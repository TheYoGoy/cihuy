<?php
// sudut-timur-backend/database/seeders/CostActivityAllocationSeeder.php (NEW)

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CostActivityAllocation;
use App\Models\Cost;
use App\Models\Activity;
use Carbon\Carbon;

class CostActivityAllocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $costListrik = Cost::where('name', 'Biaya Listrik Pabrik')->first();
        $costGaji = Cost::where('name', 'Gaji Tenaga Kerja Produksi')->first();
        $costPenyusutan = Cost::where('name', 'Biaya Penyusutan Mesin')->first();

        $activityRoasting = Activity::where('name', 'Roasting')->first();
        $activityPenggilingan = Activity::where('name', 'Penggilingan')->first();
        $activityPengemasan = Activity::where('name', 'Pengemasan')->first();

        if ($costListrik && $costGaji && $costPenyusutan && $activityRoasting && $activityPenggilingan && $activityPengemasan) {
            $allocationDate = Carbon::now()->startOfMonth()->toDateString(); // Alokasi awal bulan

            // Alokasi Biaya Listrik
            CostActivityAllocation::firstOrCreate([
                'cost_id' => $costListrik->id,
                'activity_id' => $activityRoasting->id,
                'allocation_date' => $allocationDate,
            ], [
                'allocated_amount' => 2000000.00, // 40% dari 5jt
                'notes' => 'Alokasi listrik ke Roasting.',
            ]);
            CostActivityAllocation::firstOrCreate([
                'cost_id' => $costListrik->id,
                'activity_id' => $activityPenggilingan->id,
                'allocation_date' => $allocationDate,
            ], [
                'allocated_amount' => 1500000.00, // 30% dari 5jt
                'notes' => 'Alokasi listrik ke Penggilingan.',
            ]);
            CostActivityAllocation::firstOrCreate([
                'cost_id' => $costListrik->id,
                'activity_id' => $activityPengemasan->id,
                'allocation_date' => $allocationDate,
            ], [
                'allocated_amount' => 1500000.00, // 30% dari 5jt
                'notes' => 'Alokasi listrik ke Pengemasan.',
            ]);

            // Alokasi Gaji Tenaga Kerja
            CostActivityAllocation::firstOrCreate([
                'cost_id' => $costGaji->id,
                'activity_id' => $activityRoasting->id,
                'allocation_date' => $allocationDate,
            ], [
                'allocated_amount' => 6000000.00, // 40% dari 15jt
                'notes' => 'Alokasi gaji ke Roasting.',
            ]);
            CostActivityAllocation::firstOrCreate([
                'cost_id' => $costGaji->id,
                'activity_id' => $activityPenggilingan->id,
                'allocation_date' => $allocationDate,
            ], [
                'allocated_amount' => 4500000.00, // 30% dari 15jt
                'notes' => 'Alokasi gaji ke Penggilingan.',
            ]);
            CostActivityAllocation::firstOrCreate([
                'cost_id' => $costGaji->id,
                'activity_id' => $activityPengemasan->id,
                'allocation_date' => $allocationDate,
            ], [
                'allocated_amount' => 4500000.00, // 30% dari 15jt
                'notes' => 'Alokasi gaji ke Pengemasan.',
            ]);

            // Alokasi Biaya Penyusutan
            CostActivityAllocation::firstOrCreate([
                'cost_id' => $costPenyusutan->id,
                'activity_id' => $activityRoasting->id,
                'allocation_date' => $allocationDate,
            ], [
                'allocated_amount' => 1200000.00, // 60% dari 2jt
                'notes' => 'Alokasi penyusutan ke Roasting.',
            ]);
            CostActivityAllocation::firstOrCreate([
                'cost_id' => $costPenyusutan->id,
                'activity_id' => $activityPenggilingan->id,
                'allocation_date' => $allocationDate,
            ], [
                'allocated_amount' => 800000.00, // 40% dari 2jt
                'notes' => 'Alokasi penyusutan ke Penggilingan.',
            ]);
        }
    }
}
