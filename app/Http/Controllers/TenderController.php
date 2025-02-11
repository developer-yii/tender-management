<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Company;
use App\Models\Document;
use App\Models\Reference;
use App\Models\Status;
use App\Models\Tender;
use App\Models\TenderFile;
use App\Models\User;
use DocxMerge\DocxMerge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PdfMerger;


class TenderController extends Controller
{
    public function index()
    {
        if (isAdmin()) {
            $tenders = Tender::with(['files', 'users', 'tenderStatus'])->get();
        } else {
            $tenders = Tender::with(['files', 'users', 'tenderStatus'])
                ->whereHas('users', function ($query) {
                    $query->where('users.id', Auth::id());
                })
                ->get();
        }

        $employees = User::with(['tenders', 'tags', 'files'])
                            ->where('id', '!=', Auth::user()->id)
                            ->where('role', 2)
                            ->get();

        if(isAdmin()){
            return view('admin.tenders.index', compact('tenders', 'employees'));
        }else{
            return view('admin.tenders.my-tenders', compact('tenders'));
        }

    }

    public function getTenders(Request $request)
    {
        $employee = User::with(['tenders', 'tenders.tenderStatus'])->find($request->id);

        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        $tenders = $employee->tenders->map(function ($tender) {
            return [
                'id' => $tender->id,
                'tender_name' => $tender->tender_name,
                'status_icon' => $tender->tenderStatus ? $tender->tenderStatus->getIconUrl() : asset('assest/images/checkdot.png'),
                'status' => $tender->tenderStatus ? $tender->tenderStatus->title : 'Unknown',
            ];
        });
        return response()->json(['tenders' => $tenders]);
    }

    public function tenderDetails(Request $request)
    {
        $tender = Tender::with(['files', 'tenderStatus'])->find($request->id);

        $folder_files = $tender->files()
                    ->where('type', 'folder')
                    ->get()
                    ->groupBy('folder_name');
        $mainFile = getTenderFiles($tender, 'main')->first();
        $documentFiles = getTenderFiles($tender, 'documents');
        $tender->main_image = $mainFile ? $mainFile->file_path : null;
        $folder_files = getTenderFiles($tender, 'folder', 'folder_name');

        if(!$tender){
            abort(404);
        }

        $openaiApiKey = env('OPENAI_API_KEY');
        return view('admin.tenders.details', compact('tender', 'folder_files', 'documentFiles', 'openaiApiKey'));
    }

