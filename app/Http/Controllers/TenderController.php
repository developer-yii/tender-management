<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Company;
use App\Models\Document;
use App\Models\Reference;
use App\Models\Tender;
use App\Models\TenderFile;
use App\Models\User;
use DocxMerge\DocxMerge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PdfMerger;


class TenderController extends Controller
{
    public function index()
    {
        if (isAdmin()) {
            $tenders = Tender::with(['files', 'users'])->get();
        } else {
            // Employee: Fetch only tenders assigned to the logged-in user
            $tenders = Tender::with(['files', 'users'])
                ->whereHas('users', function ($query) {
                    $query->where('users.id', Auth::id()); // Explicitly specify the 'users.id' column
                })
                ->get();
        }

        $employees = User::with(['tenders', 'tags', 'files'])->where('id', '!=', Auth::user()->id)->get();

        if(isAdmin()){
            return view('admin.tenders.index', compact('tenders', 'employees'));
        }else{
            return view('admin.tenders.my-tenders', compact('tenders'));
        }

        // $documents = Certificate::all();
        // return view('admin.tenders.demo');
    }

    public function getTenders(Request $request)
    {
        $employee = User::with('tenders')->find($request->id);

        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        $tenders = $employee->tenders;
        return response()->json(['tenders' => $tenders]);
    }

    public function tenderDetails(Request $request)
    {
        $tender = Tender::with('files')->find($request->id);

        // $mainFile = $tender->files()
        //     ->where('type', 'main')
        //     ->first();
        //     pre($mainFile);
        // $tender->main_image = $mainFile ? $mainFile->file_path : null;
        $folder_files = $tender->files()
                    ->where('type', 'folder')
                    ->get()
                    ->groupBy('folder_name');
        $mainFile = getTenderFiles($tender, 'main')->first();
        $tender->main_image = $mainFile ? $mainFile->file_path : null;
        $folder_files = getTenderFiles($tender, 'folder', 'folder_name');

        if(!$tender){
            abort(404);
        }

        return view('admin.tenders.details', compact('tender', 'folder_files'));
    }

    public function addEdit(Request $request)
    {
        $tenderStatus = Tender::tenderStatus;
        $abgabeForms = Tender::abgabeForms;
        $options = Tender::options;
        $employees = User::where('role', 2)->where('is_active', true)->get();

        $tender = null;
        $folder_files = [];

        if ($request->id) {
            $tender = Tender::with('files', 'users')->find($request->id);

            if ($tender) {
                $mainFile = getTenderFiles($tender, 'main')->first();
                $tender->main_image = $mainFile ? $mainFile->file_path : null;
                $folder_files = getTenderFiles($tender, 'folder', 'folder_name');
            }
        }

        return view('admin.tenders.add', compact('tenderStatus', 'abgabeForms', 'options', 'employees', 'tender', 'folder_files'));
    }


    // public function downloadDocx(Request $request)
    // {
    //     $data = $request->all();

    //     // Access the 'loadedDocx' array
    //     $loadedDocx = $data['loadedDocx'] ?? [];

    //     $docxFiles = [];
    //     foreach ($loadedDocx as $item) {
    //         $parts = explode('-', $item);
    //         if (count($parts) === 2) {
    //             $type = $parts[0];
    //             $id = $parts[1];

    //             $filePath = null;

    //             if($type == "tender"){
    //                 $file = TenderFile::find($id);
    //                 $filePath = $file->getFilePathUrl();
    //             }elseif($type == "team"){
    //                 $file = User::find($id);
    //                 $filePath = $file->getDocumentUrl();
    //             }elseif($type == "certificate"){
    //                 $file = Certificate::find($id);
    //                 $filePath = $file->getCertificateWordUrl();
    //             }elseif($type == "reference"){
    //                 $file = Reference::find($id);
    //                 $filePath = $file->getFileWordUrl();
    //             }
    //             elseif($type == "presentation"){
    //                 $file = Company::find($id);
    //                 $filePath = getDocumentPath($file->company_presentation_word);
    //             }elseif($type == "framework"){
    //                 $file = Company::find($id);
    //                 $filePath = getDocumentPath($file->agile_framework_word);
    //             }

    //             if ($filePath) {
    //                 // $docxFiles[] = public_path(parse_url($filePath, PHP_URL_PATH));
    //                 $docxFiles[] = $filePath;
    //             }
    //         }
    //     }

    //     if (!empty($docxFiles)) {
    //         $uniqueFileName = date('d_m_Y') . '_' . uniqid() . '.docx';
    //         $outputDir = public_path('storage/mergedFile/');
    //         $mergedFilePath = $outputDir . $uniqueFileName;

    //         if (!is_dir($outputDir) && !mkdir($outputDir, 0755, true) && !is_dir($outputDir)) {
    //             throw new \RuntimeException(sprintf('Directory "%s" was not created', $outputDir));
    //         }

    //         try {
    //             // Get a list of existing files in the directory
    //             $existingFiles = glob($outputDir . '*');

