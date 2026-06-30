<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function marketing()
    {
        $user = auth()->user();

        $totalProspek = $user->customers()->where('status', 'Prospek Customer')->count();
        $totalNegosiasi = $user->customers()->where('status', 'Negosiasi')->count();
        $totalCustomerAktif = $user->customers()->where('status', 'Customer Aktif')->count();

        // Data for map (sebaran customer berdasarkan lokasi)
        $customers = $user->customers()->select('nama', 'lokasi', 'status')->get();

        return view('dashboard.marketing', compact(
            'totalProspek', 'totalNegosiasi', 'totalCustomerAktif', 'customers'
        ));
    }

    public function manager()
    {
        $totalSales = User::where('role', 'marketing')->count();
        $totalProspek = Customer::where('status', 'Prospek Customer')->count();
        $totalNegosiasi = Customer::where('status', 'Negosiasi')->count();
        $totalCustomerAktif = Customer::where('status', 'Customer Aktif')->count();

        // Top 5 sales by active customers
        $topSales = User::where('role', 'marketing')
            ->withCount(['customers as aktif_count' => function ($q) {
                $q->where('status', 'Customer Aktif');
            }])
            ->orderByDesc('aktif_count')
            ->limit(5)
            ->get();

        // Data for map (sebaran customer keseluruhan)
        $customers = Customer::select('nama', 'lokasi', 'status')->get();

        return view('dashboard.manager', compact(
            'totalSales', 'totalProspek', 'totalNegosiasi', 'totalCustomerAktif', 'topSales', 'customers'
        ));
    }

    // public function prospekChartData(Request $request)
    // {
    //     $startDate = $request->get('start', Carbon::now()->subMonths(6)->format('Y-m-d'));
    //     $endDate = $request->get('end', Carbon::now()->format('Y-m-d'));

    //     $dbDriver = DB::connection()->getDriverName();
    //     $dateExpression = $dbDriver === 'sqlite' 
    //         ? "strftime('%Y-%m', created_at)" 
    //         : "DATE_FORMAT(created_at, '%Y-%m')";

    //     $data = Customer::select(
    //             DB::raw("$dateExpression as bulan"),
    //             'status',
    //             DB::raw('COUNT(*) as total')
    //         )
    //         ->whereBetween('created_at', [$startDate, $endDate])
    //         ->whereIn('status', ['Prospek Customer', 'Negosiasi', 'Customer Aktif'])
    //         ->groupBy('bulan', 'status')
    //         ->orderBy('bulan')
    //         ->get();

    //     return response()->json($data);
    // }

    public function prospekChartData(Request $request)
    {
        $startDate = Carbon::parse(
            $request->get('start', now()->subMonths(6))
        )->startOfDay();

        $endDate = Carbon::parse(
            $request->get('end', now())
        )->endOfDay();

        $dbDriver = DB::connection()->getDriverName();

        $dateExpression = $dbDriver === 'sqlite'
            ? "strftime('%Y-%m', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m')";

        $data = Customer::select(
                DB::raw("$dateExpression as bulan"),
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', [
                'Prospek Customer',
                'Negosiasi',
                'Customer Aktif'
            ])
            ->groupBy('bulan', 'status')
            ->orderBy('bulan')
            ->get();

        return response()->json($data);
    }

    public function laporanMarketing(Request $request)
    {
        $query = User::where('role', 'marketing');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        $marketings = $query->paginate(10)->withQueryString();
        return view('dashboard.laporan_marketing', compact('marketings'));
    }
}
