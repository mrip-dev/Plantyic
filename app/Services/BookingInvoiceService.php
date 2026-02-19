<?php

namespace App\Services;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class BookingInvoiceService
{
    /**
     * Generate PDF invoice for a booking
     *
     * @param int $bookingId
     * @return string URL of generated PDF
     */
    public static function generate(int $bookingId): string
    {
        $booking = Booking::with('vehicle')->findOrFail($bookingId);

        // Ensure directory exists
        if (! Storage::disk('public')->exists('invoices')) {
            Storage::disk('public')->makeDirectory('invoices');
        }

        $fileName = 'booking_invoice_' . $booking->id . '_' . now()->format('Ymd_His') . '.pdf';
        $path = 'invoices/' . $fileName;

        $pdf = Pdf::loadView('pdf.booking-invoice', [
            'booking' => $booking,
            'logoUrl' => asset('storage/caartl.png'),
            'date'    => now()->format('d M, Y'),
        ])
        ->setPaper('a4', 'portrait')
        ->setWarnings(false);

        $pdf->getDomPDF()->set_option('isRemoteEnabled', true);

        Storage::disk('public')->put($path, $pdf->output());

        return asset('storage/' . $path);
    }
}
