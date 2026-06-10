<?php

namespace App\Mail;

use App\Models\ComboBooking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public ComboBooking $booking;

    public function __construct(ComboBooking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        $booking = $this->booking->loadMissing(['combo']);

        $pdf = Pdf::loadView('pdf.booking_invoice', [
            'booking' => $booking,
        ])->setPaper('a4', 'portrait');

        $fileName = 'hoa-don-' . ($booking->booking_code ?? $booking->id) . '.pdf';

        return $this
            ->subject('Hóa đơn đặt tour Tour Link - ' . ($booking->booking_code ?? $booking->id))
            ->view('emails.booking_invoice')
            ->with([
                'booking' => $booking,
            ])
            ->attachData($pdf->output(), $fileName, [
                'mime' => 'application/pdf',
            ]);
    }
}
