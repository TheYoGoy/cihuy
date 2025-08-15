<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Cost, CostDriver};
use Carbon\Carbon;

class CostSeeder extends Seeder
{
    public function run(): void
    {
        // Generate costs untuk 6 bulan terakhir
        $months = [
            ['month' => 3, 'name' => 'Maret', 'factor' => 0.9],
            ['month' => 4, 'name' => 'April', 'factor' => 1.0],
            ['month' => 5, 'name' => 'Mei', 'factor' => 1.1],
            ['month' => 6, 'name' => 'Juni', 'factor' => 1.2],
            ['month' => 7, 'name' => 'Juli', 'factor' => 1.0],
            ['month' => 8, 'name' => 'Agustus', 'factor' => 0.95],
        ];

        $baseCosts = [
            ['name' => 'Biaya Listrik', 'base_amount' => 3200000, 'driver' => 'Listrik'],
            ['name' => 'Biaya Gas LPG', 'base_amount' => 1800000, 'driver' => 'Gas LPG'],
            ['name' => 'Biaya Air', 'base_amount' => 950000, 'driver' => 'Air'],
            ['name' => 'Gaji Tenaga Kerja Langsung', 'base_amount' => 6500000, 'driver' => 'Jam Tenaga Kerja'],
            ['name' => 'Biaya Maintenance Mesin', 'base_amount' => 2000000, 'driver' => 'Jam Mesin'],
            ['name' => 'Biaya Bahan Kemasan', 'base_amount' => 1500000, 'driver' => 'Berat Bahan'],
            ['name' => 'Overhead Pabrik', 'base_amount' => 2800000, 'driver' => 'Jumlah Batch'],
            ['name' => 'Biaya Sewa Gudang', 'base_amount' => 1200000, 'driver' => 'Luas Lantai'],
        ];

        foreach ($months as $monthData) {
            foreach ($baseCosts as $costData) {
                $costDriver = CostDriver::where('name', $costData['driver'])->first();
                
                if ($costDriver) {
                    $amount = $costData['base_amount'] * $monthData['factor'];
                    
                    Cost::firstOrCreate([
                        'name' => $costData['name'] . ' ' . $monthData['name'] . ' 2025'
                    ], [
                        'amount' => round($amount, 2),
                        'description' => "Biaya operasional bulan {$monthData['name']} 2025",
                        'cost_driver_id' => $costDriver->id,
                    ]);
                }
            }
        }

        $totalCosts = Cost::count();
        echo "✅ {$totalCosts} cost records berhasil dibuat!\n";
    }
}