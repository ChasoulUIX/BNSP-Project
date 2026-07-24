<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Catat Penjualan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Input transaksi penjualan baru</p>
        </div>
    </x-slot>

    <div class="max-w-xl">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Form Penjualan</h3>
            </div>
            <div class="p-6">
                @if ($errors->any())
                <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl">
                    @foreach ($errors->all() as $error)
                    <p class="text-sm text-red-600">{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                @if ($barangs->isEmpty())
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="text-sm text-gray-500 font-medium">Tidak ada barang tersedia</p>
                    <p class="text-xs text-gray-400 mt-1">Tambah barang terlebih dahulu</p>
                    <a href="{{ route('barang.create') }}" class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-colors">
                        Tambah Barang
                    </a>
                </div>
                @else
                <form action="{{ route('penjualan.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="barang_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Pilih Barang</label>
                        <select id="barang_id" name="barang_id" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition bg-white">
                            <option value="">-- Pilih Barang --</option>
                            @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}" data-harga="{{ $barang->harga }}" data-stok="{{ $barang->stok }}">
                                {{ $barang->nama_barang }} — Stok: {{ $barang->stok }} — Rp {{ number_format($barang->harga, 0, ',', '.') }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Info barang --}}
                    <div id="barang-info" class="hidden p-4 bg-indigo-50 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-indigo-600 font-medium">Stok tersedia</p>
                                <p class="text-lg font-bold text-indigo-900" id="stok-info">0 Unit</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-indigo-600 font-medium">Harga satuan</p>
                                <p class="text-lg font-bold text-indigo-900" id="harga-info">Rp 0</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="jumlah" class="block text-sm font-semibold text-gray-700 mb-1.5">Jumlah</label>
                        <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah') }}" min="1" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition"
                               placeholder="Masukkan jumlah unit">
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-600">Total Harga</p>
                            <p class="text-xl font-bold text-gray-900">Rp <span id="total-harga">0</span></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                            Simpan Penjualan
                        </button>
                        <a href="{{ route('penjualan.index') }}"
                           class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                            Batal
                        </a>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const select = document.getElementById('barang_id');
            const jumlahInput = document.getElementById('jumlah');
            const totalDisplay = document.getElementById('total-harga');
            const barangInfo = document.getElementById('barang-info');
            const stokInfo = document.getElementById('stok-info');
            const hargaInfo = document.getElementById('harga-info');
            if (!select) return;

            function update() {
                const opt = select.options[select.selectedIndex];
                const harga = parseFloat(opt?.getAttribute('data-harga') || 0);
                const stok = parseInt(opt?.getAttribute('data-stok') || 0);
                const jumlah = parseInt(jumlahInput?.value || 0);

                if (opt?.value) {
                    barangInfo.classList.remove('hidden');
                    stokInfo.textContent = stok + ' Unit';
                    hargaInfo.textContent = 'Rp ' + harga.toLocaleString('id-ID');
                } else {
                    barangInfo.classList.add('hidden');
                }

                if (jumlah > stok) jumlahInput.value = stok;
                const total = harga * (parseInt(jumlahInput?.value) || 0);
                totalDisplay.textContent = total.toLocaleString('id-ID');
            }

            select.addEventListener('change', update);
            jumlahInput?.addEventListener('input', update);
        });
    </script>
</x-app-layout>
