<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\IOFactory;
use Spatie\Browsershot\Browsershot;
use Imagick;
use ImagickPixel;
use ImagickDraw;

class CertificateController extends Controller
{
    public function index()
    {
        $categoriesWithCertificates = Certificate::all()
                    ->groupBy('category_name');
        $categories = Certificate::categories;

        // $categories = Category::with('certificates')->get();
        return view('admin.certificates.index', compact('categories', 'categoriesWithCertificates'));
    }

    public function addupdate(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => 'Invalid Request.', 'data' => []]);
        }

        $availableCategories = Certificate::categories;
        $rules = [
            'category' => 'required|in:' . implode(',', $availableCategories),
            'title' => 'required|unique:certificates,title,' . $request->certificate_id . ',id,deleted_at,NULL',
            'description' => 'required',
            'certificate_word' => 'nullable|mimes:doc,docx|max:5120',
            'certificate_pdf' => 'nullable|mimes:pdf|max:5120',
        ];

        if (!$request->certificate_id) {
            $rules = array_merge($rules, [
                'logo' => 'required|image|max:2048',
                'certificate_word' => 'required|mimes:doc,docx|max:5120',
                'certificate_pdf' => 'required|mimes:pdf|max:5120',
            ]);
        }

        $validator = Validator::make($request->all(), $rules);

        $validator->after(function ($validator) use ($request) {
            // Check if both start_date and end_date are blank
            if (empty($request->start_date) && empty($request->end_date)) {
                $validator->errors()->add('start_date', 'Start date and end date are required.');
                $validator->errors()->add('end_date', 'Start date and end date are required.');
            }
            // Check if start_date is blank
            elseif (empty($request->start_date)) {
                $validator->errors()->add('start_date', 'Start date is required.');
            }
            // Check if end_date is blank
            elseif (empty($request->end_date)) {
                $validator->errors()->add('end_date', 'End date is required.');
            }
            // Check if end_date is after or equal to start_date
            elseif (!empty($request->start_date) && strtotime($request->end_date) < strtotime($request->start_date)) {
                $validator->errors()->add('end_date', 'End date must be after or equal to start date.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors(), 'data' => '']);
        }

        DB::beginTransaction();
        try {
            $certificate = $request->certificate_id ? Certificate::find($request->certificate_id) : new Certificate();
            if (!$certificate) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Certificate Not Found', 'data' => []]);
            }

            $certificate->category_name = $request->input('category');
            $certificate->title = $request->input('title');
            $certificate->description = $request->input('description');
            $certificate->valid_from_date = $request->input('start_date');
            $certificate->valid_to_date = $request->input('end_date');
            $certificate->save();

            $subFolderName = "certificate" . $certificate->id;

            $files = [
                'logo' => ['multiple' => false, 'folder' => 'certificates', 'subFolder' => $subFolderName],
                'certificate_word' => ['multiple' => false, 'folder' => 'certificates','subFolder' => $subFolderName],
                'certificate_pdf' => ['multiple' => false, 'folder' => 'certificates', 'subFolder' => $subFolderName],
            ];

            $certificate = handleFileUploads($request, $certificate, $files);
            if ($certificate->save()) {
                DB::commit();
                $message = $request->certificate_id ? 'Certificate updated successfully.' : 'Certificate added successfully.';
                $isNew = $request->certificate_id ? false : true;
                return response()->json(['status' => true, 'message' => $message, 'isNew' => $isNew, 'data' => []]);
            }else {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Error in saving data', 'data' => []]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'An error occurred: ' . $e->getMessage(), 'data' => []]);
        }

    }

    // private function uploadFile($file, $mainFolder, $subFolder, $oldFile = null)
    // {
    //     // Delete old file if exists
    //     if ($oldFile) {
    //         Storage::delete("public/{$mainFolder}/{$subFolder}/{$oldFile}");
    //     }

    //     $dir = "public/{$mainFolder}/{$subFolder}/";
    //     $extension = $file->getClientOriginalExtension();
    //     $uniqueName = uniqid() . "_" . time();
    //     $filename = $uniqueName . '.' . $extension;

    //     Storage::disk('local')->put($dir . $filename, File::get($file));

    //     $filePath = storage_path("app/{$dir}{$filename}");

    //     if ($extension === 'doc') {
    //         $convertedFilePath = convertDocToDocx($filePath);
    //         if ($convertedFilePath) {
    //             $filePath = $convertedFilePath;
    //             $extension = 'docx'; // Update extension after conversion
    //             $filename = $uniqueName. '.' . $extension;
    //         } else {
    //             throw new \Exception("Failed to convert .doc to .docx for file: {$filePath}");
    //         }
    //     }

    //     return $filename;
    // }


    // protected function convertDocToDocx($docPath)
    // {
    //     $docxPath = str_replace('.doc', '.docx', $docPath);

    //     // LibreOffice command to convert .doc to .docx
    //     $command = '"C:\\Program Files\\LibreOffice\\program\\soffice.exe" --headless --convert-to docx ' . escapeshellarg($docPath) . ' --outdir ' . escapeshellarg(dirname($docPath));
    //     exec($command, $output, $resultCode);

    //     if ($resultCode !== 0) {
    //         return false;
    //     }
    //     unlink($docPath);
    //     return $docxPath;
    // }

    // function convertDocxtoPdf($docxPath, $outputImagePath, $filenameonly)
    // {
    //     // Convert DOCX to PDF using LibreOffice
    //     $command = '"C:\\Program Files\\LibreOffice\\program\\soffice.exe" --headless --convert-to pdf ' . escapeshellarg($docxPath) . ' --outdir ' . escapeshellarg($outputImagePath);
    //     exec($command, $output, $resultCode);

    //     // Check if the conversion was successful
    //     if ($resultCode !== 0) {
    //         throw new \Exception("Failed to convert DOCX to PDF. Command output: " . implode("\n", $output));
    //     }

    //     // Full PDF path
    //     $fullPdfPath = $outputImagePath . $filenameonly . '.pdf';

    //     // Check if the PDF was created
    //     if (!file_exists($fullPdfPath)) {
    //         throw new \Exception("PDF file not found: " . $fullPdfPath);
    //     }

    //     // Extract only the first page using pdftk
    //     $firstPagePdfPath = $outputImagePath . $filenameonly . '_first_page.pdf';
    //     $this->extractFirstPageWithGhostscript($fullPdfPath, $firstPagePdfPath);
    //     // $this->generatePdfToImage($firstPagePdfPath, $outputImagePath);

    //     return $firstPagePdfPath;
    // }

    // function extractFirstPageWithGhostscript($fullPdfPath, $firstPagePdfPath)
    // {
    //     // Update this path if needed based on your installation
    //     $gsCommand = '"C:\\Program Files\\gs\\gs10.04.0\\bin\\gswin64c.exe" -sDEVICE=pdfwrite -dFirstPage=1 -dLastPage=1 -o ' . escapeshellarg($firstPagePdfPath) . ' ' . escapeshellarg($fullPdfPath);

    //     exec($gsCommand, $output, $resultCode);

    //     // Log the result
    //     error_log("Ghostscript command output: " . implode("\n", $output));
    //     error_log("Ghostscript command result code: " . $resultCode);

    //     unlink($fullPdfPath);
    //     return $resultCode === 0;
    // }

    // private function generatePdfToImage($pdfPath, $outputPath)
    // {
    //     $imagick = new Imagick();
    //     $imagick->setResolution(300, 300); // Set DPI
    //     // $imagick->setOption('gs', '"C:\\Program Files\\gs\\gs10.04.0\\bin\\gswin64c.exe"');
    //     $imagick->readImage($pdfPath . '[0]'); // Read the first page
    //     $imagick->setImageFormat('png');
    //     $imagick->writeImage($outputPath);
    //     $imagick->clear();
    //     $imagick->destroy();
    //     unlink($pdfPath);
    // }

    public function certificateDetails(Request $request)
    {
        $categories = Certificate::categories;
        $certificate = Certificate::find($request->id);
        if(!$certificate){
            return response()->json(['message' => 'Certificate not found.'], 404);
        }
        $this->getFilePath($certificate);
        return view('admin.certificates.details', compact('certificate', 'categories'));
    }

    public function detail(Request $request)
    {
        $certificate = Certificate::find($request->id);
        if(!$certificate){
            return response()->json(['message' => 'Certificate not found.'], 404);
        }

        $this->getFilePath($certificate);
        return response()->json($certificate);
    }

    private function getFilePath($certificate)
    {
        $certificate->logo_url = $certificate->logo
            ? $certificate->getLogoUrl()
            : null;

        $certificate->certificate_pdf_url = $certificate->certificate_pdf
            ? $certificate->getCertificatePdfUrl()
            : null;

        $certificate->certificate_word_url = $certificate->certificate_word
            ? $certificate->getCertificateWordUrl()
            : null;
    }
}
