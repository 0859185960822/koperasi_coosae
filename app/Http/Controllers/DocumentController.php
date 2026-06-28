<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;

class DocumentController extends Controller
{
    public function download(\App\Models\Document $document)
    {
        if (auth()->user()->role === 'marketing' && $document->customer->marketing_id !== auth()->id()) {
            abort(403);
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Download Dokumen: ' . $document->jenis_dokumen . ' (Customer: ' . $document->customer->nama . ')',
            'ip_address' => request()->ip(),
        ]);

        return Storage::disk('public')->download($document->file_path);
    }

    public function downloadZip(\App\Models\Customer $customer)
    {
        if (auth()->user()->role === 'marketing' && $customer->marketing_id !== auth()->id()) {
            abort(403);
        }

        $documents = $customer->documents;
        
        if ($documents->isEmpty()) {
            return back()->with('error', 'Tidak ada dokumen untuk diunduh.');
        }

        $zip = new \ZipArchive();
        $zipFileName = 'Dokumen_' . str_replace(' ', '_', $customer->nama) . '_' . time() . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($documents as $doc) {
                $filePath = storage_path('app/public/' . $doc->file_path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($filePath));
                }
            }
            $zip->close();
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Download ZIP Dokumen Customer: ' . $customer->nama,
            'ip_address' => request()->ip(),
        ]);

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
