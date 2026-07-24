<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jual Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-6">Form Penjualan</h3>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($barangs->isEmpty())
                        <p class="text-gray-500">Tidak ada barang tersedia untuk dijual.</p>
                    @else
                        <form action="{{ route('penjualan.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="barang_id" class="block text-sm font-medium text-gray-700 mb-2">Nama Barang</label>
                                <select id="barang_id" name="barang_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach ($barangs as $barang)
                                        <option value="{{ $barang->id }}" data-harga="{{ $barang->harga }}" data-stok="{{ $barang->stok }}">
                                            {{ $barang->nama_barang }} (Stok: {{ $barang->stok }}) - Rp {{ number_format($barang->harga, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                                <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah') }}" min="1" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Total Harga</label>
                                <div class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-lg font-semibold text-gray-900">
                                    Rp <span id="total-harga">0</span>
                                </div>
                            </div>
                            <div class="flex space-x-3">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">Simpan</button>
                                <a href="{{ route('penjualan.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-lg transition duration-200">Batal</a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const barangSelect = document.getElementById('barang_id');
            const jumlahInput = document.getElementById('jumlah');
            const totalDisplay = document.getElementById('total-harga');

            function hitungTotal() {
                const selected = barangSelect.options[barangSelect.selectedIndex];
                const harga = selected?.getAttribute('data-harga') || 0;
                const stok = parseInt(selected?.getAttribute('data-stok') || 0);
                const jumlah = parseInt(jumlahInput.value) || 0;

                if (jumlah > stok) {
                    jumlahInput.value = stok;
                }

                const total = (selected?.getAttribute('data-harga') || 0) * (parseInt(jumlahInput.value) || 0);
                totalDisplay.textContent = total.toLocaleString('id-ID');
            }

            barangSelect.addEventListener('change', function () {
                const stok = parseInt(this.options[this.selectedIndex]?.getAttribute('data-stok') || 0);
                jumlahInput.setAttribute('max', stok);
                hitungTotal();
            });

            jumlahInput.addEventListener('input', hitungTotal);
        });
    </script>
</x-app-layout>
