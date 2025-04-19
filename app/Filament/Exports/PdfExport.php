<?php

namespace App\Filament\Exports;

use App\Models\Action;
use Spatie\LaravelPdf\Facades\Pdf;

class PdfExport
{
    public static function exportAcion(Action $action)
    {
        Pdf::html(view('pdf.action', [
            'invoiceNumber' => '1234',
            'customerName' => 'Grumpy Cat',

        ]))
            ->save('invoice.pdf');
    }
}
