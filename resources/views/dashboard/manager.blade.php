<x-app-layout>
    <x-slot name="header">Dashboard Manager</x-slot>

    {{-- Overview Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-ui.card>
            <x-ui.card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
                <x-ui.card-title class="text-sm font-medium">Total Sales</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="text-2xl font-bold">{{ $totalSales }}</div>
            </x-ui.card-content>
        </x-ui.card>
        <x-ui.card>
            <x-ui.card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
                <x-ui.card-title class="text-sm font-medium">Prospek Customer</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="text-2xl font-bold">{{ $totalProspek }}</div>
            </x-ui.card-content>
        </x-ui.card>
        <x-ui.card>
            <x-ui.card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
                <x-ui.card-title class="text-sm font-medium">Negosiasi</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="text-2xl font-bold">{{ $totalNegosiasi }}</div>
            </x-ui.card-content>
        </x-ui.card>
        <x-ui.card>
            <x-ui.card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
                <x-ui.card-title class="text-sm font-medium">Customer Aktif</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="text-2xl font-bold">{{ $totalCustomerAktif }}</div>
            </x-ui.card-content>
        </x-ui.card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Bar Chart: Prospek per Waktu --}}
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title class="text-lg">Kenaikan Jumlah Prospek Customer</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <x-ui.input type="date" id="startDate" value="{{ now()->subMonths(6)->format('Y-m-d') }}" class="w-auto h-8 text-xs" />
                    <span class="text-muted-foreground text-sm">-</span>
                    <x-ui.input type="date" id="endDate" value="{{ now()->format('Y-m-d') }}" class="w-auto h-8 text-xs" />
                    <x-ui.button id="btnFilter" size="sm" class="h-8">Filter</x-ui.button>
                </div>
                <canvas id="prospekChart"></canvas>
            </x-ui.card-content>
        </x-ui.card>

        {{-- Bar Chart: Top 5 Sales --}}
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title class="text-lg">Top 5 Sales dengan Kinerja Terbaik</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <canvas id="topSalesChart"></canvas>
                <p class="text-xs text-muted-foreground mt-4 text-center">Berdasarkan jumlah customer berstatus Aktif</p>
            </x-ui.card-content>
        </x-ui.card>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const topSales = @json($topSales);
        const topLabels = topSales.map(s => s.name);
        const topData = topSales.map(s => s.aktif_count);

        new Chart(document.getElementById('topSalesChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: topLabels,
                datasets: [{
                    label: 'Customer Aktif',
                    data: topData,
                    backgroundColor: ['#10b981', '#0ea5e9', '#f59e0b', '#8b5cf6', '#ec4899'],
                    borderRadius: 4
                }]
            },
            options: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });

        let prospekChart;
        function loadProspekChart() {
            const start = document.getElementById('startDate').value;
            const end = document.getElementById('endDate').value;
            fetch(`{{ route('manager.api.prospek-chart') }}?start=${start}&end=${end}`)
                .then(r => r.json())
                .then(data => {
                    const labels = data.map(d => d.bulan);
                    const values = data.map(d => d.total);
                    if (prospekChart) prospekChart.destroy();
                    prospekChart = new Chart(document.getElementById('prospekChart').getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Prospek Baru',
                                data: values,
                                backgroundColor: '#10b981',
                                borderRadius: 4
                            }]
                        },
                        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
                    });
                });
        }
        loadProspekChart();
        document.getElementById('btnFilter').addEventListener('click', loadProspekChart);
    });
    </script>
</x-app-layout>
