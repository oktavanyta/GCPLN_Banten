<?php

namespace App\Http\Controllers;
use App\Models\Groundcheck;
use App\Models\Upi;
use App\Models\Up3;
use App\Models\Ulp;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        // Set default tanggal jika tidak ada di request
        $today = date('Y-m-d');
        $queryParams = $request->all();
        if (!$request->filled('tanggal_prabayar')) {
            $queryParams['tanggal_prabayar'] = $today;
        }
        if (!$request->filled('tanggal_pascabayar')) {
            $queryParams['tanggal_pascabayar'] = $today;
        }

        // Base query (biar tidak duplikat banyak)
        $baseQuery = Groundcheck::with(['ulp', 'ulp.up3', 'ulp.up3.upi']);

        // Filter UPI
        if ($request->filled('upi_id')) {
            $baseQuery->whereHas('ulp.up3.upi', function($q) use ($request) {
                $q->where('id', $request->upi_id);
            });
        }

        // Filter UP3
        if ($request->filled('up3_id')) {
            $baseQuery->whereHas('ulp.up3', function($q) use ($request) {
                $q->where('id', $request->up3_id);
            });
        }

        // Filter ULP
        if ($request->filled('ulp_id')) {
            $baseQuery->whereHas('ulp', function($q) use ($request) {
                $q->where('id', $request->ulp_id);
            });
        }

        // ========================
        // PRABAYAR
        // ========================
        $prabayarQuery = (clone $baseQuery)
            ->whereRaw('LOWER(jenis) = ?', ['prabayar']);

        if (!empty($queryParams['tanggal_prabayar'])) {
            $prabayarQuery->whereDate('groundchecks.created_at', $queryParams['tanggal_prabayar']);
        }

        // Tampilkan timestamp terakhir untuk prabayar
        $lastUpdatedPrabayar = (clone $prabayarQuery)->orderBy('updated_at', 'desc')->value('updated_at');
        $prabayar = $prabayarQuery
            ->join('ulps', 'groundchecks.ulp_id', '=', 'ulps.id')
            ->orderBy('groundchecks.updated_at')
            ->orderBy('ulps.kode')
            ->select('groundchecks.*')
            ->paginate(12, ['*'], 'page_prabayar')
            ->withQueryString();

        // ========================
        // PASCABAYAR
        // ========================
        $pascabayarQuery = (clone $baseQuery)
            ->whereRaw('LOWER(jenis) = ?', ['pascabayar']);

        if (!empty($queryParams['tanggal_pascabayar'])) {
            $pascabayarQuery->whereDate('groundchecks.created_at', $queryParams['tanggal_pascabayar']);
        }

        $pascabayar = $pascabayarQuery
            ->join('ulps', 'groundchecks.ulp_id', '=', 'ulps.id')
            ->orderBy('groundchecks.updated_at')
            ->orderBy('ulps.kode')
            ->select('groundchecks.*')
            ->paginate(12, ['*'], 'page_pascabayar')
            ->withQueryString();

        // ========================
        // FILTER BERJENJANG
        // ========================
        $upis = Upi::all();

        // UP3 tergantung UPI
        $up3s = Up3::when($request->upi_id, function ($q) use ($request) {
            $q->where('upi_id', $request->upi_id);
        })->get();

        // ULP tergantung UP3
        $ulps = Ulp::when($request->up3_id, function ($q) use ($request) {
            $q->where('up3_id', $request->up3_id);
        })->get();

        // ========================
        // CHART (tetap sama)
        // ========================
        $dateRange = [];
        $start = now()->subDays(14)->startOfDay();
        $end = now()->endOfDay();

        for ($date = $start->copy(); $date <= $end; $date->addDay()) {
            $dateRange[] = $date->format('Y-m-d');
        }

        $chartLabels = collect($dateRange)->map(fn($d) => date('d M', strtotime($d)));

        $chartPrabayar = collect($dateRange)->map(function($d) {
            return Groundcheck::whereDate('groundchecks.created_at', $d)
                ->whereRaw('LOWER(jenis) = ?', ['prabayar'])
                ->sum('submitted');
        });

        $chartPascabayar = collect($dateRange)->map(function($d) {
            return Groundcheck::whereDate('groundchecks.created_at', $d)
                ->whereRaw('LOWER(jenis) = ?', ['pascabayar'])
                ->sum('submitted');
        });

        $chartTotal = $chartPrabayar->zip($chartPascabayar)->map(function($pair) {
            return $pair[0] + $pair[1];
        });

        return view('dashboard', compact(
            'prabayar',
            'pascabayar',
            'upis',
            'up3s',
            'ulps',
            'chartLabels',
            'chartPrabayar',
            'chartPascabayar',
            'chartTotal'
        ));
    }
}
