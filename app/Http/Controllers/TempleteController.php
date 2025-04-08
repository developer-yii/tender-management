<?php

namespace App\Http\Controllers;

use App\Models\Templete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TempleteController extends Controller
{
    public function index(){
        $templetes = Templete::all();
        return view('admin.templetes.index', compact('templetes'));
    }

    public function addupdate(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => trans('message.invalid-request'), 'data' => []]);
        }

        $rules = [
            'title' => 'required|unique:templetes,title,' . $request->edit_id . ',id,deleted_at,NULL',
            'templete_file' => 'nullable|max:15360',
        ];

        if (!$request->edit_id) {
            $rules = array_merge($rules, [
                'templete_file' => 'required|max:15360',
            ]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors(), 'data' => '']);
        }

        DB::beginTransaction();
        try {
            $templete = $request->edit_id ? Templete::find($request->edit_id) : new Templete();
            if (!$templete) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Templete Not Found', 'data' => []]);
            }

            $templete->title = $request->input('title');
            $templete->save();

            // $subFolderName = "templete" . $templete->id;

            $files = [
                'templete_file' => ['folder' => 'templetes', 'fileName' => 'templete'],
            ];

            if ($templete->save()) {
                $templete = fileUploadWithId($request, $templete, $files);
                DB::commit();
                $message = $request->edit_id ? 'Templete updated successfully.' : 'Templete added successfully.';
                $isNew = $request->edit_id ? false : true;
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

    public function templeteDetails(Request $request)
    {
        $templete = Templete::find($request->id);
        if(!$templete){
            return response()->json(['message' => 'Templete not found.'], 404);
        }
        $this->getFilePath($templete);
        return view('admin.templetes.details', compact('templete'));
    }

    public function detail(Request $request)
    {
        $templete = Templete::find($request->id);
        if(!$templete){
            return response()->json(['message' => 'Templete not found.'], 404);
        }

        $this->getFilePath($templete);
        return response()->json($templete);
    }

    private function getFilePath($templete)
    {
        $templete->templete_file_url = $templete->templete_file
            ? $templete->getTempleteFileUrl()
            : null;

    }
}
