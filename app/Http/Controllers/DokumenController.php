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

public function view($folderOrFile = null, $fileName = null)
{
    if (is_null($folderOrFile)) {
        abort(404, 'Folder atau file tidak ditemukan.');
    }
    
    if (is_null($fileName)) {
        $fileName = $folderOrFile;
        $folder = null;
    } else {
        $folder = $folderOrFile;
    }

    $filePath = $folder 
        ? trim($folder, '/') . '/' . ltrim($fileName, '/') 
        : ltrim($fileName, '/');
        

    $filePath = str_replace('+', ' ', $filePath);

    if (!Storage::disk('inside')->exists($filePath)) {
        abort(404, 'File tidak ditemukan.');
    }

    $fileContent = Storage::disk('inside')->get($filePath);
    $mimeType = Storage::disk('inside')->mimeType($filePath);

   
    return response($fileContent, 200, [
        'Content-Type' => $mimeType,
        'Content-Disposition' => 'inline; filename="' . $fileName . '"',
    ]);
}

}
