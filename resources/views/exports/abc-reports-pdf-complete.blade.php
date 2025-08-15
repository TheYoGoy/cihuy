<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan ABC Lengkap - {{ \Carbon\Carbon::create(null, $selectedMonth)->translatedFormat('F') }} {{ $selectedYear }}</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 11px;
            margin: 40px;
            color: #333;
        }

        .kop {
            margin-bottom: 20px;
        }

        .kop h2 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .kop p {
            margin: 2px 0;
            font-size: 10px;
        }

        .judul {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }

        h3 {
            font-size: 13px;
            margin-top: 25px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 5px 7px;
            font-size: 10px;
            vertical-align: top;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 10px;
        }

        .footer p {
            margin: 0;
        }

        .text-bold {
            font-weight: bold;
        }

        .product-detail-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
        }

        .product-detail-table th,
        .product-detail-table td {
            border: 1px dashed #bbb;
            padding: 3px 5px;
            font-size: 9px;
            background-color: #fafafa;
        }

        .product-detail-table th {
            background-color: #e8e8e8;
            font-weight: normal;
            text-align: center;
        }

        .sub-total-row td {
            font-weight: bold;
            background-color: #f5f5f5;
        }
    </style>
</head>

<body>

    <div class="kop">
        <h2>Kopi Sudut Timur </h2>
        <p>Jl. Bina Marga No. 8, Cipayung, Kota Jakarta Timur, 13840</p>
        <p>Tanggal: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
    </div>

    <div class="judul">
        LAPORAN BIAYA BERBASIS AKTIVITAS (ABC) LENGKAP<br>
        PERIODE: {{ \Carbon\Carbon::create(null, $selectedMonth)->translatedFormat('F Y') }}
    </div>

    <h3>1. Ringkasan Biaya Aktivitas dan Rate</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 25%;">Nama Aktivitas</th>
                <th style="width: 15%;">Driver Biaya Utama</th>
                <th style="width: 10%;">Unit Driver</th>
                <th style="width: 15%;" class="text-right">Total Biaya Pool</th>
                <th style="width: 15%;" class="text-center">Total Penggunaan Driver</th>
                <th style="width: 15%;" class="text-right">Rate per Unit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activityReports as $index => $report)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $report['activity']->name ?? '-' }}</td>
                <td>{{ $report['activity']->primaryCostDriver->name ?? '-' }}</td>
                <td class="text-center">{{ $report['activity']->primaryCostDriver->unit ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($report['allocated_cost'], 0, ',', '.') }}</td>
                <td class="text-center">{{ number_format($report['driver_usage'], 2, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($report['activity_rate'], 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data aktivitas dan rate untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h3>2. Detail Alokasi Biaya Aktivitas per Produk</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 15%;">Nama Produk</th>
                <th style="width: 40%;">Detail Alokasi Aktivitas</th>
                <th style="width: 10%;" class="text-center">Total Produksi (Unit)</th>
                <th style="width: 15%;" class="text-right">Total Biaya Produk</th>
                <th style="width: 15%;" class="text-right">Biaya per Unit Produk</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productReports as $index => $productReport)
            @php
                $product = $productReport['product'];
                $productId = $product->id ?? null;
            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $product->name ?? '-' }}</td>
                <td>
                    @if (!empty($productActivityDetails[$productId]))
                    <table class="product-detail-table">
                        <thead>
                            <tr>
                                <th style="width: 25%;">Aktivitas</th>
                                <th style="width: 20%;">Driver (Unit)</th>
                                <th style="width: 15%;" class="text-center">Konsumsi</th>
                                <th style="width: 20%;" class="text-right">Rate</th>
                                <th style="width: 20%;" class="text-right">Alokasi Biaya</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $subTotalAllocatedCost = 0; @endphp
                            @foreach($productActivityDetails[$productId] as $detail)
                            <tr>
                                <td>{{ $detail['activity_name'] }}</td>
                                <td>{{ $detail['cost_driver_name'] }} ({{ $detail['cost_driver_unit'] }})</td>
                                <td class="text-center">{{ number_format($detail['quantity_consumed'], 2, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($detail['activity_rate'], 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($detail['allocated_cost'], 0, ',', '.') }}</td>
                            </tr>
                            @php $subTotalAllocatedCost += $detail['allocated_cost']; @endphp
                            @endforeach
                            <tr class="sub-total-row">
                                <td colspan="4" class="text-right">Subtotal Biaya Alokasi:</td>
                                <td class="text-right">Rp {{ number_format($subTotalAllocatedCost, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    @else
                    Tidak ada detail aktivitas.
                    @endif
                </td>
                <td class="text-center">{{ number_format($productReport['total_production_quantity'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($productReport['total_cost'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($productReport['unit_cost'], 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data biaya produk untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <br><br><br>
        <p class="text-bold">(___________________________)</p>
        <p>Mengetahui</p>
    </div>

</body>

</html>
