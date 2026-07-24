<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #1f2937; padding: 40px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #4f46e5; padding-bottom: 20px; }
        .header h1 { font-size: 22px; color: #1e1b4b; margin-bottom: 5px; }
        .header p { font-size: 11px; color: #6b7280; }
        .info-box { background: #f3f4f6; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px; }
        .info-box table { width: auto; }
        .info-box td { padding: 2px 12px 2px 0; font-size: 11px; border: none; }
        .info-box td.label { color: #6b7280; }
        .info-box td.value { font-weight: bold; color: #374151; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #4f46e5; color: #fff; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 9px 8px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
        tr:nth-child(even) { background: #f9fafb; }
        .total-row { background: #eef2ff; font-weight: bold; }
        .total-row td { border-top: 2px solid #4f46e5; padding-top: 12px; font-size: 12px; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 15px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PENJUALAN</h1>
        <p>Sistem Manajemen Barang &mdash; {{ $generated_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td class="label">Periode:</td>
                <td class="value">{{ $date_start ? \Carbon\Carbon::parse($date_start)->format('d/m/Y') : 'Semua' }} s/d {{ $date_end ? \Carbon\Carbon::parse($date_end)->format('d/m/Y') : 'Semua' }}</td>
            </tr>
            <tr>
                <td class="label">Total Transaksi:</td>
                <td class="value">{{ $jumlah_transaksi }}</td>
            </tr>
            <tr>
                <td class="label">Total Omset:</td>
                <td class="value">Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    @if ($barang_nama)
    <p style="margin-bottom: 15px; font-size: 11px; color: #6b7280;">Filter Barang: <strong>{{ $barang_nama }}</strong></p>
    @endif

    @if ($penjualan->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 40px;" class="text-center">No</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th class="text-center">Jumlah</th>
                <th class="text-right">Harga Satuan</th>
                <th class="text-right">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualan as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                <td class="text-center">{{ $item->jumlah }}</td>
                <td class="text-right">Rp {{ number_format($item->barang->harga ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" class="text-right">TOTAL KESELURUHAN</td>
                <td class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    @else
    <p style="text-align: center; padding: 40px 0; color: #9ca3af;">Tidak ada data penjualan untuk filter yang dipilih.</p>
    @endif

    <div class="footer">
        <p>Dicetak otomatis pada {{ $generated_at->format('d F Y H:i:s') }} &bull; Sistem Manajemen Barang</p>
    </div>
</body>
</html>
