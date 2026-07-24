<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::with('barang')->latest()->paginate(10);
        return view('penjualan.index', compact('penjualan'));
    }

    public function create()
    {
        $barangs = Barang::where('stok', '>', 0)->get();
        return view('penjualan.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $barang = Barang::find($request->barang_id);

        if ($barang->stok < $request->jumlah) {
            return back()->withErrors(['jumlah' => 'Stok tidak mencukupi. Stok tersedia: ' . $barang->stok])->withInput();
        }

        $totalHarga = $barang->harga * $request->jumlah;

        DB::transaction(function () use ($barang, $request, $totalHarga) {
            Penjualan::create([
                'barang_id' => $barang->id,
                'jumlah' => $request->jumlah,
                'total_harga' => $totalHarga,
            ]);

            $barang->decrement('stok', $request->jumlah);
        });

        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil. Total: Rp ' . number_format($totalHarga, 0, ',', '.'));
    }

    public function pdf(Request $request)
    {
        $query = Penjualan::with('barang');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('barang_nama')) {
            $query->whereHas('barang', function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->barang_nama . '%');
            });
        }

        $penjualan = $query->latest()->get();

        $total = $penjualan->sum('total_harga');
        $jumlahTransaksi = $penjualan->count();

        $data = [
            'title' => 'Laporan Penjualan',
            'date_start' => $request->input('start_date'),
            'date_end' => $request->input('end_date'),
            'barang_nama' => $request->input('barang_nama'),
            'penjualan' => $penjualan,
            'total' => $total,
            'jumlah_transaksi' => $jumlahTransaksi,
            'generated_at' => now(),
        ];

        $pdf = Pdf::loadView('penjualan.pdf_report', $data);

        if ($request->has('download')) {
            return $pdf->download('laporan-penjualan-' . now()->format('Y-m-d-H-i-s') . '.pdf');
        }

        return $pdf->stream('laporan-penjualan.pdf');
    }
}
