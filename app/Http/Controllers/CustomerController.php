<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ActivityLog;

class CustomerController extends Controller
{
    // ==============================
    // MARKETING: PROSPEK CUSTOMER
    // ==============================

    public function prospekIndex(Request $request)
    {
        $query = auth()->user()->customers()->with('product');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('whatsapp', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(10)->withQueryString();
        $products = Product::all();

        return view('customers.prospek_index', compact('customers', 'products'));
    }

    public function prospekStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
            'whatsapp' => 'required|string|unique:customers,whatsapp',
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        auth()->user()->customers()->create([
            'nama' => $request->nama,
            'product_id' => $request->product_id,
            'whatsapp' => $request->whatsapp,
            'lokasi' => $request->lokasi,
            'keterangan' => $request->keterangan,
            'status' => 'Prospek Customer',
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Tambah Prospek Customer: ' . $request->nama,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('prospek.index')->with('success', 'Prospek customer berhasil ditambahkan.');
    }

    public function prospekUpdate(Request $request, Customer $customer)
    {
        if ($customer->marketing_id !== auth()->id()) abort(403);

        $request->validate([
            'nama' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
            'whatsapp' => 'required|string|unique:customers,whatsapp,' . $customer->id,
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $customer->update($request->only(['nama', 'product_id', 'whatsapp', 'lokasi', 'keterangan']));

        ActivityLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Update Prospek Customer: ' . $customer->nama,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('prospek.index')->with('success', 'Prospek customer berhasil diupdate.');
    }

    public function prospekDestroy(Request $request, Customer $customer)
    {
        if ($customer->marketing_id !== auth()->id()) abort(403);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Hapus Customer: ' . $customer->nama,
            'ip_address' => $request->ip(),
        ]);

        $customer->delete();

        return redirect()->route('prospek.index')->with('success', 'Customer berhasil dihapus.');
    }

    // ==============================
    // MARKETING: STATUS PERJALANAN
    // ==============================

    public function statusPerjalanan()
    {
        $customers = auth()->user()->customers()
            ->with(['product', 'followups.documents'])
            ->whereIn('status', ['Prospek Customer', 'Negosiasi', 'Customer Aktif'])
            ->latest()
            ->paginate(10);
        $products = Product::all();

        return view('customers.status_perjalanan', compact('customers', 'products'));
    }

    // ==============================
    // MARKETING: CUSTOMER AKTIF
    // ==============================

    public function customerAktif()
    {
        $customers = auth()->user()->customers()
            ->where('status', 'Customer Aktif')
            ->with(['product', 'followups.documents', 'documents'])
            ->latest()
            ->paginate(10);

        return view('customers.customer_aktif', compact('customers'));
    }

    // ==============================
    // MANAGER: DRILL-DOWN PAGES
    // ==============================

    public function managerProspek(\App\Models\User $user)
    {
        $customers = $user->customers()
            ->whereIn('status', ['Prospek Customer', 'Negosiasi'])
            ->with(['product', 'followups.documents', 'documents'])
            ->get();
        return view('customers.manager_prospek', compact('customers', 'user'));
    }

    public function managerAktif(\App\Models\User $user)
    {
        $customers = $user->customers()
            ->where('status', 'Customer Aktif')
            ->with(['product', 'followups.documents', 'documents'])
            ->get();
        return view('customers.manager_aktif', compact('customers', 'user'));
    }
}
