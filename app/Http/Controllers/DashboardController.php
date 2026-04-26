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
                        ->where('jenis', 'Prabayar');

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
            ->where('jenis', 'Pascabayar');

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

        // Ambil UPI Banten
        $defaultUpi = Upi::where('nama', 'Banten')->first();

        // Set default jika belum dipilih
        if (!$request->filled('upi_id') && $defaultUpi) {
            $request->merge([
                'upi_id' => $defaultUpi->id
            ]);
        }

        // Filter UPI
        if ($request->filled('upi_id')) {
            $upis = Upi::all();
        } 

        // UP3 tergantung UPI
        $up3s = Up3::when($request->upi_id, function ($q) use ($request) {
            $q->where('upi_id', $request->upi_id);
        })->get();

        // ULP tergantung UP3
        $ulps = Ulp::when($request->up3_id, function ($q) use ($request) {
            $q->where('up3_id', $request->up3_id);
        })->get();

        $tab = $request->get('tab', 'prabayar');
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

        // Chart mengikuti filter UP3, ULP
        $chartPrabayarData = collect($dateRange)->map(function($d) use ($request, $tab) {

            $query = Groundcheck::query()
                ->whereBetween('created_at', [$d.' 00:00:00', $d.' 23:59:59'])
                ->where('jenis', 'Prabayar');

            // FILTER HANYA JIKA TAB PRABAYAR
            if ($tab === 'prabayar') {
                if ($request->filled('up3_id')) {
                    $query->whereHas('ulp.up3', fn($q) => $q->where('id', $request->up3_id));
                }
                if ($request->filled('ulp_id')) {
                    $query->whereHas('ulp', fn($q) => $q->where('id', $request->ulp_id));
                }
            }

            $result = $query->selectRaw('SUM(open) as o, SUM(submitted) as s, SUM(rejected) as r')->first();

            return [
                'open' => $result->o ?? 0,
                'submitted' => $result->s ?? 0,
                'rejected' => $result->r ?? 0,
            ];
        });

        $chartOpenPrabayar = $chartPrabayarData->pluck('open');
        $chartPrabayar = $chartPrabayarData->pluck('submitted');
        $chartRejectedPrabayar = $chartPrabayarData->pluck('rejected');

        $chartPascabayarData = collect($dateRange)->map(function($d) use ($request, $tab) {

            $query = Groundcheck::query()
                ->whereBetween('created_at', [$d.' 00:00:00', $d.' 23:59:59'])
                ->where('jenis', 'Pascabayar');

            // FILTER HANYA JIKA TAB PASCABAYAR
            if ($tab === 'pascabayar') {
                if ($request->filled('up3_id')) {
                    $query->whereHas('ulp.up3', fn($q) => $q->where('id', $request->up3_id));
                }
                if ($request->filled('ulp_id')) {
                    $query->whereHas('ulp', fn($q) => $q->where('id', $request->ulp_id));
                }
            }

            $result = $query->selectRaw('SUM(open) as o, SUM(submitted) as s, SUM(rejected) as r')->first();

            return [
                'open' => $result->o ?? 0,
                'submitted' => $result->s ?? 0,
                'rejected' => $result->r ?? 0,
            ];
        });

        $chartOpenPascabayar = $chartPascabayarData->pluck('open');
        $chartPascabayar = $chartPascabayarData->pluck('submitted');
        $chartRejectedPascabayar = $chartPascabayarData->pluck('rejected');

        $chartTotal = $chartPrabayar->zip($chartPascabayar)->map(function($pair) {
            return $pair[0] + $pair[1];
        });

        $isFiltered = $request->filled('up3_id') || $request->filled('ulp_id');
        $selectedUp3 = null;
        $selectedUlp = null;

        if ($request->filled('up3_id')) {
            $selectedUp3 = \App\Models\Up3::find($request->up3_id)?->nama;
        }

        if ($request->filled('ulp_id')) {
            $selectedUlp = \App\Models\Ulp::find($request->ulp_id)?->nama;
        }
        return view('dashboard', compact(
            'prabayar',
            'pascabayar',
            'upis',
            'up3s',
            'ulps',
            'chartLabels',
            'chartPrabayar',
            'chartPascabayar',
            'chartRejectedPrabayar',
            'chartRejectedPascabayar',
            'chartTotal',
            'chartOpenPrabayar',
            'chartOpenPascabayar',
            'isFiltered',
            'tab',
            'selectedUp3',
            'selectedUlp',
        ));
    }
}
