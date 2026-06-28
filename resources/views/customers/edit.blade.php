<x-app-layout>
    <x-slot name="header">
        Edit Prospek Customer: {{ $customer->nama }}
    </x-slot>

    <div class="mb-4">
        <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline">← Kembali</a>
    </div>

    <div class="card bg-base-100 shadow-sm glass-panel max-w-2xl">
        <div class="card-body">
            <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text">Nama Customer</span></label>
                    <input type="text" name="nama" class="input input-bordered w-full" required value="{{ old('nama', $customer->nama) }}" />
                    @error('nama') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mb-4">
                    <label class="label"><span class="label-text">Produk yang Diminati</span></label>
                    <select name="product_id" class="select select-bordered w-full" required>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ (old('product_id', $customer->product_id) == $product->id) ? 'selected' : '' }}>{{ $product->nama }}</option>
                        @endforeach
                    </select>
                    @error('product_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mb-4">
                    <label class="label"><span class="label-text">Nomor WhatsApp</span></label>
                    <input type="text" name="whatsapp" class="input input-bordered w-full" required value="{{ old('whatsapp', $customer->whatsapp) }}" />
                    @error('whatsapp') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mb-4">
                    <label class="label"><span class="label-text">Lokasi</span></label>
                    <input type="text" name="lokasi" class="input input-bordered w-full" required value="{{ old('lokasi', $customer->lokasi) }}" />
                    @error('lokasi') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mb-4">
                    <label class="label"><span class="label-text">Status Customer</span></label>
                    <select name="status" class="select select-bordered w-full" required>
                        <option value="Prospek Customer" {{ $customer->status == 'Prospek Customer' ? 'selected' : '' }}>Prospek Customer</option>
                        <option value="Negosiasi" {{ $customer->status == 'Negosiasi' ? 'selected' : '' }}>Negosiasi</option>
                        <option value="Customer Aktif" {{ $customer->status == 'Customer Aktif' ? 'selected' : '' }}>Customer Aktif</option>
                    </select>
                    @error('status') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-warning w-full">Update Customer</button>
            </form>
        </div>
    </div>
</x-app-layout>
