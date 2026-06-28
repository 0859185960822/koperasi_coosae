<x-app-layout>
    <x-slot name="header">
        Data Customer Dihapus (Soft Delete)
    </x-slot>

    <div class="mb-4">
        <a href="{{ route('manager.dashboard') }}" class="btn btn-sm btn-outline">← Kembali ke Dashboard</a>
    </div>

    <div class="card bg-base-100 shadow-sm glass-panel border-error border">
        <div class="card-body">
            <h2 class="card-title text-error mb-4">Tong Sampah (Read Only)</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Nama Customer</th>
                            <th>Marketing</th>
                            <th>Produk</th>
                            <th>Status Terakhir</th>
                            <th>Tanggal Dihapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td class="font-semibold">{{ $customer->nama }}</td>
                            <td>{{ $customer->marketing->name }}</td>
                            <td>{{ $customer->product->nama ?? '-' }}</td>
                            <td>{{ $customer->status }}</td>
                            <td class="text-error font-bold">{{ $customer->deleted_at->format('d M Y H:i') }}</td>
                        </tr>
                        @endforeach
                        @if($customers->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data yang dihapus.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
