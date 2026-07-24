<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-0.5">Ringkasan sistem manajemen barang</p>
        </div>
    </x-slot>

    @php
        $totalBarang = \App\Models\Barang::count();
        $totalStok = \App\Models\Barang::sum('stok');
        $stokHabis = \App\Models\Barang::where('stok', 0)->count();
        $totalTransaksi = \App\Models\Penjualan::count();
        $totalOmset = \App\Models\Penjualan::sum('total_harga');
        $barangHabis = \App\Models\Barang::where('stok', 0)->pluck('nama_barang');
        $barangStokRendah = \App\Models\Barang::where('stok', '>', 0)->where('stok', '<=', 5)->pluck('nama_barang');
    @endphp

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Total Barang</p>
                <p class="text-xl font-bold text-gray-900">{{ $totalBarang }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H9M9 5a2 2 0 000 4h6a2 2 0 100-4H9z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Total Stok</p>
                <p class="text-xl font-bold text-gray-900">{{ number_format($totalStok) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Total Transaksi</p>
                <p class="text-xl font-bold text-gray-900">{{ $totalTransaksi }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Total Omset</p>
                <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalOmset, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Penjualan Terakhir --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Penjualan Terakhir</h3>
                <a href="{{ route('penjualan.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Lihat semua →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @php $recent = \App\Models\Penjualan::with('barang')->latest()->take(5)->get(); @endphp
                @forelse ($recent as $item)
                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-gray-50/60 transition-colors">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $item->barang->nama_barang ?? '-' }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $item->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500">{{ $item->jumlah }} unit</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-400">
                    <p class="text-sm">Belum ada penjualan</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Peringatan Stok --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Peringatan Stok</h3>
                <a href="{{ route('barang.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Lihat semua →</a>
            </div>
            <div class="p-6 space-y-3">
                @if ($barangHabis->isEmpty() && $barangStokRendah->isEmpty())
                <div class="text-center py-6 text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium">Semua stok aman</p>
                </div>
                @endif
                @foreach ($barangHabis as $nama)
                <div class="flex items-center gap-3 p-3 bg-red-50 rounded-xl">
                    <span class="w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></span>
                    <p class="text-sm text-red-700"><strong>{{ $nama }}</strong> — stok habis</p>
                </div>
                @endforeach
                @foreach ($barangStokRendah as $nama)
                <div class="flex items-center gap-3 p-3 bg-yellow-50 rounded-xl">
                    <span class="w-2 h-2 rounded-full bg-yellow-500 flex-shrink-0"></span>
                    <p class="text-sm text-yellow-700"><strong>{{ $nama }}</strong> — stok rendah</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('barang.create') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md hover:-translate-y-0.5 transition-all group">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">Tambah Barang</p>
                <p class="text-xs text-gray-500">Input barang baru</p>
            </div>
        </a>
        <a href="{{ route('penjualan.create') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md hover:-translate-y-0.5 transition-all group">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">Catat Penjualan</p>
                <p class="text-xs text-gray-500">Transaksi baru</p>
            </div>
        </a>
        <a href="{{ route('penjualan.pdf', ['download' => true]) }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md hover:-translate-y-0.5 transition-all group">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">Export Laporan</p>
                <p class="text-xs text-gray-500">Download PDF</p>
            </div>
        </a>
    </div>
</x-app-layout>
