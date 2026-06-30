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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Pie Chart --}}
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
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
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
