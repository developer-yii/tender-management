<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StatusController extends Controller
{
    public function index()
    {
        return view('admin.status.index');
    }

    public function get(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => trans('message.invalid-request'), 'data' => []]);
        }

        $query = Status::query();

        return DataTables::of($query)
                ->editColumn('icon', function ($data) {
                    return $data->getIconUrl();
                })
                ->editColumn('created_at', function ($data) {
                    return formatDate($data->created_at, 'd/m/Y H:i:s');
                })
                ->editColumn('updated_at', function ($data) {
                    return formatDate($data->updated_at, 'd/m/Y H:i:s');
                })
                ->filterColumn('created_at', function($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(created_at, '%d/%m/%Y %H:%i:%s') like ?", ["%{$keyword}%"]);
                })
                ->filterColumn('updated_at', function($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(updated_at, '%d/%m/%Y %H:%i:%s') like ?", ["%{$keyword}%"]);
                })
                ->addColumn('action', function ($data) {

                    $editButton = '<a href="javascript:void(0);" class="btn btn-sm btn-info edit-status m-r-10" data-id="' . $data->id . '" data-bs-toggle="modal" data-bs-target="#addStatusModal"><i class="fa fa-edit"></i> </a>';

                    return $editButton;
                })
                ->rawColumns(['action'])
                ->toJson();

    }

    public function addupdate(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => trans('message.invalid-request'), 'data' => []]);
        }

        $rules = [
            'title' => 'required|string|max:255|unique:statuses,title,' . $request->status_id . ',id',
            'icon' => 'nullable|image|max:2048',
        ];

        if (!$request->user_id) {
            $rules = array_merge($rules, [
                'icon' => 'required|image|max:2048',
            ]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $result = ['status' => false, 'error' => $validator->errors(), 'data' => ''];
            return response()->json($result);
        }

        $status = $request->status_id ? Status::find($request->status_id) : new Status();
        if ($request->status_id && !$status) {
            return response()->json(['status' => false, 'message' => 'Status not found', 'data' => []], 404);
        }

        $status->title = $request->input('title');

        if ($request->hasFile('icon') && $request->icon){
            if($status->icon){
                Storage::delete('public/status/'.$status->icon);
            }
            $dir = "public/status/";
            $extension = $request->file("icon")->getClientOriginalExtension();
            $filename = strtolower(str_replace(' ', '-', trim($request->title))). "." . $extension;
            Storage::disk("local")->put($dir . $filename,File::get($request->file("icon")));
            $status->icon = $filename;
        }

        if ($status->save()) {
            $message = $request->status_id ? 'Status updated successfully.' : 'Status added successfully.';
            return response()->json(['status' => true, 'message' => $message, 'data' => []]);
        }

        // Handle failure in saving data
        return response()->json(['status' => false, 'message' => 'Error in saving data', 'data' => []], 500);
    }

    public function detail(Request $request)
    {
        $status = Status::find($request->id);
        if (!$status) {
            return response()->json(['status' => false, 'message' => 'Status not found', 'data' => []], 404);
        }
        return response()->json($status);
    }
}