    public function addEdit(Request $request)
    {
        $tenderStatus = Status::all();
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
                    $filePath = getDocumentPath($file->company_presentation_word, 'company-presentation');
                }elseif($type == "framework"){
                    $file = Company::find($id);
                    $filePath = getDocumentPath($file->agile_framework_word, 'agile-framework');
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

            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0777, true);
                chmod($outputDir, 0777); // Force permissions to 0777
            }

            // if (!is_dir($outputDir)) {
            //     $oldUmask = umask(0); // Disable default umask effect
            //     if (!mkdir($outputDir, 0777, true) && !is_dir($outputDir)) {
            //         umask($oldUmask); // Restore umask before throwing an error
            //         throw new \RuntimeException(sprintf('Directory "%s" was not created', $outputDir));
            //     }
            //     umask($oldUmask); // Restore umask after successful creation

            //     // Ensure correct permissions
            //     chmod($outputDir, 0777);
            // }

            try {
                $existingFiles = glob($outputDir . '*');

                $dm = new DocxMerge();
                $dm->merge($docxFiles, $mergedFilePath);
                chmod($mergedFilePath, 0644);
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
                        'file_url' => $uniqueFileName
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

    public function mergePdf(Request $request)
    {
        $data = $request->all();
        $action = $data['action'] ?? 'download';
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
                    $folder = "tenders";
                    $subFolder = "tender" . $file->tender_id;
                    $filePath = getPdfFilePathUrl($folder, $subFolder, $file->file_path);
                }elseif($type == "team"){
                    $file = User::find($id);
                    $folder = "employees";
                    $subFolder = "employee" . $file->id;
                    $filePath = getPdfFilePathUrl($folder, $subFolder, $file->cv);
                }elseif($type == "certificate"){
                    $file = Certificate::find($id);
                    $folder = "certificates";
                    $subFolder = "certificate" . $file->id;
                    $filePath = getPdfFilePathUrl($folder, $subFolder, $file->certificate_pdf);
                }elseif($type == "reference"){
                    $file = Reference::find($id);
                    $folder = "references";
                    $subFolder = "reference" . $file->id;
                    $filePath = getPdfFilePathUrl($folder, $subFolder, $file->file_pdf);
                } elseif($type == "document"){
                    $file = Document::find($id);
                    $folder = "documents";
                    $subFolder = "";
                    $filePath = getPdfFilePathUrl($folder, $subFolder, $file->document_pdf);
                }elseif($type == "presentation"){
                    $file = Company::find($id);
                    $folder = "company-documents";
                    $subFolder = "";
                    $filePath = getPdfFilePathUrl($folder, $subFolder, $file->company_presentation_pdf);
                }elseif($type == "framework"){
                    $file = Company::find($id);
                    $folder = "company-documents";
                    $subFolder = "";
                    $filePath = getPdfFilePathUrl($folder, $subFolder, $file->agile_framework_pdf);
                }

                if ($filePath) {
                    $pdfFiles[] = $filePath;
                }
            }
        }

        // Check if there are PDFs to merge
        if (!empty($pdfFiles)) {
            // Generate a unique filename
            $uniqueFileName = date('d_m_Y') . '_' . uniqid() . '.pdf';

            // Set the path for the merged PDF
            $outputDir = public_path('storage/mergedFile/');
            $mergedFilePath = $outputDir . $uniqueFileName;

            // Create the directory if it doesn't exist with 0777 permissions
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0777, true);
                chmod($outputDir, 0777); // Force permissions to 0777
            }

            // if (!is_dir($outputDir)) {
            //     $oldUmask = umask(0); // Disable default umask effect
            //     if (!mkdir($outputDir, 0777, true) && !is_dir($outputDir)) {
            //         umask($oldUmask); // Restore umask before throwing an error
            //         throw new \RuntimeException(sprintf('Directory "%s" was not created', $outputDir));
            //     }
            //     umask($oldUmask); // Restore umask after successful creation

            //     // Ensure correct permissions
            //     chmod($outputDir, 0777);
            // }

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
            if ($action === 'preview') {
                return response()->json([
                    'status' => true,
                    'file_url' => $uniqueFileName
                ]);
            }

            if ($action === 'download') {
                return response()->json([
                    'status' => true,
                    'file_url' => asset('storage/mergedFile/' . $uniqueFileName),
                    'file_name' => $uniqueFileName
                ]);
            }

        }

        return response()->json([
            'status' => false,
            'message' => 'No valid PDFs to merge.'
        ]);
    }

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
            return response()->json(['status' => 400, 'message' => trans('message.invalid-request'), 'data' => []]);
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
            'folder_doc.*.*' => 'file|mimes:doc,docx,pdf,xls,xlsx,csv|max:5120',
        ];

        if (!$request->tender_id) {
            $rules = array_merge($rules, [
                'file_upload' => 'required|image|max:2048',
            ]);
        }

        $customMessage = [
            'documents.*.*' => 'The documents field must be a file of type: doc, docx, pdf.',
            'folder_doc.*.*' => 'The documents must be a file of type: doc, docx, pdf, xls, xlsx, csv.',
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

        list($period_from, $period_to) = explode(' bis ', $request->execution_period);
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
            $storagePath = storage_path("app/{$dir}");

            // Check if the directory exists; if not, create it with 0755 permissions
            if (!is_dir($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

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

        if ($request->tender_id) {
            // Retrieve the tender file documents only once
            $tenderFileDocs = TenderFile::where('tender_id', $request->tender_id)
                ->where('type', 'documents')
                ->get();

            // Determine whether to filter based on old documents or not
            $oldDocuments = $request->old_documents ?? [];

            foreach ($tenderFileDocs as $fileDoc) {
                // If old_documents is provided, only delete files not in the oldDocuments list
                if (empty($oldDocuments) || !in_array($fileDoc->original_file_name, $oldDocuments)) {
                    deleteFileAndRecord($fileDoc, $request->tender_id);
                }
            }

            // for folder and files
            $tenderFolders = TenderFile::where('tender_id', $request->tender_id)->where('type', 'folder')->get();
            $oldFolders = $request->old_folder_name ?? [];
            $oldFolderDocs = $request->old_folder_doc ?? [];

            foreach ($tenderFolders as $tenderFolder) {
                // Check if the file is not in the list of old documents
                if (empty($oldFolders) || !in_array($tenderFolder->folder_name, $oldFolders)) {
                    deleteFileAndRecord($tenderFolder, $request->tender_id);
                }

                // Check if files in the folder match the old documents
                if (empty($oldFolderDocs[$tenderFolder->folder_name]) || isset($oldFolderDocs[$tenderFolder->folder_name])) {
                    $folderDocs = $oldFolderDocs[$tenderFolder->folder_name] ?? [];

                    // Check if the file is in the list of old documents for the folder
                    if (empty($oldFolders) || !in_array($tenderFolder->original_file_name, $folderDocs)) {
                        // Define the file path
                        deleteFileAndRecord($tenderFolder, $request->tender_id);
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
            $storagePath = storage_path("app/{$dir}");
            if (!is_dir($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

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
