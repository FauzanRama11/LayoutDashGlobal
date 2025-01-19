<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class DokumenController extends Controller
{
    public function viewPdf($fileName)
    {
        // Path ke file di luar folder Laravel
        $filePath = base_path('/' . $fileName);

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


public function viewPdfNaskah($fileName)
{
    // Path ke file yang disimpan di disk 'outside'
    $filePath = 'naskah/' . $fileName;

    // Periksa apakah file ada
    if (!Storage::disk('outside')->exists($filePath)) {
        abort(404, 'File not found.');
    }

    // Ambil file dan kirimkan sebagai respons PDF
    $fileContent = Storage::disk('outside')->get($filePath);
    
    return response($fileContent, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $fileName . '"',
    ]);
}

}
