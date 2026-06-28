<x-app-layout>
    <x-slot name="header">
        Performa Marketing: {{ $user->name }}
    </x-slot>

    <div class="mb-4">
        <a href="{{ route('manager.dashboard') }}" class="btn btn-sm btn-outline">← Kembali ke Dashboard</a>
    </div>

    <div class="card bg-base-100 shadow-sm glass-panel mb-8">
        <div class="card-body">
            <h2 class="card-title mb-4">Customer yang Ditangani</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Nama Customer</th>
                            <th>Status</th>
                            <th>Last Follow Up</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td>{{ $customer->nama }}</td>
                            <td>
                                <span class="badge {{ $customer->status == 'Customer Aktif' ? 'badge-success' : ($customer->status == 'Negosiasi' ? 'badge-accent' : 'badge-primary') }}">
                                    {{ $customer->status }}
                                </span>
                            </td>
                            <td>
                                @if($customer->last_followup_at)
                                    {{ $customer->last_followup_at->format('d M Y') }}
                                    <span class="text-xs text-neutral block">{{ $customer->last_followup_at->diffForHumans() }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('manager.customers.show', $customer->id) }}" class="btn btn-sm btn-info">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                        @if($customers->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center">Belum ada customer.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
