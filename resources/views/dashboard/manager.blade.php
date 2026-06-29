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
                <x-ui.card-title class="text-lg">Status Seluruh Customer</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 mb-4">
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <x-ui.input type="date" id="startDate" value="{{ now()->subMonths(6)->format('Y-m-d') }}" class="w-full sm:w-auto h-8 text-xs" />
                        <span class="text-muted-foreground text-sm">-</span>
                        <x-ui.input type="date" id="endDate" value="{{ now()->format('Y-m-d') }}" class="w-full sm:w-auto h-8 text-xs" />
                    </div>
                    <x-ui.button id="btnFilter" size="sm" class="h-8 w-full sm:w-auto">Filter</x-ui.button>
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

    {{-- Map --}}
    <x-ui.card class="mt-6">
        <x-ui.card-header>
            <x-ui.card-title class="text-lg">Sebaran Seluruh Customer berdasarkan Wilayah</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            <div id="customerMap" class="w-full rounded-md border" style="height: 320px;"></div>
            <p class="text-xs text-muted-foreground mt-2">* Lokasi ditampilkan berdasarkan data kota/wilayah customer.</p>
        </x-ui.card-content>
    </x-ui.card>

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
                    // Extract unique labels (bulan)
                    const labels = [...new Set(data.map(d => d.bulan))].sort();
                    
                    const prospekData = labels.map(label => {
                        const row = data.find(d => d.bulan === label && d.status === 'Prospek Customer');
                        return row ? row.total : 0;
                    });
                    const negosiasiData = labels.map(label => {
                        const row = data.find(d => d.bulan === label && d.status === 'Negosiasi');
                        return row ? row.total : 0;
                    });
                    const aktifData = labels.map(label => {
                        const row = data.find(d => d.bulan === label && d.status === 'Customer Aktif');
                        return row ? row.total : 0;
                    });

                    if (prospekChart) prospekChart.destroy();
                    prospekChart = new Chart(document.getElementById('prospekChart').getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Prospek Customer',
                                    data: prospekData,
                                    borderColor: '#0ea5e9',
                                    backgroundColor: '#0ea5e9',
                                    tension: 0.3
                                },
                                {
                                    label: 'Negosiasi',
                                    data: negosiasiData,
                                    borderColor: '#f59e0b',
                                    backgroundColor: '#f59e0b',
                                    tension: 0.3
                                },
                                {
                                    label: 'Customer Aktif',
                                    data: aktifData,
                                    borderColor: '#10b981',
                                    backgroundColor: '#10b981',
                                    tension: 0.3
                                }
                            ]
                        },
                        options: { responsive: true, plugins: { legend: { display: true, position: 'bottom' } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
                    });
                });
        }
        loadProspekChart();
        document.getElementById('btnFilter').addEventListener('click', loadProspekChart);

        const map = L.map('customerMap').setView([-2.5, 118], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        const customers = @json($customers);
        const locationCounts = {};
        customers.forEach(c => {
            const key = c.lokasi.toLowerCase().trim();
            if (!locationCounts[key]) locationCounts[key] = { lokasi: c.lokasi, count: 0, names: [] };
            locationCounts[key].count++;
            locationCounts[key].names.push(c.nama);
        });

        Object.values(locationCounts).forEach(loc => {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(loc.lokasi + ', Indonesia')}&limit=1`)
                .then(r => r.json())
                .then(data => {
                    if (data.length > 0) {
                        const marker = L.marker([data[0].lat, data[0].lon]).addTo(map);
                        marker.bindPopup(`<b>${loc.lokasi}</b><br>${loc.count} customer<br><small>${loc.names.join(', ')}</small>`);
                    }
                }).catch(() => {});
        });
    });
    </script>
</x-app-layout>
