<x-app-layout>
    <x-slot name="header">Laporan Marketing</x-slot>

    <div class="flex justify-end mb-4">
        <form action="{{ route('manager.laporan') }}" method="GET" class="flex w-full sm:max-w-sm items-center space-x-2">
            <x-ui.input type="text" name="search" placeholder="Cari marketing..." value="{{ request('search') }}" />
            <x-ui.button variant="search" type="submit">Cari</x-ui.button>
        </form>
    </div>

    <x-ui.card>
        <x-ui.card-content class="pt-6">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Marketing</x-ui.table-head>
                        <x-ui.table-head class="text-center">Data Prospek Customer</x-ui.table-head>
                        <x-ui.table-head class="text-center">Data Customer Aktif</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($marketings as $m)
                    <x-ui.table-row>
                        <x-ui.table-cell class="font-medium">{{ $m->name }}</x-ui.table-cell>
                        <x-ui.table-cell class="text-center">
                            <x-ui.button variant="history" size="sm" href="{{ route('manager.marketing.prospek', $m->id) }}">Lihat Detail Prospek</x-ui.button>
                        </x-ui.table-cell>
                        <x-ui.table-cell class="text-center">
                            <x-ui.button variant="history" size="sm" href="{{ route('manager.marketing.aktif', $m->id) }}">Lihat Detail Aktif</x-ui.button>
                        </x-ui.table-cell>
                    </x-ui.table-row>
                    @empty
                    <x-ui.table-row>
                        <x-ui.table-cell colspan="3" class="text-center text-muted-foreground h-24">Belum ada data marketing.</x-ui.table-cell>
                    </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
            @if($marketings->hasPages())
                <div class="mt-4">{{ $marketings->links() }}</div>
            @else
                <div class="mt-4 flex flex-col sm:flex-row items-center justify-between px-2 gap-4">
                    <div class="text-sm text-muted-foreground text-center sm:text-left">
                        Menampilkan 1 hingga {{ $marketings->count() }} dari {{ $marketings->total() }} hasil
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
</x-app-layout>
