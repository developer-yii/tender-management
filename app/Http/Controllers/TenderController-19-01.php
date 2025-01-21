<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Company;
use App\Models\Document;
use App\Models\Reference;
use App\Models\Tender;
use App\Models\TenderFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PDO;

class TenderController extends Controller
{
    public function index()
    {
        $tenders = Tender::all();
        $tenders = Tender::with(['files' => function ($query) {
            $query->where('folder_name', 'main');
        }])->get();
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
        // $tender->main_image = $mainFile ? $mainFile->file_path : null;
        // $folder_files = $tender->files()
        //             ->where('type', 'folder')
        //             ->get()
        //             ->groupBy('folder_name');
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

    public function start()
    {
        $tenders = Tender::all();
        $teamMembers = User::where('role', 2)->get();
        $companyDocuments = Company::find(1);
        $certificates = Certificate::all();
        $references = Reference::all();
        $documents = Document::all();
        return view('admin.tenders.start', compact('tenders', 'teamMembers', 'companyDocuments', 'certificates', 'references', 'documents'));
    }

    public function tenderDocuments(Request $request)
    {
        $tender = Tender::with('users', 'files')->find($request->id);
        $tenderDocuments = getTenderFiles($tender, 'documents');
        // $teamMembers = $tender->users;
        // $teamMembers = User::where('role', 2)->get();
        // $certificates = Certificate::all();
        // $references = Reference::all();
        // $documents = Document::all();
        return response()->json([
            'tenderDocuments' => $tenderDocuments,
            // 'teamMembers' => $teamMembers,
            // 'certificates' => $certificates,
            // 'references' => $references,
            // 'documents' => $documents,
        ]);
    }

    // public function teamPreview(Request $request)
    // {
    //     $user = User::findOrFail($request->id);
    //     $pdfUrl = $user->getCvUrl();
    //     $docUrl = $user->getDocumentUrl();


    //     return response()->json([
    //         'name' => $user->first_name . ' ' . $user->last_name,
    //         'pdf_url' => $pdfUrl,
    //         'doc_url' => $docUrl,
    //     ]);
    // }

    public function preview(Request $request)
    {
        // Get selected data for each section
        $selectedData = $request->input('selectedData', []);
        $teamMembers = collect();
        $certificates = collect();
        $references = collect();
        $documents = collect();

        if (!empty($selectedData['team'])) {
            $teamMembers = User::whereIn('id', $selectedData['team'])->get();
            foreach ($teamMembers as $teamMember) {
                $teamMember->doc_preview_url = $teamMember->getDocxPreviewUrl();
                $teamMember->pdf_preview_url = $teamMember->getCvUrl();
            }
        }

        // Fetch and process certificates if IDs are provided
        if (!empty($selectedData['certificates'])) {
            $certificates = Certificate::whereIn('id', $selectedData['certificates'])->get();
            foreach ($certificates as $certificate) {
                $certificate->doc_preview_url = $certificate->getDocxPreviewUrl();
                $certificate->pdf_preview_url = $certificate->getCertificatePdfUrl();
            }
        }

        // Fetch and process references if IDs are provided
        if (!empty($selectedData['references'])) {
            $references = Reference::whereIn('id', $selectedData['references'])->get();
            foreach ($references as $reference) {
                $reference->doc_preview_url = $reference->getDocxPreviewUrl();
                $reference->pdf_preview_url = $reference->getFilePdfUrl();
            }
        }

        // Fetch and process documents if IDs are provided
        if (!empty($selectedData['documents'])) {
            $documents = Document::whereIn('id', $selectedData['documents'])->get();
            foreach ($documents as $document) {
                // $document->doc_preview_url = $document->getDocxPreviewUrl();
                $document->pdf_preview_url = $document->getDocumentPdfUrl();
            }
        }

        // Generate previews for each section
        $docPreview = $this->generateDocPreview($teamMembers, $certificates, $references, $documents);
        $pdfPreview = $this->generatePdfPreview($teamMembers, $certificates, $references, $documents);

        return response()->json([
            'docPreview' => $docPreview,
            'pdfPreview' => $pdfPreview,
            'teamMembers' => $teamMembers,
            'certificates' => $certificates,
            'references' => $references,
            'documents' => $documents
        ]);
    }

    protected function generateDocPreview($teamMembers, $certificates, $references, $documents)
    {
        $docHtml = '';

        // Team members preview
        if($teamMembers->isNotEmpty()){
            foreach ($teamMembers as $member) {
                $title = $member->first_name . ' ' .$member->last_name;
                $docHtml .= '<div class="secriesBox">
                            <div class="imgbox">
                                <canvas id="team-doc-preview-' . $member->id . '"></canvas>
                            </div>
                            <div class="seriestext">
                                <p>' . $title . '</p>
                            </div>
                        </div>';
            }
        }

        if ($certificates->isNotEmpty()) {
            foreach ($certificates as $certificate) {
                $title = $certificate->title;
                $docHtml .= '<div class="secriesBox">
                            <div class="imgbox">
                                <canvas id="certificate-doc-preview-' . $certificate->id . '"></canvas>
                            </div>
                            <div class="seriestext">
                                <p>' . $title . '</p>
                            </div>
                        </div>';
            }
        }

        if ($references->isNotEmpty()) {
            foreach ($references as $reference) {
                $title = $reference->project_title;
                $docHtml .= '<div class="secriesBox">
                            <div class="imgbox">
                                <canvas id="reference-doc-preview-' . $reference->id . '"></canvas>
                            </div>
                            <div class="seriestext">
                                <p>' . $title . '</p>
                            </div>
                        </div>';
            }
        }
        return $docHtml;
    }

    protected function generatePdfPreview($teamMembers, $certificates, $references, $documents)
    {
        // Generate PDF preview (HTML) for all sections
        $pdfHtml = '';

        // Team members preview
        if ($teamMembers->isNotEmpty()) {
            foreach ($teamMembers as $member) {
                $title = $member->first_name . ' ' .$member->last_name;
                $pdfHtml .= '<div class="secriesBox">
                            <div class="imgbox">
                                <canvas id="team-pdf-preview-' . $member->id . '"></canvas>
                            </div>
                            <div class="seriestext">
                                <p>' . $title . '</p>
                            </div>
                        </div>';
            }
        }

        if ($certificates->isNotEmpty()) {
            foreach ($certificates as $certificate) {
                $title = $certificate->title;
                $pdfHtml .= '<div class="secriesBox">
                            <div class="imgbox">
                                <canvas id="certificate-pdf-preview-' . $certificate->id . '"></canvas>
                            </div>
                            <div class="seriestext">
                                <p>' . $title . '</p>
                            </div>
                        </div>';
            }
        }

        if ($references->isNotEmpty()) {
            foreach ($references as $reference) {
                $title = $reference->project_title;
                $pdfHtml .= '<div class="secriesBox">
                            <div class="imgbox">
                                <canvas id="reference-pdf-preview-' . $reference->id . '"></canvas>
                            </div>
                            <div class="seriestext">
                                <p>' . $title . '</p>
                            </div>
                        </div>';
            }
        }

        if ($documents->isNotEmpty()) {
            foreach ($documents as $document) {
                $title = $document->title;
                $pdfHtml .= '<div class="secriesBox">
                            <div class="imgbox">
                                <canvas id="document-pdf-preview-' . $document->id . '"></canvas>
                            </div>
                            <div class="seriestext">
                                <p>' . $title . '</p>
                            </div>
                        </div>';
            }
        }

        return $pdfHtml;
    }

    public function createUpdate(Request $request)
    {
        // pre($request->all());

        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => 'Invalid Request.', 'data' => []]);
        }

        $rules = [
            // 'file_upload' => 'required',
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
        ];

        $validator = Validator::make($request->all(), $rules);

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
            $oldTenderImg = $tenderFileMainImg->file_path;
            if ($oldTenderImg) {
                Storage::delete("public/tenders/tender{$request->tender_id}/{$oldTenderImg}");
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
                    'tender_id' => $request->tender_id,
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

        if ($request->hasFile('documents')) {
            $dir = "public/tenders/tender" . $tender->id . "/";
            foreach ($request->file('documents') as $documentFile) {
                $originalFileName = $documentFile->getClientOriginalName();
                $extension = $documentFile->getClientOriginalExtension();
                $newDocumentName = uniqid() . "_" . time() . '.' . $extension;

                // Save the image to storage
                Storage::disk("local")->put($dir . $newDocumentName, File::get($documentFile));

                // Create a record in the database for the image
                $tender->files()->create([
                    'type' => 'documents',
                    'original_file_name' => $originalFileName,
                    'file_path' => $newDocumentName,
                ]);
            }
        }

        if ($request->has('folder_name')) {
            $folderFiles = $request->file('folder_doc');
            $dir = "public/tenders/tender" . $tender->id . "/";

            Storage::disk("local")->makeDirectory($dir);

            foreach ($request->folder_name as $folder => $folderName) {
                if (isset($folderFiles[$folder]) && is_array($folderFiles[$folder])) {
                    foreach ($folderFiles[$folder] as $file) {
                        if ($file instanceof \Illuminate\Http\UploadedFile) {
                            // Process the file
                            $originalFileName = $file->getClientOriginalName();
                            $extension = $file->getClientOriginalExtension();
                            $newFileName = uniqid() . "_" . time() . '.' . $extension;

                            // Save the file to storage
                            Storage::disk("local")->put($dir . $newFileName, File::get($file));

                            // Create a record in the database for the file
                            $tender->files()->create([
                                'type' => 'folder',
                                'folder_name' => $folderName,
                                'original_file_name' => $originalFileName,
                                'file_path' => $newFileName,  // Storing the full path
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


    // public function add()
    // {
    //     $pdfFiles = [
    //         public_path(parse_url("/storage/certificates/certificate/test-1.pdf", PHP_URL_PATH)),
    //         public_path(parse_url("/storage/certificates/certificate/Get_Started_With_Smallpdf.pdf", PHP_URL_PATH)),
    //         public_path(parse_url("/storage/certificates/certificate/certificates1.pdf", PHP_URL_PATH)),
    //     ];

    //     $pdfMerger = PDFMerger::init(); //Initialize the merger
    //     foreach ($pdfFiles as $file) {
    //         $pdfMerger->addPDF($file);
    //     }

    //     $pdfMerger->merge(); //For a normal merge (No blank page added)
    //     //$pdfMerger->duplexMerge(); //Merges your provided PDFs and adds blank pages between documents as needed to allow duplex printing

    //     $outputPath = public_path('storage/certificates/certificate/merged_pdf.pdf');
    //     $pdfMerger->save($outputPath);
    //     exit;

    //     $documents = Certificate::all();

    //     $allFiles = [];
    //     foreach ($documents as $document) {
    //         $relativePath = $document->getCertificateUrl(); // e.g., /storage/certificates/certificate/certificates1.docx
    //         $filePath = public_path(parse_url($relativePath, PHP_URL_PATH));
    //         if (!file_exists($filePath)) {
    //             \Log::error("File not found: {$filePath}");
    //             continue; // Skip missing files
    //         }
    //         $allFiles[]= $filePath;
    //     }
    //     if(!empty($allFiles)){
    //         $dm = new DocxMerge();
    //         $dm->merge($allFiles, public_path('storage/certificates/certificate/merged_document.docx'));
    //     }

    //     return view('admin.tenders.demo', compact('documents'));
    //     // return view('admin.tenders.add');
    // }



    // public function get(Request $request)
    // {
    //     if(!$request->ajax()){
    //         return response()->json(['status' => 400, 'message' => 'Invalid Request.', 'data' => []]);
    //     }

    //     $data = Tender::all();

    //     return DataTables::of($data)
    //             ->addColumn('action', function ($data) {

    //                 $editButton = '<a href="javascript:void(0);" class="btn btn-sm btn-primary edit-tag m-r-10" data-id="' . $data->id . '" data-bs-toggle="modal" data-bs-target="#addTagModal"><i class="fa fa-edit"></i> </a>';

    //                 $deleteButton = '<a href="javascript:void(0);" class="btn btn-sm btn-danger delete-tag" data-id="' . $data->id . '" title="Delete"><i class="fa fa-trash"></i></a>';

    //                 $actionButtons = $editButton . $deleteButton;

    //                 return $actionButtons;

    //             })
    //             ->rawColumns(['action'])
    //             ->toJson();

    // }

    // public function addupdate(Request $request)
    // {
    //     if(!$request->ajax()){
    //         return response()->json(['status' => 400, 'message' => 'Invalid Request.', 'data' => []]);
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255|unique:tags,name,' . $request->tag_id . ',id,deleted_at,NULL',
    //     ]);

    //     if ($validator->fails()) {
    //         $result = ['status' => false, 'error' => $validator->errors(), 'data' => ''];
    //         return response()->json($result);
    //     }

    //     $tag = $request->tag_id ? Tender::find($request->tag_id) : new Tender();
    //     if ($request->tag_id && !$tag) {
    //         return response()->json(['status' => false, 'message' => 'Tag not found', 'data' => []], 404);
    //     }

    //     $tag->name = $request->input('name');

    //     if ($tag->save()) {
    //         $message = $request->tag_id ? 'Tag updated successfully.' : 'Tag added successfully.';
    //         return response()->json(['status' => true, 'message' => $message, 'data' => []]);
    //     }

    //     // Handle failure in saving data
    //     return response()->json(['status' => false, 'message' => 'Error in saving data', 'data' => []], 500);
    // }

    // public function detail(Request $request)
    // {
    //     $tag = Tender::find($request->id);
    //     if (!$tag) {
    //         return response()->json(['status' => false, 'message' => 'Tag not found', 'data' => []], 404);
    //     }
    //     return response()->json($tag);
    // }

    // public function delete(Request $request)
    // {
    //     $tag = Tender::find($request->id);
    //     if (!$tag) {
    //         return response()->json(['status' => false, 'message' => 'Tag not found', 'data' => []], 404);
    //     }

    //     $tag->delete();
    //     return response()->json(['status' => true, 'message' => 'Tag deleted successfully!', 'data' => []]);
    // }
}
