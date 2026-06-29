<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\ActivityLog;

class FollowupController extends Controller
{
    public function store(Request $request, Customer $customer)
    {
        if ($customer->marketing_id !== auth()->id()) abort(403);

        $request->validate([
            'status' => 'required|in:Prospek Customer,Negosiasi,Customer Aktif',
            'jenis_interaksi' => 'required',
            'tanggal_interaksi' => 'required|date',
            'keterangan' => 'nullable|string',
            'jenis_dokumen' => 'nullable|string|required_with:file_dokumen',
            'file_dokumen' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'product_id' => 'nullable|exists:products,id',
            'whatsapp' => 'nullable|string|unique:customers,whatsapp,' . $customer->id,
            'lokasi' => 'nullable|string|max:255',
        ]);

        $followup = $customer->followups()->create([
            'user_id' => auth()->id(),
            'status_saat_itu' => $request->status,
            'jenis_interaksi' => $request->jenis_interaksi,
            'tanggal_interaksi' => $request->tanggal_interaksi,
            'keterangan' => $request->keterangan,
        ]);

        if ($request->hasFile('file_dokumen')) {
            $path = $request->file('file_dokumen')->store('documents', 'public');
            $customer->documents()->create([
                'followup_id' => $followup->id,
                'jenis_dokumen' => $request->jenis_dokumen,
                'file_path' => $path,
            ]);
        }

        // Update customer status, last followup date, and optionally other fields
        $updateData = [
            'status' => $request->status,
            'last_followup_at' => now(),
        ];
        
        if ($request->filled('product_id')) $updateData['product_id'] = $request->product_id;
        if ($request->filled('whatsapp')) $updateData['whatsapp'] = $request->whatsapp;
        if ($request->filled('lokasi')) $updateData['lokasi'] = $request->lokasi;

        $customer->update($updateData);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Tambah Followup Customer: ' . $customer->nama,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Follow up berhasil ditambahkan.');
    }
}
