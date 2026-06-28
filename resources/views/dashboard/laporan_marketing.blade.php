<x-app-layout>
    <x-slot name="header">Laporan Marketing</x-slot>

    <x-ui.card>
        <x-ui.card-content class="pt-6">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Marketing</x-ui.table-head>
                        <x-ui.table-head class="text-right">Data Prospek Customer</x-ui.table-head>
                        <x-ui.table-head class="text-right">Data Customer Aktif</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($marketings as $m)
                    <x-ui.table-row>
                        <x-ui.table-cell class="font-medium">{{ $m->name }}</x-ui.table-cell>
                        <x-ui.table-cell class="text-right">
                            <x-ui.button variant="outline" size="sm" href="{{ route('manager.marketing.prospek', $m->id) }}">Lihat Detail Prospek</x-ui.button>
                        </x-ui.table-cell>
                        <x-ui.table-cell class="text-right">
                            <x-ui.button variant="secondary" size="sm" href="{{ route('manager.marketing.aktif', $m->id) }}">Lihat Detail Aktif</x-ui.button>
                        </x-ui.table-cell>
                    </x-ui.table-row>
                    @empty
                    <x-ui.table-row>
                        <x-ui.table-cell colspan="3" class="text-center text-muted-foreground h-24">Belum ada data marketing.</x-ui.table-cell>
                    </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
    </x-ui.card>
</x-app-layout>
