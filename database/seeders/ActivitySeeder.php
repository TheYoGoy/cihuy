<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Activity, CostDriver};

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $activities = [
            // Aktivitas Pra-Produksi
            [
                'name' => 'Penyortiran Biji Kopi',
                'description' => 'Pemilahan biji kopi berdasarkan ukuran dan kualitas',
                'driver' => 'Jam Tenaga Kerja'
            ],
            [
                'name' => 'Pencucian Biji',
                'description' => 'Pembersihan biji kopi dari kotoran dan kulit',
                'driver' => 'Air'
            ],
            
            // Aktivitas Pengeringan
            [
                'name' => 'Pengeringan Natural',
                'description' => 'Pengeringan biji kopi dengan sinar matahari',
                'driver' => 'Luas Lantai'
            ],
            [
                'name' => 'Pengeringan Mesin',
                'description' => 'Pengeringan biji dengan mesin pengering',
                'driver' => 'Listrik'
            ],
            
            // Aktivitas Roasting
            [
                'name' => 'Roasting Light',
                'description' => 'Pemanggangan ringan untuk kopi mild',
                'driver' => 'Gas LPG'
            ],
            [
                'name' => 'Roasting Medium',
                'description' => 'Pemanggangan sedang untuk kopi balance',
                'driver' => 'Gas LPG'
            ],
            [
                'name' => 'Roasting Dark',
                'description' => 'Pemanggangan gelap untuk kopi bold',
                'driver' => 'Gas LPG'
            ],
            
            // Aktivitas Penggilingan
            [
                'name' => 'Penggilingan Coarse',
                'description' => 'Penggilingan kasar untuk french press',
                'driver' => 'Listrik'
            ],
            [
                'name' => 'Penggilingan Medium',
                'description' => 'Penggilingan sedang untuk drip coffee',
                'driver' => 'Listrik'
            ],
            [
                'name' => 'Penggilingan Fine',
                'description' => 'Penggilingan halus untuk espresso',
                'driver' => 'Listrik'
            ],
            
            // Aktivitas Blending
            [
                'name' => 'Blending Premium',
                'description' => 'Pencampuran kopi premium grade',
                'driver' => 'Jam Mesin'
            ],
            [
                'name' => 'Blending Regular',
                'description' => 'Pencampuran kopi regular grade',
                'driver' => 'Jam Mesin'
            ],
            
            // Aktivitas Quality Control
            [
                'name' => 'Quality Testing',
                'description' => 'Pengujian rasa dan aroma kopi',
                'driver' => 'Jam Tenaga Kerja'
            ],
            
            // Aktivitas Pengemasan
            [
                'name' => 'Pengemasan Sachet',
                'description' => 'Pengemasan kopi dalam sachet',
                'driver' => 'Jam Mesin'
            ],
            [
                'name' => 'Pengemasan Pouch',
                'description' => 'Pengemasan kopi dalam standing pouch',
                'driver' => 'Jam Mesin'
            ],
            [
                'name' => 'Pengemasan Tin',
                'description' => 'Pengemasan kopi dalam kaleng',
                'driver' => 'Jam Mesin'
            ],
            [
                'name' => 'Labeling & Coding',
                'description' => 'Pemberian label dan kode produk',
                'driver' => 'Jam Mesin'
            ],
            
            // Aktivitas Penyimpanan
            [
                'name' => 'Penyimpanan Gudang',
                'description' => 'Penyimpanan produk jadi',
                'driver' => 'Luas Lantai'
            ]
        ];

        foreach ($activities as $activityData) {
            $costDriver = CostDriver::where('name', $activityData['driver'])->first();
            
            if ($costDriver) {
                Activity::firstOrCreate(
                    ['name' => $activityData['name']],
                    [
                        'description' => $activityData['description'],
                        'primary_cost_driver_id' => $costDriver->id,
                    ]
                );
            }
        }

        echo "✅ " . count($activities) . " activities berhasil dibuat!\n";
    }
}