    //             // Merge the files
    //             $dm = new DocxMerge();
    //             $dm->merge($docxFiles, $mergedFilePath);

    //             // Get a list of files after the merge
    //             $newFiles = glob($outputDir . '*');

    //             // Find the new temp files created
    //             $tempFiles = array_diff($newFiles, $existingFiles);

    //             // Delete the temp files
    //             foreach ($tempFiles as $file) {
    //                 if ($file !== $mergedFilePath && is_file($file)) {
    //                     unlink($file);
    //                 }
    //             }
    //         } catch (\Exception $e) {
    //             error_log('Error merging DOCX files: ' . $e->getMessage());
    //             throw $e;
    //         }
    //     }


    //     // if (!empty($docxFiles)) {
    //     //     // Filter and validate files
    //     //     $docxFiles = array_filter($docxFiles, function ($file) {
    //     //         return file_exists($file) && pathinfo($file, PATHINFO_EXTENSION) === 'docx';
    //     //     });

    //     //     if (!empty($docxFiles)) {
    //     //         $mergedFilePath = public_path('storage/mergedFile/merged_document.docx');

    //     //         // Ensure the directory exists
    //     //         if (!is_dir(dirname($mergedFilePath))) {
    //     //             mkdir(dirname($mergedFilePath), 0755, true);
    //     //         }

    //     //         $dm = new DocxMerge();

    //     //         try {
    //     //             $dm->merge($docxFiles, $mergedFilePath);
    //     //             \Log::info("Merge completed successfully: $mergedFilePath");
    //     //         } catch (\Exception $e) {
    //     //             \Log::error("Error during DocxMerge: " . $e->getMessage());
    //     //         }
    //     //     } else {
    //     //         \Log::error("No valid .docx files to merge.");
    //     //     }
    //     // } else {
    //     //     \Log::error("No files provided for merging.");
    //     // }

    //     // $pdfFilesData = [
    //     //     public_path(parse_url("/storage/demofile/test-1.pdf", PHP_URL_PATH)),
    //     //     public_path(parse_url("/storage/demofile/Get_Started_With_Smallpdf.pdf", PHP_URL_PATH)),
    //     //     public_path(parse_url("/storage/demofile/certificates2.pdf", PHP_URL_PATH)),
    //     //     // public_path(parse_url("/storage/demofile/certificates1.pdf", PHP_URL_PATH)),
    //     // ];
    //     // pre([$pdfFiles, $pdfFilesData]);

    //     // $pdfMerger = PDFMerger::init(); //Initialize the merger
    //     // foreach ($pdfFiles as $file) {
    //     //     $pdfMerger->addPDF($file);
    //     // }

    //     // $pdfMerger->merge();
    //     // $outputPath = public_path('storage/merged/merged_pdf.pdf');
    //     // $pdfMerger->save($outputPath);
    //     // exit;

    //     // $documents = Certificate::all();

    //     // $allFiles = [];
    //     // foreach ($documents as $document) {
    //     //     $relativePath = $document->getCertificateWordUrl(); // e.g., /storage/demofile/certificates1.docx
    //     //     $filePath = public_path(parse_url($relativePath, PHP_URL_PATH));
    //     //     if (!file_exists($filePath)) {
    //     //         continue; // Skip missing files
    //     //     }
    //     //     $allFiles[]= $filePath;
    //     // }
    //     // if(!empty($allFiles)){
    //     //     $dm = new DocxMerge();
    //     //     $dm->merge($allFiles, public_path('storage/demofile/merged_document.docx'));
    //     // }

    //     // return view('admin.tenders.demo', compact('documents'));
    //     // return view('admin.tenders.add');
    // }

    public function previewDocx(Request $request)
    {
        $fileUrl = $request->file_url;
        return view('admin.tenders.preview-docx', compact('fileUrl'));
    }

    public function previewPdf(Request $request)
    {
        $fileUrl = $request->file_url;
        return view('admin.tenders.preview-pdf', compact('fileUrl'));
    }

