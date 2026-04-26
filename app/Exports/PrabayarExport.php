<?php

namespace App\Exports;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PrabayarExport implements FromView, WithEvents, ShouldAutoSize
{
    public $data;
    public $tanggal;
    public $timestamp;

    public function __construct($data, $tanggal)
    {
        $this->data = $data;
        $this->tanggal = $tanggal;

        $lastTimestamp = collect($data)->max('created_at');

        $this->timestamp = $lastTimestamp
            ? Carbon::parse($lastTimestamp)->format('d M Y H:i:s')
            : '-';
    }

    public function view(): View
    {
        return view('exports.prabayar', [
            'data' => $this->data,
            'tanggal' => $this->tanggal,
            'timestamp' => $this->timestamp
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // 🔹 Freeze
                $sheet->freezePane('A5');

                // 🔹 Bold header
                $sheet->getStyle('A4:H4')->getFont()->setBold(true);

                // 🔹 Center header
                $sheet->getStyle('A4:H4')->getAlignment()->setHorizontal('center');

                $highestRow = $sheet->getHighestRow();

                // 🔥 Tambah baris TOTAL
                $totalRow = $highestRow + 1;

                $sheet->setCellValue("A{$totalRow}", 'TOTAL');

                // Merge label TOTAL
                $sheet->mergeCells("A{$totalRow}:D{$totalRow}");

                // Rumus SUM
                $sheet->setCellValue("E{$totalRow}", "=SUM(E5:E{$highestRow})");
                $sheet->setCellValue("F{$totalRow}", "=SUM(F5:F{$highestRow})");
                $sheet->setCellValue("G{$totalRow}", "=SUM(G5:G{$highestRow})");
                $sheet->setCellValue("H{$totalRow}", "=SUM(H5:H{$highestRow})");

                // 🔹 Bold baris total
                $sheet->getStyle("A{$totalRow}:H{$totalRow}")->getFont()->setBold(true);

                // 🔹 Center angka total
                $sheet->getStyle("E{$totalRow}:H{$totalRow}")
                    ->getAlignment()->setHorizontal('center');

                // 🔹 Border termasuk total
                $sheet->getStyle("A4:H{$totalRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // 🔹 Merge judul
                $sheet->mergeCells('A1:H1');
                $sheet->mergeCells('A2:H2');

                $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal('center');
            }
        ];
    }
}