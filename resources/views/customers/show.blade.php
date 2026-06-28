<x-app-layout>
    <x-slot name="header">
        Detail Customer: {{ $customer->nama }}
    </x-slot>

    <div class="mb-4">
        @if(auth()->user()->role === 'marketing')
            <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline">← Kembali</a>
        @else
            <a href="{{ route('manager.customers.index') }}" class="btn btn-sm btn-outline">← Kembali</a>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Info Customer & Form Update -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card bg-base-100 shadow-sm glass-panel">
                <div class="card-body">
                    <h2 class="card-title text-lg border-b pb-2">Informasi Customer</h2>
                    <p><strong>Nama:</strong> {{ $customer->nama }}</p>
                    <p><strong>WhatsApp:</strong> <a href="https://wa.me/{{ $customer->whatsapp }}" target="_blank" class="text-primary hover:underline">{{ $customer->whatsapp }}</a></p>
                    <p><strong>Lokasi:</strong> {{ $customer->lokasi }}</p>
                    <p><strong>Produk:</strong> {{ $customer->product->nama }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge {{ $customer->status == 'Customer Aktif' ? 'badge-success' : ($customer->status == 'Negosiasi' ? 'badge-accent' : 'badge-primary') }}">
                            {{ $customer->status }}
                        </span>
                    </p>
                    <p><strong>Dibuat:</strong> {{ $customer->created_at->format('d M Y') }}</p>
                    @if(auth()->user()->role === 'manager')
                        <p><strong>Marketing:</strong> {{ $customer->marketing->name }}</p>
                    @endif
                </div>
            </div>

            @if(auth()->user()->role === 'marketing')
            <div class="card bg-base-100 shadow-sm glass-panel">
                <div class="card-body">
                    <h2 class="card-title text-lg border-b pb-2">Update Status & Follow Up</h2>
                    <form action="{{ route('followups.store', $customer->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-control mb-2">
                            <label class="label"><span class="label-text">Status Customer</span></label>
                            <select name="status" class="select select-bordered w-full" required>
                                <option value="Prospek Customer" {{ $customer->status == 'Prospek Customer' ? 'selected' : '' }}>Prospek Customer</option>
                                <option value="Negosiasi" {{ $customer->status == 'Negosiasi' ? 'selected' : '' }}>Negosiasi</option>
                                <option value="Customer Aktif" {{ $customer->status == 'Customer Aktif' ? 'selected' : '' }}>Customer Aktif</option>
                            </select>
                        </div>

                        <div class="form-control mb-2">
                            <label class="label"><span class="label-text">Riwayat Interaksi</span></label>
                            <select name="jenis_interaksi" class="select select-bordered w-full" required>
                                <option value="Telepon">Telepon</option>
                                <option value="WhatsApp / Email">WhatsApp / Email</option>
                                <option value="Meeting">Meeting</option>
                                <option value="Presentasi Produk">Presentasi Produk</option>
                                <option value="Kunjungan Lapangan">Kunjungan Lapangan</option>
                            </select>
                        </div>

                        <div class="form-control mb-2">
                            <label class="label"><span class="label-text">Tanggal Interaksi</span></label>
                            <input type="date" name="tanggal_interaksi" class="input input-bordered w-full" required value="{{ date('Y-m-d') }}" />
                        </div>

                        <div class="form-control mb-2">
                            <label class="label"><span class="label-text">Catatan</span></label>
                            <textarea name="keterangan" class="textarea textarea-bordered w-full"></textarea>
                        </div>

                        <div class="form-control mb-2">
                            <label class="label"><span class="label-text">Upload Dokumen (Opsional)</span></label>
                            <select name="jenis_dokumen" class="select select-bordered w-full mb-2">
                                <option value="">Tanpa Dokumen</option>
                                <option value="Form Pendaftaran">Form Pendaftaran</option>
                                <option value="Form Kunjungan">Form Kunjungan</option>
                                <option value="Catatan Survey">Catatan Survey</option>
                                <option value="Proposal Kerjasama">Proposal Kerjasama</option>
                                <option value="Kontrak">Kontrak</option>
                                <option value="Dokumen Lainnya">Dokumen Lainnya</option>
                            </select>
                            <input type="file" name="file_dokumen" class="file-input file-input-bordered w-full" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
                        </div>

                        <button type="submit" class="btn btn-primary w-full mt-4">Simpan Follow Up</button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <!-- Kolom Kanan: Timeline & Dokumen -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card bg-base-100 shadow-sm glass-panel">
                <div class="card-body">
                    <h2 class="card-title text-lg border-b pb-2">Timeline Perjalanan Customer</h2>
                    <ul class="timeline timeline-vertical">
                        <li>
                            <div class="timeline-start">{{ $customer->created_at->format('d M Y') }}</div>
                            <div class="timeline-middle">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-primary"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                            </div>
                            <div class="timeline-end timeline-box">Customer ditambahkan sebagai {{ $customer->status }}</div>
                            <hr class="bg-primary"/>
                        </li>
                        
                        @foreach($customer->followups as $followup)
                        <li>
                            <hr class="bg-primary"/>
                            <div class="timeline-start">{{ $followup->tanggal_interaksi->format('d M Y') }}</div>
                            <div class="timeline-middle">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-primary"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                            </div>
                            <div class="timeline-end timeline-box">
                                <div class="font-bold">{{ $followup->jenis_interaksi }}</div>
                                <div class="text-sm opacity-80">Status: {{ $followup->status_saat_itu }}</div>
                                @if($followup->keterangan)
                                    <div class="text-sm mt-1">"{{ $followup->keterangan }}"</div>
                                @endif
                            </div>
                            <hr class="bg-primary"/>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm glass-panel">
                <div class="card-body">
                    <h2 class="card-title text-lg border-b pb-2">Daftar Dokumen</h2>
                    <div class="overflow-x-auto">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis Dokumen</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->documents as $doc)
                                <tr>
                                    <td>{{ $doc->created_at->format('d M Y') }}</td>
                                    <td>{{ $doc->jenis_dokumen }}</td>
                                    <td>
                                        <a href="{{ route('documents.download', $doc->id) }}" class="btn btn-xs btn-outline btn-primary" target="_blank">Download</a>
                                    </td>
                                </tr>
                                @endforeach
                                @if($customer->documents->isEmpty())
                                <tr>
                                    <td colspan="3" class="text-center text-sm opacity-60">Tidak ada dokumen</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
