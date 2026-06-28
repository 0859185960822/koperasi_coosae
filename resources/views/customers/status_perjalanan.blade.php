<x-app-layout>
    <x-slot name="header">Status Perjalanan Customer</x-slot>

    <x-ui.card>
        <x-ui.card-content class="pt-6">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Nama Customer</x-ui.table-head>
                        <x-ui.table-head>Produk Diminati</x-ui.table-head>
                        <x-ui.table-head>Status</x-ui.table-head>
                        <x-ui.table-head class="text-right">Update Status</x-ui.table-head>
                        <x-ui.table-head class="text-right">Histori</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($customers as $c)
                    <x-ui.table-row>
                        <x-ui.table-cell class="font-medium">{{ $c->nama }}</x-ui.table-cell>
                        <x-ui.table-cell>{{ $c->product->nama ?? '-' }}</x-ui.table-cell>
                        <x-ui.table-cell>
                            <x-ui.badge variant="{{ $c->status == 'Customer Aktif' ? 'default' : ($c->status == 'Negosiasi' ? 'secondary' : 'outline') }}">
                                {{ $c->status }}
                            </x-ui.badge>
                        </x-ui.table-cell>
                        <x-ui.table-cell class="text-right">
                            <x-ui.button variant="outline" size="sm" onclick="modalStatusUpdate{{ $c->id }}.showModal()">Update</x-ui.button>
                        </x-ui.table-cell>
                        <x-ui.table-cell class="text-right">
                            <x-ui.button variant="secondary" size="sm" onclick="modalHistory{{ $c->id }}.showModal()">History</x-ui.button>
                        </x-ui.table-cell>
                    </x-ui.table-row>
                    @empty
                    <x-ui.table-row>
                        <x-ui.table-cell colspan="5" class="text-center text-muted-foreground h-24">Belum ada data.</x-ui.table-cell>
                    </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
            <div class="mt-4">{{ $customers->links() }}</div>
        </x-ui.card-content>
    </x-ui.card>

    @foreach($customers as $c)
    <x-ui.modal id="modalStatusUpdate{{ $c->id }}" title="Update Status: {{ $c->nama }}">
        <form action="{{ route('status.update', $c->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 max-h-[70vh] overflow-y-auto px-1">
            @csrf
            
            <div class="space-y-2">
                <x-ui.label>Status Customer</x-ui.label>
                <select name="status" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring" required>
                    @foreach(['Prospek Customer', 'Negosiasi', 'Customer Aktif'] as $status)
                        <option value="{{ $status }}" {{ $c->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <x-ui.label>Riwayat Interaksi Marketing</x-ui.label>
                <select name="jenis_interaksi" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring" required>
                    <option value="" disabled selected>Pilih Interaksi...</option>
                    @foreach(['Telepon', 'WA/ Email', 'Meeting', 'Kunjungan Lapangan', 'Presentasi Produk'] as $interaksi)
                        <option value="{{ $interaksi }}">{{ $interaksi }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <x-ui.label>Tanggal Interaksi</x-ui.label>
                <x-ui.input type="date" name="tanggal_interaksi" required value="{{ date('Y-m-d') }}" />
            </div>

            <div class="space-y-2">
                <x-ui.label>Keterangan Hasil Interaksi</x-ui.label>
                <textarea name="keterangan" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"></textarea>
            </div>

            <div class="space-y-2">
                <x-ui.label>Jenis Dokumen (Opsional)</x-ui.label>
                <select name="jenis_dokumen" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    <option value="">Tidak ada dokumen</option>
                    @foreach(['Form Pendaftaran', 'Form Kunjungan', 'Catatan Survey', 'Proposal Kerja Sama', 'Kontrak', 'Dokumen Lainnya'] as $jenis)
                        <option value="{{ $jenis }}">{{ $jenis }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <x-ui.label>Upload Dokumen</x-ui.label>
                <x-ui.input type="file" name="file_dokumen" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="p-1.5" />
            </div>

            <div class="flex justify-end pt-4">
                <x-ui.button type="submit">Update & Simpan</x-ui.button>
            </div>
        </form>
    </x-ui.modal>

    <x-ui.modal id="modalHistory{{ $c->id }}" title="Riwayat Follow Up">
        <p class="text-sm text-muted-foreground mb-4">Customer: <span class="font-semibold text-foreground">{{ $c->nama }}</span></p>
        <div class="max-h-[60vh] overflow-y-auto">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Tanggal</x-ui.table-head>
                        <x-ui.table-head>Interaksi</x-ui.table-head>
                        <x-ui.table-head>Status</x-ui.table-head>
                        <x-ui.table-head>Dokumen</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($c->followups as $fu)
                    <x-ui.table-row>
                        <x-ui.table-cell class="whitespace-nowrap">{{ $fu->tanggal_interaksi->format('d M Y') }}</x-ui.table-cell>
                        <x-ui.table-cell>
                            <div>{{ $fu->jenis_interaksi }}</div>
                            <div class="text-xs text-muted-foreground mt-1">{{ $fu->keterangan }}</div>
                        </x-ui.table-cell>
                        <x-ui.table-cell><x-ui.badge variant="outline">{{ $fu->status_saat_itu }}</x-ui.badge></x-ui.table-cell>
                        <x-ui.table-cell>
                            @forelse($fu->documents as $doc)
                                <div class="text-xs">{{ $doc->jenis_dokumen }}</div>
                            @empty
                                <span class="text-muted-foreground">-</span>
                            @endforelse
                        </x-ui.table-cell>
                    </x-ui.table-row>
                    @empty
                    <x-ui.table-row>
                        <x-ui.table-cell colspan="4" class="text-center text-muted-foreground h-16">Belum ada riwayat.</x-ui.table-cell>
                    </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </div>
    </x-ui.modal>
    @endforeach
</x-app-layout>
