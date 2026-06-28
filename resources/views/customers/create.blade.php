<x-app-layout>
    <x-slot name="header">
        Tambah Prospek Customer
    </x-slot>

    <div class="mb-4">
        <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline">← Kembali</a>
    </div>

    <div class="card bg-base-100 shadow-sm glass-panel max-w-2xl">
        <div class="card-body">
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text">Nama Customer</span></label>
                    <input type="text" name="nama" class="input input-bordered w-full" required value="{{ old('nama') }}" />
                    @error('nama') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mb-4">
                    <label class="label"><span class="label-text">Produk yang Diminati</span></label>
                    <select name="product_id" class="select select-bordered w-full" required>
                        <option disabled selected>Pilih Produk...</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->nama }}</option>
                        @endforeach
                    </select>
                    @error('product_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mb-4">
                    <label class="label"><span class="label-text">Nomor WhatsApp</span></label>
                    <input type="text" name="whatsapp" class="input input-bordered w-full" required value="{{ old('whatsapp') }}" />
                    @error('whatsapp') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mb-4">
                    <label class="label"><span class="label-text">Lokasi</span></label>
                    <input type="text" name="lokasi" class="input input-bordered w-full" required value="{{ old('lokasi') }}" />
                    @error('lokasi') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mb-6">
                    <label class="label"><span class="label-text">Keterangan</span></label>
                    <textarea name="keterangan" class="textarea textarea-bordered w-full">{{ old('keterangan') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary w-full">Simpan Prospek</button>
            </form>
        </div>
    </div>
</x-app-layout>
