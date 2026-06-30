<x-app-layout>
    <x-slot name="header">Customer Aktif</x-slot>

    <div class="flex justify-end mb-4">
        <form action="{{ route('aktif.index') }}" method="GET" class="flex w-full sm:max-w-sm items-center space-x-2">
            <x-ui.input type="text" name="search" placeholder="Cari customer..." value="{{ request('search') }}" />
            <x-ui.button variant="search" type="submit">Cari</x-ui.button>
        </form>
    </div>

    <x-ui.card>
        <x-ui.card-content class="pt-6">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Nama Customer</x-ui.table-head>
                        <x-ui.table-head>Produk Diminati</x-ui.table-head>
                        <x-ui.table-head class="text-center">Download Dokumen</x-ui.table-head>
                        <x-ui.table-head class="text-center">History</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($customers as $c)
                    <x-ui.table-row>
                        <x-ui.table-cell class="font-medium">{{ $c->nama }}</x-ui.table-cell>
                        <x-ui.table-cell>{{ $c->product->nama ?? '-' }}</x-ui.table-cell>
                        <x-ui.table-cell class="text-center">
                            @if($c->documents->isNotEmpty())
                            <x-ui.button variant="secondary" size="sm" onclick="modalDocs{{ $c->id }}.showModal()">Dokumen ({{ $c->documents->count() }})</x-ui.button>
                            @else
                            <span class="text-xs text-muted-foreground">Tidak ada</span>
                            @endif
                        </x-ui.table-cell>
                        <x-ui.table-cell class="text-center">
                            <x-ui.button variant="history" size="sm" onclick="modalHistAktif{{ $c->id }}.showModal()">History</x-ui.button>
                        </x-ui.table-cell>
                    </x-ui.table-row>
                    @empty
                    <x-ui.table-row>
                        <x-ui.table-cell colspan="4" class="text-center text-muted-foreground h-24">Belum ada customer aktif.</x-ui.table-cell>
                    </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
            @if($customers->hasPages())
                <div class="mt-4">{{ $customers->links() }}</div>
            @else
                <div class="mt-4 flex flex-col sm:flex-row items-center justify-between px-2 gap-4">
                    <div class="text-sm text-muted-foreground text-center sm:text-left">
                        Menampilkan 1 hingga {{ $customers->count() }} dari {{ $customers->total() }} hasil
                    </div>
                    <div class="flex items-center space-x-1">
                        <x-ui.button variant="paginasi" size="sm" disabled>&laquo;</x-ui.button>
                        <x-ui.button variant="paginasi" size="sm" class="hover:bg-primary hover:text-primary-foreground">1</x-ui.button>
                        <x-ui.button variant="paginasi" size="sm" disabled>&raquo;</x-ui.button>
                    </div>
                </div>
            @endif
        </x-ui.card-content>
    </x-ui.card>

    @foreach($customers as $c)
    <x-ui.modal id="modalDocs{{ $c->id }}" title="Dokumen Customer">
        <p class="text-sm text-muted-foreground mb-4">Customer: <span class="font-semibold text-foreground">{{ $c->nama }}</span></p>
        <ul class="list-disc list-inside space-y-2 mb-6">
            @foreach($c->documents as $doc)
            <li class="text-sm">{{ $doc->jenis_dokumen }} <span class="text-xs text-muted-foreground">({{ $doc->created_at->format('d M Y') }})</span></li>
            @endforeach
        </ul>
        <div class="flex justify-end">
            <x-ui.button href="{{ route('customers.download-zip', $c->id) }}">Unduh Semua (ZIP)</x-ui.button>
        </div>
    </x-ui.modal>

    <x-ui.modal id="modalHistAktif{{ $c->id }}" title="Log Aktivitas">
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
    </x-ui.modal>
    @endforeach
</x-app-layout>
