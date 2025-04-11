<?php

namespace App\Http\Controllers;

use App\Models\Abgabeform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AbgabeformController extends Controller
{
    public function index()
    {
        return view('admin.abgabeform.index');
    }

    public function get(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => trans('message.invalid-request'), 'data' => []]);
        }

        $query = Abgabeform::query();

        return DataTables::of($query)
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

                    $editButton = '<a href="javascript:void(0);" class="btn btn-sm btn-info edit-abgabeform m-r-10" data-id="' . $data->id . '" data-bs-toggle="modal" data-bs-target="#addAbgabeformModal"><i class="fa fa-edit"></i> </a>';
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

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:abgabeforms,name,' . $request->abgabeform_id,
        ]);

        if ($validator->fails()) {
            $result = ['status' => false, 'error' => $validator->errors(), 'data' => ''];
            return response()->json($result);
        }

        $abgabeform = $request->abgabeform_id ? Abgabeform::find($request->abgabeform_id) : new Abgabeform();
        if ($request->abgabeform_id && !$abgabeform) {
            return response()->json(['status' => false, 'message' => 'Abgabeform nicht gefunden.', 'data' => []], 404);
        }

        $abgabeform->name = $request->input('name');

        if ($abgabeform->save()) {
            $message = $request->abgabeform_id ? 'Abgabeform erfolgreich aktualisiert.' : 'Abgabeform erfolgreich hinzugefügt.';
            return response()->json(['status' => true, 'message' => $message, 'data' => []]);
        }

        // Handle failure in saving data
        return response()->json(['status' => false, 'message' => 'Fehler beim Speichern der Daten', 'data' => []], 500);
    }

    public function detail(Request $request)
    {
        $abgabeform = Abgabeform::find($request->id);
        if (!$abgabeform) {
            return response()->json(['status' => false, 'message' => 'Abgabeform nicht gefunden.', 'data' => []], 404);
        }
        return response()->json($abgabeform);
    }  
}
