<x-app-layout>
    <x-slot name="header">Dashboard Manager</x-slot>

    {{-- Overview Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-ui.card>
            <x-ui.card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
                <x-ui.card-title class="text-sm font-medium">Total Sales</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="text-2xl font-bold text-center">{{ $totalSales }}</div>
            </x-ui.card-content>
        </x-ui.card>
        <x-ui.card>
            <x-ui.card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
                <x-ui.card-title class="text-sm font-medium">Prospek Customer</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="text-2xl font-bold text-center">{{ $totalProspek }}</div>
            </x-ui.card-content>
        </x-ui.card>
        <x-ui.card>
            <x-ui.card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
                <x-ui.card-title class="text-sm font-medium">Negosiasi</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="text-2xl font-bold text-center">{{ $totalNegosiasi }}</div>
            </x-ui.card-content>
        </x-ui.card>
        <x-ui.card>
            <x-ui.card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
                <x-ui.card-title class="text-sm font-medium">Customer Aktif</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="text-2xl font-bold text-center">{{ $totalCustomerAktif }}</div>
            </x-ui.card-content>
        </x-ui.card>
    </div>

    {{-- Top 5 Sales (full-width, moved here from grid) --}}
    <x-ui.card class="mb-6">
        <x-ui.card-header>
            <x-ui.card-title class="text-lg">Top 5 Sales dengan Kinerja Terbaik</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            <canvas id="topSalesChart"></canvas>
            <p class="text-xs text-muted-foreground mt-4 text-center">Berdasarkan jumlah customer berstatus Aktif</p>
        </x-ui.card-content>
    </x-ui.card>

    {{-- Status Chart & Product Popularity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Line Chart: Status Seluruh Customer --}}
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title class="text-lg">Status Seluruh Customer</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 mb-4">
                    <div class="flex flex-1 items-center gap-2 w-full sm:w-auto">
                        <x-ui.input type="date" id="startDate" value="{{ now()->subMonths(6)->format('Y-m-d') }}" class="flex-1 w-full sm:w-auto h-8 text-xs" />
                        <span class="text-muted-foreground text-sm">-</span>
                        <x-ui.input type="date" id="endDate" value="{{ now()->format('Y-m-d') }}" class="flex-1 w-full sm:w-auto h-8 text-xs" />
                    </div>
                    <x-ui.button variant="submit" id="btnFilter" size="sm" class="h-8 w-full sm:w-auto">Filter</x-ui.button>
                </div>
                <canvas id="prospekChart"></canvas>
            </x-ui.card-content>
        </x-ui.card>

        {{-- Pie Chart: Produk Paling Diminati --}}
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title class="text-lg">Produk Paling Diminati</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="flex justify-center">
                    @if($productStats->isEmpty())
                        <div class="flex items-center justify-center h-48 text-muted-foreground italic">
                            belum ada data produk
                        </div>
                    @else
                        <div class="w-full max-w-xs">
                            <canvas id="productPieChart"></canvas>
                        </div>
                    @endif
                </div>
                @if($productStats->isNotEmpty())
                    <div class="mt-4 flex flex-wrap justify-center gap-x-4 gap-y-2">
                        @php
                            $productColors = ['#6366f1','#ec4899','#f59e0b','#10b981','#0ea5e9','#8b5cf6','#ef4444','#14b8a6','#f97316','#06b6d4'];
                        @endphp
                        @foreach($productStats as $index => $product)
                            <div class="flex items-center gap-1.5 text-xs">
                                <span class="inline-block w-3 h-3 rounded-full shrink-0" style="background-color: {{ $productColors[$index % count($productColors)] }};"></span>
                                <span class="text-foreground">{{ $product['nama'] }} ({{ $product['total'] }})</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-ui.card-content>
        </x-ui.card>
    </div>

    {{-- Map --}}
    <x-ui.card>
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
        // Top 5 Sales Chart
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

        // Product Pie Chart
        const productCanvas = document.getElementById('productPieChart');
        if (productCanvas) {
            const productColors = ['#6366f1','#ec4899','#f59e0b','#10b981','#0ea5e9','#8b5cf6','#ef4444','#14b8a6','#f97316','#06b6d4'];
            const productData = @json($productStats);
            const productCtx = productCanvas.getContext('2d');
            new Chart(productCtx, {
                type: 'pie',
                data: {
                    labels: productData.map(p => p.nama),
                    datasets: [{
                        data: productData.map(p => p.total),
                        backgroundColor: productData.map((_, i) => productColors[i % productColors.length]),
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        // Status Chart (Line)
        let statusChart;
        function loadStatusChart()
        {
            const start = document.getElementById('startDate').value;
            const end = document.getElementById('endDate').value;
            fetch(`{{ route('manager.api.prospek-chart') }}?start=${start}&end=${end}`)
                .then(r => r.json())
                .then(data => {
                    const labels = [...new Set(data.map(d => d.bulan))];
                    
                    const grouped = {};
                        data.forEach(item => {
                            if (!grouped[item.status]) {
                                grouped[item.status] = {};
                            }
                            grouped[item.status][item.bulan] = item.total;
                        });
                        const prospekData = labels.map(label =>
                            grouped["Prospek Customer"]?.[label] ?? 0
                        );
                        const negosiasiData = labels.map(label =>
                            grouped["Negosiasi"]?.[label] ?? 0
                        );
                        const aktifData = labels.map(label =>
                            grouped["Customer Aktif"]?.[label] ?? 0
                        );

                    if (statusChart) statusChart.destroy();
                    statusChart = new Chart(document.getElementById('prospekChart').getContext('2d'),
                    {
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
                        options: {responsive: true,interaction: {mode: 'index',intersect: false},plugins: {legend: {position: 'bottom'}},scales: {y: {beginAtZero: true,ticks: {stepSize: 1}}}}
                    });
                });
        }
        loadStatusChart();
        document.getElementById('btnFilter').addEventListener('click', loadStatusChart);

        // Map
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
