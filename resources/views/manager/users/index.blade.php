<x-app-layout>
    <x-slot name="header">Manajemen User Marketing</x-slot>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
        <x-ui.button variant="tambah" onclick="modalTambahUser.showModal()" class="w-full sm:w-auto">+ Tambah User</x-ui.button>
        <form action="{{ route('manager.users.index') }}" method="GET" class="flex w-full sm:max-w-sm items-center space-x-2">
            <x-ui.input type="text" name="search" placeholder="Cari user..." value="{{ request('search') }}" />
            <x-ui.button variant="submit" type="submit">Cari</x-ui.button>
        </form>
    </div>

    <x-ui.card>
        <x-ui.card-content class="pt-6">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Nama</x-ui.table-head>
                        <x-ui.table-head>Email</x-ui.table-head>
                        <x-ui.table-head>Tanggal Dibuat</x-ui.table-head>
                        <x-ui.table-head class="text-right">Aksi</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($users as $user)
                        <x-ui.table-row>
                            <x-ui.table-cell class="font-medium">{{ $user->name }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $user->email }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $user->created_at->format('d M Y') }}</x-ui.table-cell>
                            <x-ui.table-cell class="flex flex-col items-end gap-2">
                                <x-ui.button variant="update" size="sm" onclick="modalEditUser{{ $user->id }}.showModal()">Update</x-ui.button>
                                <form action="{{ route('manager.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf @method('DELETE')
                                    <x-ui.button variant="destructive" size="sm" type="submit">Delete</x-ui.button>
                                </form>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="4" class="text-center text-muted-foreground h-24">Belum ada user marketing.</x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>

            @if($users->hasPages())
                <div class="mt-4">{{ $users->links() }}</div>
            @else
                <div class="mt-4 flex flex-col sm:flex-row items-center justify-between px-2 gap-4">
                    <div class="text-sm text-muted-foreground text-center sm:text-left">
                        Menampilkan 1 hingga {{ $users->count() }} dari {{ $users->total() }} hasil
                    </div>
                    <div class="flex items-center space-x-1">
                        <x-ui.button variant="paginasi" size="sm" disabled>&laquo;</x-ui.button>
                        <x-ui.button variant="paginasi" size="sm" class="hover:bg-primary hover:text-primary-foreground">1</x-ui.button>
                        <x-ui.button variant="paginasi" size="sm" disabled>&raquo;</x-ui.button>
                    </div>
                </div>
            @endif
        </x-ui.card-content>
    </x-ui.card>

    {{-- Modal Tambah --}}
    <x-ui.modal id="modalTambahUser" title="Tambah User Marketing">
        <form action="{{ route('manager.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-2">
                <x-ui.label for="name">Nama Lengkap</x-ui.label>
                <x-ui.input id="name" name="name" required />
            </div>
            <div class="space-y-2">
                <x-ui.label for="email">Email</x-ui.label>
                <x-ui.input id="email" type="email" name="email" required />
            </div>
            <div class="space-y-2">
                <x-ui.label for="password">Password</x-ui.label>
                <x-ui.input id="password" type="password" name="password" required minlength="8" />
            </div>
            <div class="flex justify-end pt-4">
                <x-ui.button type="submit">Simpan</x-ui.button>
            </div>
        </form>
    </x-ui.modal>

    {{-- Modal Edit --}}
    @foreach($users as $user)
        <x-ui.modal id="modalEditUser{{ $user->id }}" title="Edit User">
            <form action="{{ route('manager.users.update', $user->id) }}" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div class="space-y-2">
                    <x-ui.label>Nama Lengkap</x-ui.label>
                    <x-ui.input name="name" value="{{ $user->name }}" required />
                </div>
                <div class="space-y-2">
                    <x-ui.label>Email</x-ui.label>
                    <x-ui.input type="email" name="email" value="{{ $user->email }}" required />
                </div>
                <div class="space-y-2">
                    <x-ui.label>Password (Opsional)</x-ui.label>
                    <x-ui.input type="password" name="password" placeholder="Kosongkan jika tidak ingin diubah" minlength="8" />
                </div>
                <div class="flex justify-end pt-4">
                    <x-ui.button variant="update" type="submit">Update</x-ui.button>
                </div>
            </form>
        </x-ui.modal>
    @endforeach

</x-app-layout>
