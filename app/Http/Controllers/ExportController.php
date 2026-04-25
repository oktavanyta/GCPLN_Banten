<?php

namespace App\Http\Controllers;

use App\Models\Groundcheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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
            $query->whereHas('ulp.up3.upi', function($q) use ($request) {
                $q->where('id', $request->upi_id);
            });
        }
        if ($request->filled('up3_id')) {
            $query->whereHas('ulp.up3', function($q) use ($request) {
                $q->where('id', $request->up3_id);
            });
        }
        if ($request->filled('ulp_id')) {
            $query->whereHas('ulp', function($q) use ($request) {
                $q->where('id', $request->ulp_id);
            });
        }

        $data = $query->get();

        $filename = 'prabayar_export_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ];

        $callback = function() use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Timestamp', 'UPI', 'UP3', 'ULP', 'Open', 'Submitted', 'Rejected', 'Total']);
            foreach ($data as $row) {
                fputcsv($handle, [
                    $row->created_at,
                    optional(optional(optional($row->ulp)->up3)->upi)->nama ?? '-',
                    optional(optional($row->ulp)->up3)->nama ?? '-',
                    optional($row->ulp)->nama ?? '-',
                    $row->open,
                    $row->submitted,
                    $row->rejected,
                    $row->open + $row->submitted + $row->rejected
                ]);
            }
            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function pascabayar(Request $request)
    {
        $query = Groundcheck::with(['ulp', 'ulp.up3', 'ulp.up3.upi'])
            ->whereRaw('LOWER(jenis) = ?', ['pascabayar']);

        if ($request->filled('tanggal_pascabayar')) {
            $query->whereDate('groundchecks.created_at', $request->tanggal_pascabayar);
        }
        if ($request->filled('upi_id')) {
            $query->whereHas('ulp.up3.upi', function($q) use ($request) {
                $q->where('id', $request->upi_id);
            });
        }
        if ($request->filled('up3_id')) {
            $query->whereHas('ulp.up3', function($q) use ($request) {
                $q->where('id', $request->up3_id);
            });
        }
        if ($request->filled('ulp_id')) {
            $query->whereHas('ulp', function($q) use ($request) {
                $q->where('id', $request->ulp_id);
            });
        }

        $data = $query->get();

        $filename = 'pascabayar_export_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ];

        $callback = function() use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Timestamp', 'UPI', 'UP3', 'ULP', 'Open', 'Submitted', 'Rejected', 'Total']);
            foreach ($data as $row) {
                fputcsv($handle, [
                    $row->created_at,
                    optional(optional(optional($row->ulp)->up3)->upi)->nama ?? '-',
                    optional(optional($row->ulp)->up3)->nama ?? '-',
                    optional($row->ulp)->nama ?? '-',
                    $row->open,
                    $row->submitted,
                    $row->rejected,
                    $row->open + $row->submitted + $row->rejected
                ]);
            }
            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }
}
