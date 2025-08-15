<?php
// sudut-timur-backend/database/seeders/ProductActivityUsageSeeder.php (NEW)

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductActivityUsage;
use App\Models\Product;
use App\Models\Activity;
use App\Models\CostDriver;
use Carbon\Carbon;

class ProductActivityUsageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productArabika = Product::where('name', 'Kopi Arabika House Blend')->first();
        $productRobusta = Product::where('name', 'Kopi Robusta Single Origin')->first();

        $activityRoasting = Activity::where('name', 'Roasting')->first();
        $activityPenggilingan = Activity::where('name', 'Penggilingan')->first();
        $activityPengemasan = Activity::where('name', 'Pengemasan')->first();

        $costDriverJamMesin = CostDriver::where('name', 'Jam Mesin')->first();
        $costDriverJumlahBatch = CostDriver::where('name', 'Jumlah Batch')->first();

        if ($productArabika && $productRobusta && $activityRoasting && $activityPenggilingan && $activityPengemasan && $costDriverJamMesin && $costDriverJumlahBatch) {
            $usageDate = Carbon::now()->subDays(2)->toDateString();

            // Kopi Arabika menggunakan Roasting (Jam Mesin)
            ProductActivityUsage::firstOrCreate([
                'product_id' => $productArabika->id,
                'activity_id' => $activityRoasting->id,
                'cost_driver_id' => $costDriverJamMesin->id,
                'usage_date' => $usageDate,
            ], [
                'quantity_consumed' => 0.15, // 0.15 jam mesin per unit Arabika
                'notes' => 'Penggunaan jam mesin roasting per unit Arabika.',
            ]);

            // Kopi Arabika menggunakan Pengemasan (Jumlah Batch)
            ProductActivityUsage::firstOrCreate([
                'product_id' => $productArabika->id,
                'activity_id' => $activityPengemasan->id,
                'cost_driver_id' => $costDriverJumlahBatch->id,
                'usage_date' => $usageDate,
            ], [
                'quantity_consumed' => 0.01, // 0.01 batch pengemasan per unit Arabika
                'notes' => 'Penggunaan batch pengemasan per unit Arabika.',
            ]);

            // Kopi Robusta menggunakan Penggilingan (Jam Mesin)
            ProductActivityUsage::firstOrCreate([
                'product_id' => $productRobusta->id,
                'activity_id' => $activityPenggilingan->id,
                'cost_driver_id' => $costDriverJamMesin->id,
                'usage_date' => $usageDate,
            ], [
                'quantity_consumed' => 0.10, // 0.10 jam mesin per unit Robusta
                'notes' => 'Penggunaan jam mesin penggilingan per unit Robusta.',
            ]);

            // Kopi Robusta menggunakan Pengemasan (Jumlah Batch)
            ProductActivityUsage::firstOrCreate([
                'product_id' => $productRobusta->id,
                'activity_id' => $activityPengemasan->id,
                'cost_driver_id' => $costDriverJumlahBatch->id,
                'usage_date' => $usageDate,
            ], [
                'quantity_consumed' => 0.008, // 0.008 batch pengemasan per unit Robusta
                'notes' => 'Penggunaan batch pengemasan per unit Robusta.',
            ]);
        }
    }
}
