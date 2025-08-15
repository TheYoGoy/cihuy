<?php
// sudut-timur-backend/database/seeders/ActivityCostDriverUsageSeeder.php (NEW)

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ActivityCostDriverUsage;
use App\Models\Activity;
use App\Models\CostDriver;
use Carbon\Carbon;

class ActivityCostDriverUsageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activityRoasting = Activity::where('name', 'Roasting')->first();
        $activityPenggilingan = Activity::where('name', 'Penggilingan')->first();
        $costDriverJamMesin = CostDriver::where('name', 'Jam Mesin')->first();
        $costDriverJumlahBatch = CostDriver::where('name', 'Jumlah Batch')->first();

        if ($activityRoasting && $activityPenggilingan && $costDriverJamMesin && $costDriverJumlahBatch) {
            ActivityCostDriverUsage::firstOrCreate([
                'activity_id' => $activityRoasting->id,
                'cost_driver_id' => $costDriverJamMesin->id,
                'usage_date' => Carbon::now()->subDays(7)->toDateString(),
                'usage_quantity' => 200.5,
            ], [
                'notes' => 'Penggunaan mesin roasting untuk bulan ini.',
            ]);

            ActivityCostDriverUsage::firstOrCreate([
                'activity_id' => $activityPenggilingan->id,
                'cost_driver_id' => $costDriverJamMesin->id,
                'usage_date' => Carbon::now()->subDays(6)->toDateString(),
                'usage_quantity' => 150.0,
            ], [
                'notes' => 'Penggunaan mesin penggilingan.',
            ]);

            ActivityCostDriverUsage::firstOrCreate([
                'activity_id' => $activityRoasting->id,
                'cost_driver_id' => $costDriverJumlahBatch->id,
                'usage_date' => Carbon::now()->subDays(5)->toDateString(),
                'usage_quantity' => 10,
            ], [
                'notes' => 'Jumlah batch roasting yang diselesaikan.',
            ]);
        }
    }
}
