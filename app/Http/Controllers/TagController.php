<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TagController extends Controller
{
    public function index()
    {
        return view('admin.tags.index');
    }

    public function get(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => 'Invalid Request.', 'data' => []]);
        }

        $data = Tag::all();

        return DataTables::of($data)
                ->addColumn('action', function ($data) {

                    $editButton = '<a href="javascript:void(0);" class="btn btn-sm btn-primary edit-tag m-r-10" data-id="' . $data->id . '" data-bs-toggle="modal" data-bs-target="#addTagModal"><i class="fa fa-edit"></i> </a>';

                    $deleteButton = '<a href="javascript:void(0);" class="btn btn-sm btn-danger delete-tag" data-id="' . $data->id . '" title="Delete"><i class="fa fa-trash"></i></a>';

                    $actionButtons = $editButton . $deleteButton;

                    return $actionButtons;

                })
                ->rawColumns(['action'])
                ->toJson();

    }

    public function addupdate(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => 'Invalid Request.', 'data' => []]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:tags,name,' . $request->tag_id . ',id,deleted_at,NULL',
        ]);

        if ($validator->fails()) {
            $result = ['status' => false, 'error' => $validator->errors(), 'data' => ''];
            return response()->json($result);
        }

        $tag = $request->tag_id ? Tag::find($request->tag_id) : new Tag();
        if ($request->tag_id && !$tag) {
            return response()->json(['status' => false, 'message' => 'Tag not found', 'data' => []], 404);
        }

        $tag->name = $request->input('name');

        if ($tag->save()) {
            $message = $request->tag_id ? 'Tag updated successfully.' : 'Tag added successfully.';
            return response()->json(['status' => true, 'message' => $message, 'data' => []]);
        }

        // Handle failure in saving data
        return response()->json(['status' => false, 'message' => 'Error in saving data', 'data' => []], 500);
    }

    public function detail(Request $request)
    {
        $tag = Tag::find($request->id);
        if (!$tag) {
            return response()->json(['status' => false, 'message' => 'Tag not found', 'data' => []], 404);
        }
        return response()->json($tag);
    }

    public function delete(Request $request)
    {
        try {
            $tag = Tag::find($request->id);
            if (!$tag) {
                return response()->json(['status' => false, 'message' => 'Tag not found', 'data' => []], 404);
            }

            $tag->delete();
            return response()->json(['status' => true, 'message' => 'Tag deleted successfully!', 'data' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to delete the tag.', 'data' => []], 500);
        }
    }
}
