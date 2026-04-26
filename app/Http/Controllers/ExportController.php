<?php

namespace App\Http\Controllers;

use App\Models\Groundcheck;
use Illuminate\Http\Request;
use App\Exports\PrabayarExport;
use App\Exports\PascabayarExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function prabayar(Request $request)
    {
        $query = Groundcheck::with(['ulp', 'ulp.up3', 'ulp.up3.upi'])
            ->whereRaw('LOWER(jenis) = ?', ['prabayar']);

        if ($request->filled('tanggal_prabayar')) {
            $query->whereDate('groundchecks.created_at', $request->tanggal_prabayar);
        }

        if ($request->filled('upi_id')) {
            $query->whereHas('ulp.up3.upi', fn($q) => $q->where('id', $request->upi_id));
        }

        if ($request->filled('up3_id')) {
            $query->whereHas('ulp.up3', fn($q) => $q->where('id', $request->up3_id));
        }

        if ($request->filled('ulp_id')) {
            $query->whereHas('ulp', fn($q) => $q->where('id', $request->ulp_id));
        }

        $data = $query->get();

        // ambil tanggal filter (default hari ini)
        $tanggal = $request->tanggal_prabayar ?? date('Y-m-d');

        return Excel::download(
            new PrabayarExport($data, $tanggal),
            'Monitoring_Prabayar_' . $tanggal . '.xlsx'
        );
    }

    public function pascabayar(Request $request)
    {
        $query = Groundcheck::with(['ulp', 'ulp.up3', 'ulp.up3.upi'])
            ->whereRaw('LOWER(jenis) = ?', ['pascabayar']);

        if ($request->filled('tanggal_pascabayar')) {
            $query->whereDate('groundchecks.created_at', $request->tanggal_pascabayar);
        }

        if ($request->filled('upi_id')) {
            $query->whereHas('ulp.up3.upi', fn($q) => $q->where('id', $request->upi_id));
        }

        if ($request->filled('up3_id')) {
            $query->whereHas('ulp.up3', fn($q) => $q->where('id', $request->up3_id));
        }

        if ($request->filled('ulp_id')) {
            $query->whereHas('ulp', fn($q) => $q->where('id', $request->ulp_id));
        }

        $data = $query->get();

        $tanggal = $request->tanggal_pascabayar ?? date('Y-m-d');

        return Excel::download(
            new PascabayarExport($data, $tanggal),
            'Monitoring_Pascabayar_' . $tanggal . '.xlsx'
        );
    }
}
