<?php

namespace App\Http\Controllers;

use App\Models\Reference;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReferenceController extends Controller
{
    public function index()
    {
        $tags = Tag::with('references')->get();
        $referencesWithoutTags = Reference::doesntHave('tags')->get();
        return view('admin.references.index', compact('tags', 'referencesWithoutTags'));
    }

    public function addupdate(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => 'Invalid Request.', 'data' => []]);
        }

        $rules = [
            'project_title' => 'required|string|max:255|unique:references,project_title,' . $request->reference_id . ',id,deleted_at,NULL',
            'scope' => 'required',
            'file_word' => 'nullable|mimes:doc,docx|max:5120',
            'file_pdf' => 'nullable|mimes:pdf|max:5120',
        ];

        if (!$request->reference_id) {
            $rules = array_merge($rules, [
                'file_word' => 'required|mimes:doc,docx|max:5120',
                'file_pdf' => 'required|mimes:pdf|max:5120',
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
            $reference = $request->reference_id ? Reference::find($request->reference_id) : new Reference();
            if (!$reference) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Reference Not Found', 'data' => []]);
            }

            $reference->project_title = $request->input('project_title');
            $reference->scope = $request->input('scope');
            $reference->start_date = $request->input('start_date');
            $reference->end_date = $request->input('end_date');
            if ($reference->save()) {
                $subFolderName = "reference" . $reference->id;

                $files = [
                    'file_word' => ['multiple' => false, 'folder' => 'references','subFolder' => $subFolderName],
                    'file_pdf' => ['multiple' => false, 'folder' => 'references', 'subFolder' => $subFolderName],
                ];

                $reference = handleFileUploads($request, $reference, $files);
                $reference->save();
                $reference->tags()->sync($request->input('tags'));
                Db::commit();
                $message = $request->reference_id ? 'Reference updated successfully.' : 'Reference added successfully.';
                $isNew = $request->reference_id ? false : true;
                return response()->json(['status' => true, 'message' => $message, 'isNew' => $isNew, 'data' => []]);
            }else{
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Error in saving data', 'data' => []]);
            }
        } catch (\Exception $e) {
            // Rollback on any exception
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'An error occurred: ' . $e->getMessage(), 'data' => []]);
        }

        return response()->json(['status' => false, 'message' => 'Error in saving data', 'data' => []]);
    }

    public function referenceDetails(Request $request)
    {
        $reference = Reference::with('tags')->find($request->id);
        if(!$reference){
            return response()->json(['message' => 'Reference not found.'], 404);
        }
        $tags = Tag::all();
        $this->getFilePath($reference);
        return view('admin.references.details', compact('tags', 'reference'));
    }

    public function detail(Request $request)
    {
        $reference = Reference::with('tags')->find($request->id);
        if(!$reference){
            return response()->json(['message' => 'Reference not found.'], 404);
        }
        $this->getFilePath($reference);
        return response()->json($reference);
    }

    private function getFilePath($employee)
    {
        $employee->file_word_url = $employee->file_word
            ? $employee->getFileWordUrl()
            : null;

        $employee->file_pdf_url = $employee->file_pdf
            ? $employee->getFilePdfUrl()
            : null;
    }
}
