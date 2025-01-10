<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use League\CommonMark\Node\Block\Document;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Certificate::all();
        return view('admin.documents.index', compact('documents'));
    }


    // public function previewWordFile($fileName)
    // {
    //     // Path to the Word file
    //     $filePath = storage_path("app/public/documents/{$fileName}");
    //     \Log::info($filePath);
    //     // Check if file exists
    //     if (!file_exists($filePath)) {
    //         abort(404, 'File not found.');
    //     }

    //     // Load the Word file
    //     $phpWord = IOFactory::load($filePath);

    //     // Save as HTML
    //     $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
    //     $previewPath = storage_path('app/public/preview.html');
    //     $htmlWriter->save($previewPath);

    //     // Display the HTML file in a view
    //     $htmlContent = file_get_contents($previewPath);
    //     return view('admin.documents.index', ['htmlContent' => $htmlContent]);
    // }

}