    public function mergeDocx(Request $request)
    {
        // $uniqueFileName = date('d_m_Y') . '_' . uniqid() . '.docx';
        // $outputDir = public_path('storage/mergedFile/');
        // $mergedFilePath = $outputDir . $uniqueFileName;

        // if (!is_dir($outputDir) && !mkdir($outputDir, 0755, true) && !is_dir($outputDir)) {
        //     throw new \RuntimeException(sprintf('Directory "%s" was not created', $outputDir));
        // }
        // $docFilesData = [
        //     public_path(parse_url("/storage/demofile/company-1.docx", PHP_URL_PATH)),
        //     public_path(parse_url("/storage/demofile/sample3.docx", PHP_URL_PATH)),
        //     public_path(parse_url("/storage/demofile/Exio.docx", PHP_URL_PATH)),
        // ];

        // $dm = new DocxMerge();
        // $dm->merge($docFilesData, $mergedFilePath);

        $data = $request->all();
        $action = $data['action'] ?? 'download';

        // Access the 'loadedDocx' array
        $loadedDocx = $data['loadedDocx'] ?? [];

        $docxFiles = [];
        foreach ($loadedDocx as $item) {
            $parts = explode('-', $item);
            if (count($parts) === 2) {
                $type = $parts[0];
                $id = $parts[1];

                $filePath = null;

                if($type == "tender"){
                    $file = TenderFile::find($id);
                    $filePath = $file->getFilePathUrl();
                }elseif($type == "team"){
                    $file = User::find($id);
                    $filePath = $file->getDocumentUrl();
                }elseif($type == "certificate"){
                    $file = Certificate::find($id);
                    $filePath = $file->getCertificateWordUrl();
                }elseif($type == "reference"){
                    $file = Reference::find($id);
                    $filePath = $file->getFileWordUrl();
                }elseif($type == "presentation"){
                    $file = Company::find($id);
                    $filePath = getDocumentPath($file->company_presentation_word);
                }elseif($type == "framework"){
                    $file = Company::find($id);
                    $filePath = getDocumentPath($file->agile_framework_word);
                }

                if ($filePath) {
                    $docxFiles[] = $filePath;
                }
            }
        }

        if (!empty($docxFiles)) {
            $uniqueFileName = date('d_m_Y') . '_' . uniqid() . '.docx';
            $outputDir = public_path('storage/mergedFile/');
            $mergedFilePath = $outputDir . $uniqueFileName;

            if (!is_dir($outputDir) && !mkdir($outputDir, 0755, true) && !is_dir($outputDir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $outputDir));
            }

            try {
                $existingFiles = glob($outputDir . '*');

                $dm = new DocxMerge();
                $dm->merge($docxFiles, $mergedFilePath);

                // Get a list of files after the merge
                $newFiles = glob($outputDir . '*');

                // Find the new temp files created
                $tempFiles = array_diff($newFiles, $existingFiles);

                // Delete the temp files
                foreach ($tempFiles as $file) {
                    if ($file !== $mergedFilePath && is_file($file)) {
                        unlink($file);
                    }
                }

                if ($action === 'preview') {
                    return response()->json([
                        'status' => true,
                        'file_url' => asset('storage/mergedFile/' . $uniqueFileName)
                    ]);
                }

                if ($action === 'download') {
                    return response()->json([
                        'status' => true,
                        'file_url' => asset('storage/mergedFile/' . $uniqueFileName),
                        'file_name' => $uniqueFileName
                    ]);
                }

            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Error merging DOCX files: ' . $e->getMessage()
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'No files found to merge.'
        ]);
    }

    // public function mergePdf(Request $request)
    // {
    //     $data = $request->all();
    //     $loadedPdf = $data['loadedPdf'] ?? [];
    //     $pdfFiles = [];

    //     // Loop through the loaded PDFs and get their file paths
    //     foreach ($loadedPdf as $item) {
    //         $parts = explode('-', $item);
    //         if (count($parts) === 2) {
    //             $type = $parts[0];
    //             $id = $parts[1];
    //             $filePath = null;

    //             // Determine the file path based on the type
    //             switch ($type) {
    //                 case 'tender':
    //                     $file = TenderFile::find($id);
    //                     $filePath = $file ? $file->getFilePathUrl() : null;
    //                     break;
    //                 case 'team':
    //                     $file = User::find($id);
    //                     $filePath = $file ? $file->getCvUrl() : null;
    //                     break;
    //                 case 'certificate':
    //                     $file = Certificate::find($id);
    //                     $filePath = $file ? $file->getCertificatePdfUrl() : null;
    //                     break;
    //                 case 'reference':
    //                     $file = Reference::find($id);
    //                     $filePath = $file ? $file->getFilePdfUrl() : null;
    //                     break;
    //                 case 'document':
    //                     $file = Document::find($id);
    //                     $filePath = $file ? $file->getDocumentPdfUrl() : null;
    //                     break;
    //                 case 'presentation':
    //                     $file = Company::find($id);
    //                     $filePath = $file ? getDocumentPath($file->company_presentation_pdf) : null;
    //                     break;
    //                 case 'framework':
    //                     $file = Company::find($id);
    //                     $filePath = $file ? getDocumentPath($file->agile_framework_pdf) : null;
    //                     break;
    //             }

    //             // Log the file path for debugging
    //             \Log::info('File path: ' . $filePath);

    //             // Add the file path if found
    //             if ($filePath) {
    //                 // Use public_path to resolve the file
    //                 $resolvedPath = public_path(parse_url($filePath, PHP_URL_PATH));
    //                 \Log::info('Resolved file path: ' . $resolvedPath); // Log resolved path

    //                 // Check if the file exists before adding to the merge list
    //                 if (file_exists($resolvedPath)) {
    //                     $pdfFiles[] = $resolvedPath;
    //                     \Log::info('PDF added for merging: ' . $resolvedPath);
    //                 } else {
    //                     \Log::error('File does not exist: ' . $resolvedPath); // Log if file does not exist
    //                 }
    //             } else {
    //                 \Log::warning('No file found for ID: ' . $id); // Log if no file path found
    //             }
    //         } else {
    //             \Log::warning('Invalid item format: ' . $item); // Log if item format is invalid
    //         }
    //     }

    //     // Check if there are PDFs to merge
    //     if (!empty($pdfFiles)) {
    //         // Generate a unique filename
    //         $uniqueFileName = date('d_m_Y') . '_' . uniqid() . '.pdf';

    //         // Set the path for the merged PDF
    //         $mergedFilePath = public_path('storage/mergedFile/' . $uniqueFileName);

    //         // Create the directory if it doesn't exist
    //         if (!is_dir(dirname($mergedFilePath))) {
    //             try {
    //                 mkdir(dirname($mergedFilePath), 0755, true);
    //                 \Log::info('Directory created: ' . dirname($mergedFilePath));
    //             } catch (\Exception $e) {
    //                 \Log::error('Error creating directory: ' . $e->getMessage());
    //                 return response()->json([
    //                     'status' => false,
    //                     'message' => 'Failed to create directory for merged file.'
    //                 ]);
    //             }
    //         }

    //         // Initialize the PDFMerger
    //         $pdfMerger = PDFMerger::init();

    //         // Add each PDF to the merger
    //         foreach ($pdfFiles as $file) {
    //             if (file_exists($file)) {
    //                 $pdfMerger->addPDF($file);
    //             } else {
    //                 \Log::error('File not found when adding to merge: ' . $file);
    //             }
    //         }

    //         // Merge and save the output file
    //         try {
    //             $pdfMerger->merge();
    //             $pdfMerger->save($mergedFilePath);

    //             // Respond with the merged PDF details
    //             return response()->json([
    //                 'status' => true,
    //                 'file_url' => asset('storage/mergedFile/' . $uniqueFileName),
    //                 'file_name' => $uniqueFileName
    //             ]);
    //         } catch (\Exception $e) {
    //             \Log::error('Error merging PDFs: ' . $e->getMessage());
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Failed to merge PDFs.'
    //             ]);
    //         }
    //     }

    //     // Return error message if no PDFs to merge
    //     \Log::warning('No valid PDFs to merge.');
    //     return response()->json([
    //         'status' => false,
    //         'message' => 'No valid PDFs to merge.'
    //     ]);
    // }


    public function mergePdf(Request $request)
    {
        $data = $request->all();
        $loadedPdf = $data['loadedPdf'] ?? [];
        $pdfFiles = [];

        foreach ($loadedPdf as $item) {
            $parts = explode('-', $item);
            if (count($parts) === 2) {
                $type = $parts[0];
                $id = $parts[1];

                $filePath = null;

                if($type == "tender"){
                    $file = TenderFile::find($id);
                    $filePath = $file->getFilePathUrl();
                }elseif($type == "team"){
                    $file = User::find($id);
                    $filePath = $file->getCvUrl();
                }elseif($type == "certificate"){
                    $file = Certificate::find($id);
                    $filePath = $file->getCertificatePdfUrl();
                }elseif($type == "reference"){
                    $file = Reference::find($id);
                    $filePath = $file->getFilePdfUrl();
                } elseif($type == "document"){
                    $file = Document::find($id);
                    $filePath = $file->getDocumentPdfUrl();
                }elseif($type == "presentation"){
                    $file = Company::find($id);
                    $filePath = getDocumentPath($file->company_presentation_pdf);
                }elseif($type == "framework"){
                    $file = Company::find($id);
                    $filePath = getDocumentPath($file->agile_framework_pdf);
                }

                if ($filePath) {
                    // $pdfFiles[] = public_path(parse_url($filePath, PHP_URL_PATH));
                    $pdfFiles[] = $filePath;
                }
            }
        }

        // foreach ($loadedPdf as $item) {
        //     $parts = explode('-', $item);
        //     if (count($parts) === 2) {
        //         $type = $parts[0];
        //         $id = $parts[1];
        //         $filePath = null;

        //         // Determine the file path based on the type
        //         switch ($type) {
        //             case 'tender':
        //                 $file = TenderFile::find($id);
        //                 $filePath = $file ? $file->getFilePathUrl() : null;
        //                 break;
        //             case 'team':
        //                 $file = User::find($id);
        //                 $filePath = $file ? $file->getCvUrl() : null;
        //                 break;
        //             case 'certificate':
        //                 $file = Certificate::find($id);
        //                 $filePath = $file ? $file->getCertificatePdfUrl() : null;
        //                 break;
        //             case 'reference':
        //                 $file = Reference::find($id);
        //                 $filePath = $file ? $file->getFilePdfUrl() : null;
        //                 break;
        //             case 'document':
        //                 $file = Document::find($id);
        //                 $filePath = $file ? $file->getDocumentPdfUrl() : null;
        //                 break;
        //             case 'presentation':
        //                 $file = Company::find($id);
        //                 $filePath = $file ? getDocumentPath($file->company_presentation_pdf) : null;
        //                 break;
        //             case 'framework':
        //                 $file = Company::find($id);
        //                 $filePath = $file ? getDocumentPath($file->agile_framework_pdf) : null;
        //                 break;
        //         }

        //         // Add the file path if found
        //         if ($filePath) {
        //             $pdfFiles[] = public_path(parse_url($filePath, PHP_URL_PATH));
        //         }
        //     }
        // }

        \Log::info($pdfFiles);
        // Check if there are PDFs to merge
        if (!empty($pdfFiles)) {
            // Generate a unique filename
            $uniqueFileName = date('d_m_Y') . '_' . uniqid() . '.pdf';

            // Set the path for the merged PDF
            $mergedFilePath = public_path('storage/mergedFile/' . $uniqueFileName);

            // Create the directory if it doesn't exist
            if (!is_dir(dirname($mergedFilePath))) {
                mkdir(dirname($mergedFilePath), 0755, true);
            }

            // Initialize the PDFMerger
            $pdfMerger = PDFMerger::init();
            foreach ($pdfFiles as $file) {
                if (file_exists($file)) {
                    $pdfMerger->addPDF($file, 'all'); // Explicitly specify 'all' pages
                } else {
                    // Log or handle missing files
                    logger()->warning("PDF file not found: $file");
                }
            }

            // Merge and save the output file
            try {
                $pdfMerger->merge();
                $pdfMerger->save($mergedFilePath);
            } catch (\Exception $e) {
                echo "Error merging PDFs: " . $e->getMessage();
            }

            // Respond with the merged PDF details
            return response()->json([
                'status' => true,
                'file_url' => asset('storage/mergedFile/' . $uniqueFileName),
                'file_name' => $uniqueFileName
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'No valid PDFs to merge.'
        ]);
    }


    // public function mergePdf(Request $request)
    // {
    //     $data = $request->all();
    //     $loadedPdf = $data['loadedPdf'] ?? [];
    //     $pdfFiles = [];
    //     foreach ($loadedPdf as $item) {
    //         $parts = explode('-', $item);
    //         if (count($parts) === 2) {
    //             $type = $parts[0];
    //             $id = $parts[1];

    //             $filePath = null;

    //             if($type == "tender"){
    //                 $file = TenderFile::find($id);
    //                 $filePath = $file->getFilePathUrl();
    //             }elseif($type == "team"){
    //                 $file = User::find($id);
    //                 $filePath = $file->getCvUrl();
    //             }elseif($type == "certificate"){
    //                 $file = Certificate::find($id);
    //                 $filePath = $file->getCertificatePdfUrl();
    //             }elseif($type == "reference"){
    //                 $file = Reference::find($id);
    //                 $filePath = $file->getFilePdfUrl();
    //             } elseif($type == "document"){
    //                 $file = Document::find($id);
    //                 $filePath = $file->getDocumentPdfUrl();
    //             }elseif($type == "presentation"){
    //                 $file = Company::find($id);
    //                 $filePath = getDocumentPath($file->company_presentation_pdf);
    //             }elseif($type == "framework"){
    //                 $file = Company::find($id);
    //                 $filePath = getDocumentPath($file->agile_framework_pdf);
    //             }

    //             if ($filePath) {
    //                 $pdfFiles[] = public_path(parse_url($filePath, PHP_URL_PATH));
    //                 // $pdfFiles[] = $filePath;
    //             }
    //         }
    //     }

    //     $pdfFiles = [
    //         // public_path(parse_url("/storage/tenders/tender1/678ddf9d77f9e_1737351069.pdf", PHP_URL_PATH)),
    //         // public_path(parse_url("/storage/employees/employee2/67810af49eb00_1736510196.pdf", PHP_URL_PATH)),
    //         // public_path(parse_url("/storage/employees/employee3/67810c4feb7c7_1736510543.pdf", PHP_URL_PATH)),
    //         // public_path(parse_url("/storage/employees/employee4/67810d0469d40_1736510724.pdf", PHP_URL_PATH)),
    //         // public_path(parse_url("/storage/employees/employee5/67811b9ddec12_1736514461.pdf", PHP_URL_PATH)),
    //         // public_path(parse_url("/storage/certificates/certificate1/certificate1.pdf", PHP_URL_PATH)),
    //         // public_path(parse_url("/storage/certificates/certificate2/certificate2.pdf", PHP_URL_PATH)),
    //         // public_path(parse_url("/storage/certificates/certificate3/678ce076090dd_1737285750.pdf", PHP_URL_PATH)), //error
    //         // public_path(parse_url("/storage/certificates/certificate4/678ce1573278e_1737285975.pdf", PHP_URL_PATH)),
    //         // public_path(parse_url("/storage/certificates/certificate7/678e10630edde_1737363555.pdf", PHP_URL_PATH)),
    //         // public_path(parse_url("/storage/references/reference1/ref1.pdf", PHP_URL_PATH)), // error same file
    //         // public_path(parse_url("/storage/documents/document_1.pdf", PHP_URL_PATH)),
    //         // public_path(parse_url("/storage/documents/document_2.pdf", PHP_URL_PATH)),
    //         // public_path(parse_url("/storage/documents/document_3.pdf", PHP_URL_PATH)),
    //         // public_path(parse_url("/storage/documents/document_4.pdf", PHP_URL_PATH)),
    //         // public_path(parse_url("/storage/documents/document_5.pdf", PHP_URL_PATH)),
    //         // public_path(parse_url("/storage/company-documents/company-presentation.pdf", PHP_URL_PATH)),
    //         // public_path(parse_url("/storage/company-documents/agile-framework.pdf", PHP_URL_PATH)),
    //     ];

    //     if(!empty($pdfFiles)){
    //         $mergedFilePath = public_path('storage/mergedFile/merged_pdf.pdf');
    //         if (!is_dir(dirname($mergedFilePath))) {
    //             mkdir(dirname($mergedFilePath), 0755, true);
    //         }

    //         $pdfMerger = PDFMerger::init(); //Initialize the merger
    //         foreach ($pdfFiles as $file) {
    //             $pdfMerger->addPDF($file);
    //         }

    //         $pdfMerger->merge();
    //         $outputPath = public_path('storage/mergedFile/merged_pdf.pdf');
    //         $pdfMerger->save($outputPath);
    //     }

    //     // $pdfFilesData = [
    //     //     public_path(parse_url("/storage/demofile/test-1.pdf", PHP_URL_PATH)),
    //     //     public_path(parse_url("/storage/demofile/Get_Started_With_Smallpdf.pdf", PHP_URL_PATH)),
    //     //     public_path(parse_url("/storage/demofile/certificates2.pdf", PHP_URL_PATH)),
    //     //     // public_path(parse_url("/storage/demofile/certificates1.pdf", PHP_URL_PATH)),
    //     // ];
    //     // pre([$pdfFiles, $pdfFilesData]);

    //     // $pdfMerger = PDFMerger::init(); //Initialize the merger
    //     // foreach ($pdfFiles as $file) {
    //     //     $pdfMerger->addPDF($file);
    //     // }

    //     // $pdfMerger->merge();
    //     // $outputPath = public_path('storage/merged/merged_pdf.pdf');
    //     // $pdfMerger->save($outputPath);
    //     // exit;
    // }

    // public function addEdit()
    // {
    //     $pdfFiles = [
    //         public_path(parse_url("/storage/demofile/test-1.pdf", PHP_URL_PATH)),
    //         public_path(parse_url("/storage/demofile/Get_Started_With_Smallpdf.pdf", PHP_URL_PATH)),
    //         public_path(parse_url("/storage/demofile/certificates2.pdf", PHP_URL_PATH)),
    //         // public_path(parse_url("/storage/demofile/certificates1.pdf", PHP_URL_PATH)),
    //     ];

    //     // $pdfMerger = new PDFMerger();
    //     // $pdfMerger->init();
    //     $pdfMerger = PDFMerger::init(); //Initialize the merger
    //     foreach ($pdfFiles as $file) {
    //         $pdfMerger->addPDF($file);
    //     }

    //     $pdfMerger->merge(); //For a normal merge (No blank page added)
    //     //$pdfMerger->duplexMerge(); //Merges your provided PDFs and adds blank pages between documents as needed to allow duplex printing

    //     $outputPath = public_path('storage/demofile/merged_pdf.pdf');
    //     $pdfMerger->save($outputPath);
    //     // exit;

    //     $documents = Certificate::all();

    //     $allFiles = [];
    //     foreach ($documents as $document) {
    //         $relativePath = $document->getCertificateWordUrl(); // e.g., /storage/demofile/certificates1.docx
    //         $filePath = public_path(parse_url($relativePath, PHP_URL_PATH));
    //         \Log::info([$relativePath, $filePath]);
    //         if (!file_exists($filePath)) {
    //             continue; // Skip missing files
    //         }
    //         $allFiles[]= $filePath;
    //     }
    //     if(!empty($allFiles)){
    //         $dm = new DocxMerge();
    //         $dm->merge($allFiles, public_path('storage/demofile/merged_document.docx'));
    //     }

    //     return view('admin.tenders.demo', compact('documents'));
    //     // return view('admin.tenders.add');
    // }


    public function start()
    {
        $tenders = Tender::all();
        $teamMembers = User::where('role', 2)->get();
        $companyDocument = Company::find(1);
        $certificates = Certificate::all();
        $references = Reference::all();
        $documents = Document::all();
        return view('admin.tenders.start', compact('tenders', 'teamMembers', 'companyDocument', 'certificates', 'references', 'documents'));
    }

    public function tenderDocuments(Request $request)
    {
        $tender = Tender::with('users', 'files')->find($request->id);
        $tenderDocuments = getTenderFiles($tender, 'documents');
        foreach($tenderDocuments as $tenderDocument){
            $tenderDocument->docx_preview_url = '';
            $tenderDocument->pdf_preview_url = '';
            if (pathinfo($tenderDocument->original_file_name, PATHINFO_EXTENSION) === 'docx') {
                $tenderDocument->docx_preview_url = $tenderDocument->getDocxPreviewUrl();
            } else {
                $tenderDocument->pdf_preview_url = $tenderDocument->getFilePathUrl();
            }
        }

        return response()->json([
            'tenderDocuments' => $tenderDocuments,
        ]);
    }

    public function createUpdate(Request $request)
    {
        // pre($request->all());
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => 'Invalid Request.', 'data' => []]);
        }

        $rules = [
            'file_upload' => 'nullable|image|max:2048',
            'ausschreibung_name' => 'required',
            'performance_title' => 'required',
            'kurze_beschreibung' => 'required',
            'vergabestelle' => 'required',
            'execution_location' => 'required',
            'execution_period' => 'required',
            'abgabeform' => 'required',
            'status' => 'required',
            'binding_period' => 'required',
            'applicant_questions_date' => 'required',
            'expiry_offer_date' => 'required',
            'subdivision_lots' => 'required',
            'side_offers_allowed' => 'required',
            'main_offers_allowed' => 'required',
            'vergabeordnung' => 'required',
            'vergabeverfahren' => 'required',
            'employees' => 'required',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:doc,docx,pdf|max:5120',
            'folder_doc' => 'nullable|array',
            'folder_doc.*.*' => 'file|mimes:doc,docx,pdf|max:5120',
        ];

        if (!$request->tender_id) {
            $rules = array_merge($rules, [
                'file_upload' => 'required|image|max:2048',
            ]);
        }

        $customMessage = [
            'documents.*.*' => 'The documents field must be a file of type: doc, docx, pdf.',
            'folder_doc.*.*' => 'The documents must be a file of type: doc, docx, pdf.',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessage);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors(), 'data' => '']);
        }

        $tender = $request->tender_id ? Tender::find($request->tender_id) : new Tender();
        if (!$tender) {
            return response()->json(['status' => false, 'message' => 'Tender Not Found', 'data' => []]);
        }

        $tender->tender_name = $request->input('ausschreibung_name');
        $tender->title = $request->input('performance_title');
        $tender->description = $request->input('kurze_beschreibung');
        $tender->vergabestelle = $request->input('vergabestelle');
        $tender->place_of_execution = $request->input('execution_location');
        $tender->abgabeform = $request->input('abgabeform');
        $tender->status = $request->input('status');
        $tender->binding_period = $request->input('binding_period');
        $tender->question_ask_last_date = $request->input('applicant_questions_date');
        $tender->offer_period_expiration = $request->input('expiry_offer_date');
        $tender->is_subdivision_lots = $request->input('subdivision_lots');
        $tender->is_side_offers_allowed = $request->input('side_offers_allowed');
        $tender->is_main_offers_allowed = $request->input('main_offers_allowed');
        $tender->procurement_regulations = $request->input('vergabeordnung');
        $tender->procurement_procedures = $request->input('vergabeverfahren');

        list($period_from, $period_to) = explode(' to ', $request->execution_period);
        $tender->period_from = $period_from;
        $tender->period_to = $period_to;
        $tender->save();

        if ($request->hasFile('file_upload')) {
            $tenderFileMainImg = TenderFile::where('tender_id', $request->tender_id)->where('type', 'main')->first();
            if($tenderFileMainImg){
                $oldTenderImg = $tenderFileMainImg->file_path;
                if ($oldTenderImg) {
                    Storage::delete("public/tenders/tender{$request->tender_id}/{$oldTenderImg}");
                }
            }
            $dir = "public/tenders/tender" . $tender->id . "/";
            $tenderImage = $request->file('file_upload');
            $originalFileName = $tenderImage->getClientOriginalName();
            $extension = $tenderImage->getClientOriginalExtension();
            $newImageName = uniqid() . "_" . time() . '.' . $extension;

            // Save the image to storage
            Storage::disk("local")->put($dir . $newImageName, File::get($tenderImage));

            // Create a record in the database for the image
            $tenderFile = TenderFile::updateOrCreate(
                [
                    'tender_id' => $tender->id,
                    'type' => 'main',
                ],
                [
                    'original_file_name' => $originalFileName,
                    'file_path' => $newImageName,
                ]
            );
        }

        if($request->old_documents){
            $tenderFileDocs = TenderFile::where('tender_id', $request->tender_id)->where('type', 'documents')->get();
            $oldDocuments = $request->old_documents;

            // Loop through each tender file document
            foreach ($tenderFileDocs as $fileDoc) {
                // Check if the file is not in the list of old documents
                if (!in_array($fileDoc->original_file_name, $oldDocuments)) {
                    // Define the file path
                    $filePath = "public/tenders/tender{$request->tender_id}/{$fileDoc->file_path}";

                    // Delete the file from storage
                    if (Storage::exists($filePath)) {
                        Storage::delete($filePath);
                    }

                    // Delete the record from the database
                    $fileDoc->delete();
                }
            }
        }

        // Iterate over the old folder structure
        if($request->old_folder_name){
            $tenderFolders = TenderFile::where('tender_id', $request->tender_id)->where('type', 'folder')->get();
            $oldFolders = $request->old_folder_name;
            $oldFolderDocs = $request->old_folder_doc;

            foreach ($tenderFolders as $tenderFolder) {
                // Check if the file is not in the list of old documents
                if (!in_array($tenderFolder->folder_name, $oldFolders)) {
                    // Define the file path
                    $filePath = "public/tenders/tender{$request->tender_id}/{$tenderFolder->file_path}";

                    // Delete the file from storage
                    if (Storage::exists($filePath)) {
                        Storage::delete($filePath);
                    }

                    // Delete the record from the database
                    $tenderFolder->delete();
                }

                // Check if files in the folder match the old documents
                if (isset($oldFolderDocs[$tenderFolder->folder_name])) {
                    $folderDocs = $oldFolderDocs[$tenderFolder->folder_name];

                    // Check if the file is in the list of old documents for the folder
                    if (!in_array($tenderFolder->original_file_name, $folderDocs)) {
                        // Define the file path
                        $filePath = "public/tenders/tender{$request->tender_id}/{$tenderFolder->file_path}";

                        // Delete the file from storage
                        if (Storage::exists($filePath)) {
                            Storage::delete($filePath);
                        }

                        // Delete the record from the database
                        $tenderFolder->delete();
                    }
                }
            }
        }

        if($request->old_folder_doc){
            foreach ($request->old_folder_doc as $folderKey => $oldFiles) {
                // Get the folder name for this folder key
                $folderName = $request->old_folder_name[$folderKey] ?? null;

                if ($folderName) {
                    // Get all existing files from the database for this folder
                    $tenderFileDocs = TenderFile::where('tender_id', $request->tender_id)
                        ->where('type', 'folder')
                        ->where('folder_name', $folderName)
                        ->get();

                    // Get the newly uploaded files for this folder, if any
                    $newUploadedFiles = $request->folder_doc[$folderKey] ?? [];
                    $newUploadedFileNames = array_map(function ($file) {
                        return $file->getClientOriginalName(); // Extract original names of uploaded files
                    }, $newUploadedFiles);

                    // Combine the old files and newly uploaded file names to determine what to keep
                    $keepFiles = array_merge($oldFiles, $newUploadedFileNames);

                    foreach ($tenderFileDocs as $fileDoc) {
                        // If the file in the database is not in the "keep" list, delete it
                        if (!in_array($fileDoc->original_file_name, $keepFiles)) {
                            // Delete from storage
                            $filePath = "public/tenders/tender{$request->tender_id}/{$fileDoc->file_path}";
                            if (Storage::exists($filePath)) {
                                Storage::delete($filePath);
                            }

                            // Delete from the database
                            $fileDoc->delete();
                        }
                    }
                }
            }
        }

        if ($request->hasFile('documents')) {
            $dir = "public/tenders/tender" . $tender->id . "/";
            $subFolderName = "tender" . $tender->id;

            // Correct the structure of the configuration array
            $filesConfig = [
                'documents' => [
                    'multiple' => true, // Indicating this field allows multiple files
                    'folder' => 'tenders',
                    'subFolder' => $subFolderName,
                    'type' => 'documents',
                ],
            ];

            // Now pass the configuration to the helper function
            $tender = handleFileUploads($request, $tender, $filesConfig);
        }

        if ($request->has('folder_name')) {
            $folderFiles = $request->file('folder_doc');
            $dir = "public/tenders/tender" . $tender->id . "/";
            $subFolderName = "tender" . $tender->id;
            Storage::disk("local")->makeDirectory($dir);

            foreach ($request->folder_name as $folder => $folderName) {
                if (isset($folderFiles[$folder]) && is_array($folderFiles[$folder])) {
                    foreach ($folderFiles[$folder] as $folderFile) {
                        if ($folderFile instanceof \Illuminate\Http\UploadedFile) {

                            // uploadFile($file, "tenders", $subFolderName,null);
                            $fileData = uploadFile($folderFile, "tenders", $subFolderName, null);

                            $extension = $folderFile->getClientOriginalExtension();
                            $originalFileName = $folderFile->getClientOriginalName();

                            if ($extension === 'doc') {
                                $originalFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . ".docx";
                            }

                            $tender->files()->create([
                                'type' => 'folder',
                                'folder_name' => $folderName,
                                'original_file_name' => $originalFileName,
                                'file_path' => $fileData['filename'],
                                'docx_preview' => $fileData['firstPagePdfPath'] ?? null,
                            ]);
                        }
                    }
                }
            }
        }

        $tender->users()->sync($request->employees);
        if ($tender->save()) {

            $message = $request->tender_id ? 'Tender updated successfully.' : 'Tender added successfully.';
            $isNew = $request->tender_id ? false : true;
            return response()->json(['status' => true, 'message' => $message, 'isNew' => $isNew, 'data' => []]);
        }

        return response()->json(['status' => false, 'message' => 'Error in saving data', 'data' => []]);
    }
}
