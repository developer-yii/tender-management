<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TenderController extends Controller
{
    public function index()
    {
        $tenders = Tender::all();
        $employees = User::with(['tenderUsers', 'tags', 'files'])->where('id', '!=', Auth::user()->id)->get();
        if(isAdmin()){
            return view('admin.tenders.index', compact('tenders', 'employees'));
        }else{
            return view('admin.tenders.my-tenders', compact('tenders'));
        }
    }

    public function add()
    {
        $tenderStatus = Tender::tenderStatus;
        $employees = User::where('role', 2)->where('is_active', true)->get();
        return view('admin.tenders.add', compact('tenderStatus', 'employees'));
    }

    public function createUpdate(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => 'Invalid Request.', 'data' => []]);
        }

        $rules = [
            'tender_name' => 'required',
            'title' => 'required',
            'description' => 'required',
            'vergabestelle' => 'required',
            'place_of_execution' => 'required',
            'abgabeform' => 'required',
            'status' => 'required',
            'execution_start_date' => 'required',
            'execution_end_date' => 'required',
            'expiry_offer_period' => 'required',
            'offer_end_date' => 'required',
            'binding_period' => 'required',
            'applicant question_date' => 'required',
            'image' => 'required|string|max:255|unique:users,email,' . $request->employee_id . ',id,deleted_at,NULL',
            'user_role' => 'required',
            'user_status' => 'required',
        ];

        if (!$request->employee_id) {
            $rules['password'] = 'required';
            $rules['profile_photo'] = 'required|image|max:2048';
            $rules['cv'] = 'required|mimes:pdf,doc,docx|max:5120';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors(), 'data' => '']);
        }

        $employee = $request->employee_id ? User::find($request->employee_id) : new User();
        if (!$employee) {
            return response()->json(['status' => false, 'message' => 'Employee Not Found', 'data' => []]);
        }

        $employee->first_name = $request->input('first_name');
        $employee->last_name = $request->input('last_name');
        $employee->email = $request->input('email');
        $employee->description = $request->input('description');
        if ($request->input('password')){
            $employee->password = Hash::make($request->password);
        }
        $employee->save();
        if ($request->hasFile('profile_photo')) {
            $oldProfilePic = $employee->profile_pic;
            $employee->profile_pic = $this->uploadFile($request->file('profile_photo'), 'profile-photo', 'employee', $employee->id, $oldProfilePic);
        }

        // Handle CV upload
        if ($request->hasFile('cv')) {
            $oldCv = $employee->cv;
            $employee->cv = $this->uploadFile($request->file('cv'), 'cv', 'employee', $employee->id, $oldCv);
        }
        if ($employee->save()) {
            $employee->tags()->sync($request->input('tags'));

            $message = $request->employee_id ? 'Employee updated successfully.' : 'Employee added successfully.';
            $isNew = $request->employee_id ? false : true;
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
