<?php

namespace App\Http\Controllers;

use App\Services\ReportDataBuilder;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function report(Request $request, ReportDataBuilder $reportDataBuilder)
    {
        $report = $reportDataBuilder->build($request->input('month'));
        $report['generatedAt'] = now()->format('d M Y H:i');

        $pdf = PDF::loadView('pdf.report', $report)
            ->setPaper('a4', 'landscape');

        return $request->boolean('download')
            ? $pdf->download('laporan-keuangan.pdf')
            : $pdf->stream('laporan-keuangan.pdf');
    }
}
