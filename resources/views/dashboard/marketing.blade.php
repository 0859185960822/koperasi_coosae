<x-app-layout>
    <x-slot name="header">Dashboard Marketing</x-slot>

    {{-- Overview Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
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

    {{-- Status Comparison & Product Popularity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Pie Chart: Status Customer --}}
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title class="text-lg">Perbandingan Status Customer</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="flex justify-center">
                    @if($totalProspek == 0 && $totalNegosiasi == 0 && $totalCustomerAktif == 0)
                        <div class="flex items-center justify-center h-48 text-muted-foreground italic">
                            belum ada data customer
                        </div>
                    @else
                        <div class="w-full max-w-xs">
                            <canvas id="pieChart"></canvas>
                        </div>
                    @endif
                </div>
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
            </x-ui.card-content>
        </x-ui.card>
    </div>

    {{-- Follow-up Progress (30 Hari) --}}
    <x-ui.card class="mb-6">
        <x-ui.card-header>
            <x-ui.card-title class="text-lg">Progress Follow-up Customer (30 Hari Terakhir)</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            @if($totalCustomers == 0)
                <div class="flex items-center justify-center h-20 text-muted-foreground italic">
                    belum ada data customer
                </div>
            @else
                @php
                    $percentage = $totalCustomers > 0 ? round(($followedUp / $totalCustomers) * 100) : 0;
                @endphp
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-muted-foreground">{{ $followedUp }} dari {{ $totalCustomers }} customer sudah di-follow up</span>
                        <span class="font-semibold text-foreground">{{ $percentage }}%</span>
                    </div>
                    <div class="w-full bg-muted rounded-full h-4 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 ease-out" style="width: {{ $percentage }}%; background: linear-gradient(90deg, #10b981, #059669);"></div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-3 h-3 rounded-full shrink-0" style="background: linear-gradient(90deg, #10b981, #059669);"></span>
                            <span class="text-foreground">Sudah dihubungi: <strong>{{ $followedUp }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-3 h-3 rounded-full bg-muted border border-border shrink-0"></span>
                            <span class="text-foreground">Belum dihubungi: <strong>{{ $notFollowedUp }}</strong></span>
                        </div>
                    </div>
                    <p class="text-xs text-muted-foreground">* Data di-reset otomatis setiap 30 hari berdasarkan tanggal follow-up terakhir.</p>
                </div>
            @endif
        </x-ui.card-content>
    </x-ui.card>

    {{-- Map --}}
    <x-ui.card>
        <x-ui.card-header>
            <x-ui.card-title class="text-lg">Sebaran Customer berdasarkan Wilayah</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            <div id="customerMap" class="w-full rounded-md border" style="height: 320px;"></div>
            <p class="text-xs text-muted-foreground mt-2">* Lokasi ditampilkan berdasarkan data kota/wilayah customer.</p>
        </x-ui.card-content>
    </x-ui.card>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Status Pie Chart
        const pieCanvas = document.getElementById('pieChart');
        if (pieCanvas) {
            const ctx = pieCanvas.getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Prospek Customer', 'Negosiasi', 'Customer Aktif'],
                    datasets: [{
                        data: [{{ $totalProspek }}, {{ $totalNegosiasi }}, {{ $totalCustomerAktif }}],
                        backgroundColor: ['#0ea5e9', '#f59e0b', '#10b981'],
                        borderWidth: 0,
                    }]
                },
                options: { responsive: true }
            });
        }

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
                options: {responsive: true}
            });
        }

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
