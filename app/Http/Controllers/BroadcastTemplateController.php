<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\StreamedResponse;

class BroadcastTemplateController extends Controller
{
    public function download(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_recipient_broadcast.csv"',
        ];

        return response()->streamDownload(function (): void {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }
            fprintf($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['phone', 'nama', 'pesan']);
            fputcsv($handle, ['6281234567890', 'Budi', 'Halo {{nama}}, ini pesan untuk nomor {{phone}}.']);
            fputcsv($handle, ['6289876543210', 'Siti', '']);
            fclose($handle);
        }, 'template_recipient_broadcast.csv', $headers);
    }

    public function downloadExcel(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_recipient_broadcast.xls"',
        ];

        return response()->streamDownload(function (): void {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }
            fprintf($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['phone', 'nama', 'pesan'], "\t");
            fputcsv($handle, ['6281234567890', 'Budi', 'Halo {{nama}}, ini pesan untuk nomor {{phone}}.'], "\t");
            fputcsv($handle, ['6289876543210', 'Siti', ''], "\t");
            fclose($handle);
        }, 'template_recipient_broadcast.xls', $headers);
    }
}
