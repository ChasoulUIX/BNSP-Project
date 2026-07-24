<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Dashboard Penjualan
                </h2>
                <p class="text-sm text-gray-500 mt-1">Ringkasan performa bisnis Anda</p>
            </div>
            <div class="text-right hidden md:block">
                <p class="text-sm text-gray-500">Tanggal Hari Ini</p>
                <p class="text-sm font-semibold text-gray-700">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            </div>
        </div>
    </x-slot>

    @php
        $totalBarang = \App\Models\Barang::count();
        $totalPenjualan = \App\Models\Penjualan::count();
        $totalOmset = \App\Models\Penjualan::sum('total_harga');
        $totalStok = \App\Models\Barang::sum('stok');
        $penjualanHariIni = \App\Models\Penjualan::whereDate('created_at', today())->sum('total_harga');
        $penjualanBulanIni = \App\Models\Penjualan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->sum('total_harga');

        // Data untuk chart penjualan 7 hari terakhir
        $labels7Hari = [];
        $data7Hari = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels7Hari[] = $date->translatedFormat('d M');
            $data7Hari[] = \App\Models\Penjualan::whereDate('created_at', $date)->sum('total_harga');
        }

        // Data untuk chart penjualan per bulan (6 bulan terakhir)
        $labelsBulan = [];
        $dataBulan = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labelsBulan[] = $date->translatedFormat('M Y');
            $dataBulan[] = \App\Models\Penjualan::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)->sum('total_harga');
        }

        // Top 5 barang terlaris
        $topBarang = \App\Models\Penjualan::selectRaw('barang_id, SUM(jumlah) as total_qty, SUM(total_harga) as total_rev')
            ->groupBy('barang_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $topBarangLabels = $topBarang->pluck('barang.nama_barang')->toArray();
        $topBarangQty = $topBarang->pluck('total_qty')->toArray();
        $topBarangColors = ['#6366f1', '#8b5cf6', '#a78bfa', '#c4b5fd', '#ddd6fe'];

        // Recent transaksi
        $recentPenjualan = \App\Models\Penjualan::with('barang')
            ->latest()
            ->limit(5)
            ->get();
    @endphp

    <div class="max-w-7xl mx-auto space-y-6">

        {{-- ═══════ Stats Cards ═══════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            {{-- Total Barang --}}
            <div class="group relative overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-blue-500/10 to-transparent rounded-bl-full"></div>
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/25 group-hover:shadow-blue-500/40 transition-shadow">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Barang</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalBarang }}</p>
                    </div>
                </div>
            </div>

            {{-- Total Transaksi --}}
            <div class="group relative overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-violet-500/10 to-transparent rounded-bl-full"></div>
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center shadow-lg shadow-violet-500/25 group-hover:shadow-violet-500/40 transition-shadow">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalPenjualan }}</p>
                    </div>
                </div>
            </div>

            {{-- Total Omset --}}
            <div class="group relative overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-emerald-500/10 to-transparent rounded-bl-full"></div>
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/25 group-hover:shadow-emerald-500/40 transition-shadow">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Omset</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalOmset, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Omset Bulan Ini --}}
            <div class="group relative overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-amber-500/10 to-transparent rounded-bl-full"></div>
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/25 group-hover:shadow-amber-500/40 transition-shadow">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Omset Bulan Ini</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════ Charts Row 1 ═══════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Penjualan 7 Hari Terakhir (Line Chart) --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Penjualan 7 Hari Terakhir</h3>
                        <p class="text-sm text-gray-500">Grafik pendapatan harian</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                </div>
                <div class="relative" style="height: 280px;">
                    <canvas id="chart7Hari"></canvas>
                </div>
            </div>

            {{-- Top 5 Barang Terlaris (Doughnut) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Barang Terlaris</h3>
                        <p class="text-sm text-gray-500">Top 5 produk</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                        </svg>
                    </div>
                </div>
                <div class="relative flex items-center justify-center" style="height: 240px;">
                    <canvas id="chartTopBarang"></canvas>
                </div>
                @if($topBarang->count() > 0)
                <div class="mt-4 space-y-2">
                    @foreach($topBarang as $idx => $item)
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full" style="background-color: {{ $topBarangColors[$idx] }}"></span>
                            <span class="text-gray-700">{{ $item->barang->nama_barang ?? '-' }}</span>
                        </div>
                        <span class="font-semibold text-gray-900">{{ $item->total_qty }} unit</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- ═══════ Charts Row 2 ═══════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Omset per Bulan (Bar Chart) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Omset per Bulan</h3>
                        <p class="text-sm text-gray-500">6 bulan terakhir</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                <div class="relative" style="height: 280px;">
                    <canvas id="chartBulanan"></canvas>
                </div>
            </div>

            {{-- Transaksi Terakhir --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Transaksi Terakhir</h3>
                        <p class="text-sm text-gray-500">5 transaksi terbaru</p>
                    </div>
                    <a href="{{ route('penjualan.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                        Lihat Semua →
                    </a>
                </div>
                <div class="overflow-hidden">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Barang</th>
                                <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Qty</th>
                                <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Total</th>
                                <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentPenjualan as $p)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="py-3 text-sm font-medium text-gray-900">{{ $p->barang->nama_barang ?? '-' }}</td>
                                <td class="py-3 text-sm text-gray-600 text-right">{{ $p->jumlah }}</td>
                                <td class="py-3 text-sm font-semibold text-gray-900 text-right">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                                <td class="py-3 text-xs text-gray-500 text-right">{{ $p->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-sm text-gray-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <span>Belum ada transaksi</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ═══════ Quick Actions ═══════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('barang.create') }}" class="group flex items-center gap-4 bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md hover:border-blue-200 transition-all duration-300 hover:-translate-y-0.5">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:shadow-blue-500/40 transition-shadow">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">Tambah Barang</p>
                    <p class="text-xs text-gray-500">Buat data barang baru</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 ml-auto group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <a href="{{ route('penjualan.create') }}" class="group flex items-center gap-4 bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md hover:border-violet-200 transition-all duration-300 hover:-translate-y-0.5">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center shadow-lg shadow-violet-500/20 group-hover:shadow-violet-500/40 transition-shadow">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">Catat Penjualan</p>
                    <p class="text-xs text-gray-500">Input transaksi baru</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 ml-auto group-hover:text-violet-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <a href="{{ route('barang.index') }}" class="group flex items-center gap-4 bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md hover:border-emerald-200 transition-all duration-300 hover:-translate-y-0.5">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:shadow-emerald-500/40 transition-shadow">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">Lihat Barang</p>
                    <p class="text-xs text-gray-500">Kelola data inventaris</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 ml-auto group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

    {{-- ═══════ Chart.js ═══════ --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        const formatRupiah = (val) => {
            if (val >= 1000000000) return 'Rp ' + (val / 1000000000).toFixed(1) + 'M';
            if (val >= 1000000) return 'Rp ' + (val / 1000000).toFixed(1) + 'jt';
            if (val >= 1000) return 'Rp ' + (val / 1000).toFixed(0) + 'rb';
            return 'Rp ' + val;
        };

        Chart.defaults.font.family = "'Figtree', 'Inter', sans-serif";
        Chart.defaults.color = '#9ca3af';

        // ── Chart 7 Hari (Line) ──
        new Chart(document.getElementById('chart7Hari'), {
            type: 'line',
            data: {
                labels: @json($labels7Hari),
                datasets: [{
                    label: 'Pendapatan',
                    data: @json($data7Hari),
                    borderColor: '#6366f1',
                    backgroundColor: (ctx) => {
                        const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 280);
                        g.addColorStop(0, 'rgba(99,102,241,0.15)');
                        g.addColorStop(1, 'rgba(99,102,241,0)');
                        return g;
                    },
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleColor: '#f9fafb',
                        bodyColor: '#f9fafb',
                        padding: 12,
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: {
                            label: (ctx) => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 12 } }
                    },
                    y: {
                        grid: { color: '#f3f4f6' },
                        ticks: {
                            font: { size: 11 },
                            callback: (v) => formatRupiah(v)
                        },
                        beginAtZero: true
                    }
                }
            }
        });

        // ── Chart Top Barang (Doughnut) ──
        new Chart(document.getElementById('chartTopBarang'), {
            type: 'doughnut',
            data: {
                labels: @json($topBarangLabels),
                datasets: [{
                    data: @json($topBarangQty),
                    backgroundColor: @json($topBarangColors),
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleColor: '#f9fafb',
                        bodyColor: '#f9fafb',
                        padding: 12,
                        cornerRadius: 10,
                        callbacks: {
                            label: (ctx) => ` ${ctx.label}: ${ctx.parsed} unit`
                        }
                    }
                }
            }
        });

        // ── Chart Bulanan (Bar) ──
        new Chart(document.getElementById('chartBulanan'), {
            type: 'bar',
            data: {
                labels: @json($labelsBulan),
                datasets: [{
                    label: 'Omset',
                    data: @json($dataBulan),
                    backgroundColor: (ctx) => {
                        const chart = ctx.chart;
                        const { ctx: canvasCtx, chartArea } = chart;
                        if (!chartArea) return '#6366f1';
                        const g = canvasCtx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                        g.addColorStop(0, '#6366f1');
                        g.addColorStop(1, '#a78bfa');
                        return g;
                    },
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleColor: '#f9fafb',
                        bodyColor: '#f9fafb',
                        padding: 12,
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: {
                            label: (ctx) => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    },
                    y: {
                        grid: { color: '#f3f4f6' },
                        ticks: {
                            font: { size: 11 },
                            callback: (v) => formatRupiah(v)
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</x-app-layout>
