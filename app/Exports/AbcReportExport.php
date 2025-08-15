<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Activity;
use App\Models\ProductActivityUsage;
use App\Models\CostActivityAllocation;
use App\Models\ActivityCostDriverUsage;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AbcReportExport implements FromView
{
    protected $month, $year;

    public function __construct($month = null, $year = null)
    {
        $this->month = $month ?? now()->month;
        $this->year = $year ?? now()->year;
    }

    public function view(): View
    {
        try {
            // Get all products and activities safely
            $products = Product::all();
            $activities = Activity::with('primaryCostDriver')->get();

            // Initialize collections
            $activityReports = collect();
            $productReports = collect();
            $productActivityDetails = [];

            // Process each activity
            foreach ($activities as $activity) {
                try {
                    // Get allocated cost for this activity
                    $allocatedCost = CostActivityAllocation::where('activity_id', $activity->id)
                        ->whereBetween('allocation_date', [
                            Carbon::create($this->year, $this->month, 1)->startOfMonth(),
                            Carbon::create($this->year, $this->month, 1)->endOfMonth()
                        ])
                        ->sum('allocated_amount');

                    // Get driver usage for this activity
                    $driverUsage = ActivityCostDriverUsage::where('activity_id', $activity->id)
                        ->where('cost_driver_id', $activity->primary_cost_driver_id)
                        ->whereBetween('usage_date', [
                            Carbon::create($this->year, $this->month, 1)->startOfMonth(),
                            Carbon::create($this->year, $this->month, 1)->endOfMonth()
                        ])
                        ->sum('usage_quantity');

                    // Calculate rate safely
                    $rate = $driverUsage > 0 ? $allocatedCost / $driverUsage : 0;

                    $activityReports->push([
                        'activity' => $activity,
                        'allocated_cost' => $allocatedCost,
                        'driver_usage' => $driverUsage,
                        'activity_rate' => $rate,
                    ]);
                } catch (\Exception $e) {
                    \Log::error("Error processing activity {$activity->id}: " . $e->getMessage());
                    continue;
                }
            }

            // Process each product
            foreach ($products as $product) {
                try {
                    // Get product activity usages
                    $productUsages = ProductActivityUsage::where('product_id', $product->id)
                        ->whereBetween('usage_date', [
                            Carbon::create($this->year, $this->month, 1)->startOfMonth(),
                            Carbon::create($this->year, $this->month, 1)->endOfMonth()
                        ])
                        ->get();

                    $totalProductCost = 0;
                    $productActivityDetails[$product->id] = [];

                    foreach ($productUsages as $usage) {
                        // Find the corresponding activity report safely
                        $activityReport = $activityReports->firstWhere('activity.id', $usage->activity_id);
                        
                        if ($activityReport && isset($activityReport['activity'])) {
                            $allocatedCost = $usage->quantity_consumed * $activityReport['activity_rate'];
                            $totalProductCost += $allocatedCost;

                            // Add to product activity details
                            $activity = $activityReport['activity'];
                            $productActivityDetails[$product->id][] = [
                                'activity_name' => $activity->name ?? '-',
                                'cost_driver_name' => optional($activity->primaryCostDriver)->name ?? '-',
                                'cost_driver_unit' => optional($activity->primaryCostDriver)->unit ?? '-',
                                'quantity_consumed' => $usage->quantity_consumed,
                                'activity_rate' => $activityReport['activity_rate'],
                                'allocated_cost' => $allocatedCost,
                            ];
                        }
                    }

                    // Get production data safely
                    $productionQty = optional($product->productions())
                        ->whereMonth('production_date', $this->month)
                        ->whereYear('production_date', $this->year)
                        ->sum('quantity') ?? 0;

                    $unitCost = $productionQty > 0 ? $totalProductCost / $productionQty : 0;

                    $productReports->push([
                        'product' => $product,
                        'total_production_quantity' => $productionQty,
                        'total_cost' => $totalProductCost,
                        'unit_cost' => $unitCost,
                    ]);
                } catch (\Exception $e) {
                    \Log::error("Error processing product {$product->id}: " . $e->getMessage());
                    continue;
                }
            }

            return view('exports.abc-reports-pdf-complete', [
                'activityReports' => $activityReports,
                'productReports' => $productReports,
                'productActivityDetails' => $productActivityDetails,
                'selectedMonth' => $this->month,
                'selectedYear' => $this->year,
            ]);

        } catch (\Exception $e) {
            \Log::error('ABC Report Export Error: ' . $e->getMessage());
            
            // Return empty view with error message
            return view('exports.abc-reports-pdf-complete', [
                'activityReports' => collect(),
                'productReports' => collect(),
                'productActivityDetails' => [],
                'selectedMonth' => $this->month,
                'selectedYear' => $this->year,
                'error' => 'Terjadi kesalahan saat memproses data laporan ABC'
            ]);
        }
    }
}