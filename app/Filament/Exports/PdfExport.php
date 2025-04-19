<?php

namespace App\Filament\Exports;

use App\Models\Action;
use Spatie\LaravelPdf\Facades\Pdf;

class PdfExport
{
    public function exportAcion(Action $action)
    {
        Pdf::html(view('pdf.test', [
            'invoiceNumber' => '1234',
            'customerName' => 'Grumpy Cat',

        ]))
            ->save('invoice.pdf');
    }
}
