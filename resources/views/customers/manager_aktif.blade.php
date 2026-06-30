<x-app-layout>
    <x-slot name="header">Data Customer Aktif: {{ $user->name }}</x-slot>

    <div class="mb-4">
        <x-ui.button variant="paginasi" size="sm" href="{{ route('manager.laporan') }}">
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
                        <x-ui.table-head class="text-right">Download Dokumen</x-ui.table-head>
                        <x-ui.table-head class="text-right">History</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($customers as $c)
                    <x-ui.table-row>
                        <x-ui.table-cell class="font-medium">{{ $c->nama }}</x-ui.table-cell>
                        <x-ui.table-cell>{{ $c->product->nama ?? '-' }}</x-ui.table-cell>
                        <x-ui.table-cell class="text-right">
                            @if($c->documents->isNotEmpty())
                            <x-ui.button variant="secondary" size="sm" onclick="mDocs{{ $c->id }}.showModal()">Dokumen ({{ $c->documents->count() }})</x-ui.button>
                            @else
                            <span class="text-xs text-muted-foreground">Tidak ada</span>
                            @endif
                        </x-ui.table-cell>
                        <x-ui.table-cell class="text-right">
                            <x-ui.button variant="history" size="sm" onclick="mHist{{ $c->id }}.showModal()">History</x-ui.button>
                        </x-ui.table-cell>
                    </x-ui.table-row>
                    @empty
                    <x-ui.table-row>
                        <x-ui.table-cell colspan="4" class="text-center text-muted-foreground h-24">Belum ada customer aktif.</x-ui.table-cell>
                    </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
    </x-ui.card>

    @foreach($customers as $c)
    {{-- Modal Dokumen --}}
    <x-ui.modal id="mDocs{{ $c->id }}" title="Dokumen Customer">
        <p class="text-sm text-muted-foreground mb-4">Customer: <span class="font-semibold text-foreground">{{ $c->nama }}</span></p>
        <ul class="list-disc list-inside space-y-2 mb-6">
            @foreach($c->documents as $doc)
            <li class="text-sm">{{ $doc->jenis_dokumen }} <span class="text-xs text-muted-foreground">({{ $doc->created_at->format('d M Y') }})</span></li>
            @endforeach
        </ul>
        <div class="flex justify-end pt-2">
            <x-ui.button href="{{ route('manager.customers.download-zip', $c->id) }}">Unduh Semua (ZIP)</x-ui.button>
        </div>
    </x-ui.modal>

    {{-- Modal History --}}
    <x-ui.modal id="mHist{{ $c->id }}" title="Riwayat Follow Up">
        <p class="text-sm text-muted-foreground mb-4">Customer: <span class="font-semibold text-foreground">{{ $c->nama }}</span></p>
        <div class="max-h-[50vh] overflow-y-auto border rounded-md">
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
                        <x-ui.table-cell>{{ $fu->jenis_interaksi }}</x-ui.table-cell>
                        <x-ui.table-cell>
                            <!-- <x-ui.badge variant="outline">{{ $fu->status_saat_itu }}</x-ui.badge> -->
                            <x-ui.badge
                                variant="{{ match($fu->status_saat_itu) {
                                    'Customer Aktif' => 'aktif',
                                    'Negosiasi' => 'negosiasi',
                                    'Prospek Customer' => 'prospek',
                                    default => 'default',
                                } }}">
                                {{ $fu->status_saat_itu }}
                            </x-ui.badge>
                            <x-ui.table-cell>
                                @forelse($fu->documents as $doc)
                                    <div class="text-xs">{{ $doc->jenis_dokumen }}</div>
                                @empty
                                    <span class="text-muted-foreground">-</span>
                                @endforelse
                            </x-ui.table-cell>
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
    </x-ui.modal>
    @endforeach
</x-app-layout>
