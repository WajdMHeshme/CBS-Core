<?php

namespace App\Services;

use App\Repositories\Contracts\CommissionRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;

class CommissionService
{
    public function __construct(
        protected CommissionRepositoryInterface $repo
    ) {}

    public function createForBooking($booking, $employee, $amount)
    {
        return $this->repo->createOrUpdateForBooking(
            $booking,
            $employee->id,
            $amount
        );
    }

    public function uploadPaymentProof($commission, $reference, $image)
    {
        if ($commission->status !== 'pending') {
            throw new \Exception('Invalid status');
        }

        $commission->update([
            'payment_reference' => $reference,
            'payment_image'     => $image,
            'status'            => 'payment_uploaded',
        ]);

        return $commission->fresh();
    }

    public function approve($commission, $user)
    {
        $commission->update([
            'status' => 'paid',
        ]);

        $commission = $commission->fresh([
            'booking.car',
            'booking.user',
            'lessor',
            'employee',
        ]);

        $pdf = Pdf::loadView(
            'dashboard.lessor.commissions.commission_receipt',
            [
                'commission' => $commission,
                'booking'    => $commission->booking,
                'car'        => $commission->booking->car,
                'customer'   => $commission->booking->user,
                'lessor'     => $commission->lessor,
                'employee'   => $user,
            ]
        )->setOptions([
            'defaultFont'          => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => true,
        ]);

        $path = storage_path('app/public/commissions');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $fileName = 'commission-' . $commission->id . '.pdf';

        file_put_contents($path . '/' . $fileName, $pdf->output());

        $commission->update([
            'receipt_pdf' => 'commissions/' . $fileName,
        ]);

        return $commission->fresh();
    }

    public function reject($commission, $user, $notes = null)
    {
        $commission->update([
            'status' => 'rejected',
            'notes'  => $notes,
        ]);

        return $commission->fresh();
    }
}
