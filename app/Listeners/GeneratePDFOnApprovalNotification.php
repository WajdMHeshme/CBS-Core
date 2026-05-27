<?php
namespace App\Listeners;

use App\Events\CommissionApproved;

class GeneratePDFOnApproval
{
    public function handle(CommissionApproved $event): void
    {
        $commission = $event->commission->load([
            'booking.car',
            'lessor',
            'employee'
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'dashboard.lessor.commissions.commission_receipt',
            compact('commission')
        );

        $fileName = 'commission-' . $commission->id . '.pdf';

        $path = storage_path("app/public/commissions/$fileName");

        file_put_contents($path, $pdf->output());

        $commission->update([
            'receipt_pdf' => "commissions/$fileName"
        ]);
    }
}
