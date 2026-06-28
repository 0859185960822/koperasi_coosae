<x-app-layout>
    <x-slot name="header">
        Daftar Prospek Customer
    </x-slot>

    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-xl font-bold">Semua Customer Anda</h2>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">Tambah Prospek</a>
    </div>

    <div class="card bg-base-100 shadow-sm glass-panel">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Nama Customer</th>
                            <th>Produk</th>
                            <th>Nomor WA</th>
                            <th>Status</th>
                            <th>Last Follow Up (Hari)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        @php
                            $days = $customer->last_followup_at ? $customer->last_followup_at->diffInDays(now()) : 999;
                            $badgeClass = 'badge-success'; // 0-14 days
                            if ($days > 14 && $days <= 29) $badgeClass = 'badge-warning';
                            if ($days >= 30) $badgeClass = 'badge-error';
                        @endphp
                        <tr>
                            <td class="font-semibold">{{ $customer->nama }}</td>
                            <td>{{ $customer->product->nama ?? '-' }}</td>
                            <td>{{ $customer->whatsapp }}</td>
                            <td>
                                <span class="badge {{ $customer->status == 'Customer Aktif' ? 'badge-success' : ($customer->status == 'Negosiasi' ? 'badge-accent' : 'badge-primary') }}">
                                    {{ $customer->status }}
                                </span>
                            </td>
                            <td>
                                @if($customer->last_followup_at)
                                    <span class="badge {{ $badgeClass }}">{{ $days }} hari</span>
                                @else
                                    <span class="badge badge-error">Belum Pernah</span>
                                @endif
                            </td>
                            <td class="flex gap-2">
                                <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-sm btn-info">Detail</a>
                                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Hapus customer ini? (Soft delete)')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-error">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if($customers->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data customer.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
