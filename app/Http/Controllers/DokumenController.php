<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DokumenController extends Controller
{
    public function viewPdf($fileName)
    {
        // Path ke file di luar folder Laravel
        $filePath = base_path('../penyimpanan/' . $fileName);

        // Periksa apakah file ada
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Kirim file sebagai respons PDF
        return Response::make(file_get_contents($filePath), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }
}
