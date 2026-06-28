<x-app-layout>
    <x-slot name="header">
        Semua Customer (Manager)
    </x-slot>

    <div class="card bg-base-100 shadow-sm glass-panel">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Nama Customer</th>
                            <th>Marketing</th>
                            <th>Produk</th>
                            <th>Nomor WA</th>
                            <th>Status</th>
                            <th>Last Follow Up</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td class="font-semibold">{{ $customer->nama }}</td>
                            <td>{{ $customer->marketing->name }}</td>
                            <td>{{ $customer->product->nama ?? '-' }}</td>
                            <td>{{ $customer->whatsapp }}</td>
                            <td>
                                <span class="badge {{ $customer->status == 'Customer Aktif' ? 'badge-success' : ($customer->status == 'Negosiasi' ? 'badge-accent' : 'badge-primary') }}">
                                    {{ $customer->status }}
                                </span>
                            </td>
                            <td>
                                @if($customer->last_followup_at)
                                    {{ $customer->last_followup_at->diffForHumans() }}
                                @else
                                    <span class="text-error">Belum Pernah</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('manager.customers.show', $customer->id) }}" class="btn btn-sm btn-info">Detail / History</a>
                            </td>
                        </tr>
                        @endforeach
                        @if($customers->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data customer.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
