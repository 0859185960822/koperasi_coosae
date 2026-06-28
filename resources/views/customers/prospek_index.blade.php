<x-app-layout>
    <x-slot name="header">Prospek Customer</x-slot>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
        <x-ui.button onclick="modalTambah.showModal()">+ Tambah Prospek</x-ui.button>
        <form action="{{ route('prospek.index') }}" method="GET" class="flex w-full max-w-sm items-center space-x-2">
            <x-ui.input type="text" name="search" placeholder="Cari customer..." value="{{ request('search') }}" />
            <x-ui.button type="submit">Cari</x-ui.button>
        </form>
    </div>

    <x-ui.card>
        <x-ui.card-content class="pt-6">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Nama Customer</x-ui.table-head>
                        <x-ui.table-head>Produk Diminati</x-ui.table-head>
                        <x-ui.table-head>No. WA</x-ui.table-head>
                        <x-ui.table-head class="text-right">Opsi</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($customers as $c)
                    <x-ui.table-row>
                        <x-ui.table-cell class="font-medium">{{ $c->nama }}</x-ui.table-cell>
                        <x-ui.table-cell>{{ $c->product->nama ?? '-' }}</x-ui.table-cell>
                        <x-ui.table-cell>{{ $c->whatsapp }}</x-ui.table-cell>
                        <x-ui.table-cell class="text-right space-x-1">
                            <x-ui.button variant="secondary" size="sm" onclick="modalUpdate{{ $c->id }}.showModal()">Update</x-ui.button>
                            <x-ui.button variant="outline" size="sm" onclick="modalDetail{{ $c->id }}.showModal()">Detail</x-ui.button>
                            <form action="{{ route('prospek.destroy', $c->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus customer ini?')">
                                @csrf @method('DELETE')
                                <x-ui.button variant="destructive" size="sm">Delete</x-ui.button>
                            </form>
                        </x-ui.table-cell>
                    </x-ui.table-row>
                    @empty
                    <x-ui.table-row>
                        <x-ui.table-cell colspan="4" class="text-center text-muted-foreground h-24">Belum ada data prospek customer.</x-ui.table-cell>
                    </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
            <div class="mt-4">{{ $customers->links() }}</div>
        </x-ui.card-content>
    </x-ui.card>

    {{-- Modal Tambah --}}
    <x-ui.modal id="modalTambah" title="Tambah Prospek Customer">
        <form action="{{ route('prospek.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-2">
                <x-ui.label>Nama Customer</x-ui.label>
                <x-ui.input name="nama" required />
            </div>
            <div class="space-y-2">
                <x-ui.label>Produk yang Diminati</x-ui.label>
                <select name="product_id" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring" required>
                    <option value="" disabled selected>Pilih Produk...</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <x-ui.label>No. WhatsApp</x-ui.label>
                <x-ui.input name="whatsapp" required />
            </div>
            <div class="space-y-2">
                <x-ui.label>Lokasi (Kota/Kabupaten)</x-ui.label>
                <x-ui.input name="lokasi" required />
            </div>
            <div class="space-y-2">
                <x-ui.label>Keterangan</x-ui.label>
                <textarea name="keterangan" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"></textarea>
            </div>
            <div class="flex justify-end pt-4">
                <x-ui.button type="submit">Simpan</x-ui.button>
            </div>
        </form>
    </x-ui.modal>

    {{-- Modal Update & Detail for each customer --}}
    @foreach($customers as $c)
    <x-ui.modal id="modalUpdate{{ $c->id }}" title="Update Prospek">
        <form action="{{ route('prospek.update', $c->id) }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div class="space-y-2">
                <x-ui.label>Nama Customer</x-ui.label>
                <x-ui.input name="nama" value="{{ $c->nama }}" required />
            </div>
            <div class="space-y-2">
                <x-ui.label>Produk yang Diminati</x-ui.label>
                <select name="product_id" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring" required>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ $c->product_id == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <x-ui.label>No. WhatsApp</x-ui.label>
                <x-ui.input name="whatsapp" value="{{ $c->whatsapp }}" required />
            </div>
            <div class="space-y-2">
                <x-ui.label>Lokasi</x-ui.label>
                <x-ui.input name="lokasi" value="{{ $c->lokasi }}" required />
            </div>
            <div class="space-y-2">
                <x-ui.label>Keterangan</x-ui.label>
                <textarea name="keterangan" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">{{ $c->keterangan }}</textarea>
            </div>
            <div class="flex justify-end pt-4">
                <x-ui.button type="submit">Update</x-ui.button>
            </div>
        </form>
    </x-ui.modal>

    <x-ui.modal id="modalDetail{{ $c->id }}" title="Detail Prospek">
        <div class="space-y-3 text-sm">
            <div class="grid grid-cols-3 border-b pb-2"><span class="font-semibold">Nama:</span> <span class="col-span-2">{{ $c->nama }}</span></div>
            <div class="grid grid-cols-3 border-b pb-2"><span class="font-semibold">Produk:</span> <span class="col-span-2">{{ $c->product->nama ?? '-' }}</span></div>
            <div class="grid grid-cols-3 border-b pb-2"><span class="font-semibold">No. WA:</span> <span class="col-span-2">{{ $c->whatsapp }}</span></div>
            <div class="grid grid-cols-3 border-b pb-2"><span class="font-semibold">Lokasi:</span> <span class="col-span-2">{{ $c->lokasi }}</span></div>
            <div class="grid grid-cols-3 border-b pb-2"><span class="font-semibold">Status:</span> <span class="col-span-2"><x-ui.badge>{{ $c->status }}</x-ui.badge></span></div>
            <div class="grid grid-cols-3 pb-2"><span class="font-semibold">Keterangan:</span> <span class="col-span-2 text-muted-foreground">{{ $c->keterangan ?? '-' }}</span></div>
        </div>
    </x-ui.modal>
    @endforeach

</x-app-layout>
