<x-app-layout>
    <x-slot name="header">Data Prospek Customer: {{ $user->name }}</x-slot>

    <div class="mb-4">
        <x-ui.button variant="outline" size="sm" href="{{ route('manager.laporan') }}">
            &larr; Kembali ke Laporan Marketing
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-content class="pt-6">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Nama Customer</x-ui.table-head>
                        <x-ui.table-head>Produk Diminati</x-ui.table-head>
                        <x-ui.table-head>Status</x-ui.table-head>
                        <x-ui.table-head class="text-right">History Follow Up</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($customers as $c)
                    <x-ui.table-row>
                        <x-ui.table-cell class="font-medium">{{ $c->nama }}</x-ui.table-cell>
                        <x-ui.table-cell>{{ $c->product->nama ?? '-' }}</x-ui.table-cell>
                        <x-ui.table-cell>
                            <x-ui.badge variant="{{ $c->status == 'Negosiasi' ? 'secondary' : 'default' }}">{{ $c->status }}</x-ui.badge>
                        </x-ui.table-cell>
                        <x-ui.table-cell class="text-right">
                            <x-ui.button variant="outline" size="sm" onclick="mProspek{{ $c->id }}.showModal()">Lihat History</x-ui.button>
                        </x-ui.table-cell>
                    </x-ui.table-row>
                    @empty
                    <x-ui.table-row>
                        <x-ui.table-cell colspan="4" class="text-center text-muted-foreground h-24">Belum ada data prospek.</x-ui.table-cell>
                    </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
    </x-ui.card>

    @foreach($customers as $c)
    <x-ui.modal id="mProspek{{ $c->id }}" title="Riwayat Follow Up">
        <p class="text-sm text-muted-foreground mb-4">Customer: <span class="font-semibold text-foreground">{{ $c->nama }}</span></p>
        <div class="max-h-[50vh] overflow-y-auto mb-4 border rounded-md">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Tanggal</x-ui.table-head>
                        <x-ui.table-head>Interaksi</x-ui.table-head>
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
                        <x-ui.table-cell colspan="3" class="text-center text-muted-foreground h-16">Belum ada riwayat.</x-ui.table-cell>
                    </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </div>
        
        @if($c->documents->isNotEmpty())
        <div class="flex justify-end pt-2">
            <x-ui.button href="{{ route('manager.customers.download-zip', $c->id) }}">Unduh Semua Dokumen (ZIP)</x-ui.button>
        </div>
        @endif
    </x-ui.modal>
    @endforeach
</x-app-layout>
