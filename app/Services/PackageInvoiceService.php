<?php

namespace App\Services;

use App\Models\User;
use App\Models\Package;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PackageInvoiceService
{
    public static function generate(int $userId): string
    {
        $user = User::findOrFail($userId);

        $package = Package::find($user->package_id);

        if (! $package) {
            throw new \Exception('No package found for this user.');
        }

        // Ensure directory exists
        if (! Storage::disk('public')->exists('invoices')) {
            Storage::disk('public')->makeDirectory('invoices');
        }

        $fileName = 'invoice_' . $user->id . '_' . now()->format('Ymd_His') . '.pdf';
        $path = 'invoices/' . $fileName;

        $pdf = Pdf::loadView('pdf.package-invoice', [
            'user'    => $user,
            'package' => $package,
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
