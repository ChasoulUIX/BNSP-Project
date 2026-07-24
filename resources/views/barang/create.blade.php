<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Tambah Barang</h1>
            <p class="text-sm text-gray-500 mt-0.5">Isi data barang baru</p>
        </div>
    </x-slot>

    <div class="max-w-xl">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Form Tambah Barang</h3>
            </div>
            <div class="p-6">
                @if ($errors->any())
                <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl">
                    @foreach ($errors->all() as $error)
                    <p class="text-sm text-red-600">{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <form action="{{ route('barang.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="nama_barang" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Barang</label>
                        <input type="text" id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition"
                               placeholder="Masukkan nama barang">
                    </div>
                    <div>
                        <label for="harga" class="block text-sm font-semibold text-gray-700 mb-1.5">Harga (Rp)</label>
                        <input type="number" id="harga" name="harga" value="{{ old('harga') }}" min="0" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition"
                               placeholder="Masukkan harga satuan">
                    </div>
                    <div>
                        <label for="stok" class="block text-sm font-semibold text-gray-700 mb-1.5">Stok Awal</label>
                        <input type="number" id="stok" name="stok" value="{{ old('stok') }}" min="0" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition"
                               placeholder="Masukkan jumlah stok">
                    </div>
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                            Simpan Barang
                        </button>
                        <a href="{{ route('barang.index') }}"
                           class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
