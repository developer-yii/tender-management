<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\HasFilePath;

class EmployeeController extends Controller
{
    use HasFilePath;

    public function index()
    {
        $users = User::where('role', 2)->get();
        $tags = Tag::with('users')->get();
        $employeesWithoutTags = User::doesntHave('tags')->where('role', 2)->get();

        return view('admin.employee.index', compact('tags', 'employeesWithoutTags', 'users'));
    }

    public function addEdit(Request $request)
    {
        $employee = null;
        if ($request->id) {
            $employee = User::with('tags')->find($request->id);

            if(!$employee){
                abort(404);
            }

            $this->getFilePath($employee);
        }

        $tags = Tag::with('users')->get();
        return view('admin.employee.create', compact('tags', 'employee'));
    }

    public function addupdate(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => trans('message.invalid-request'), 'data' => []]);
        }

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|max:255|unique:users,email,' . $request->user_id . ',id,deleted_at,NULL',
            'profile_photo' => 'nullable|image|max:2048',
            'cv' => 'nullable|mimes:pdf|max:15360',
            'document' => 'nullable|mimes:doc,docx|max:15360',
        ];

        if (!$request->user_id) {
            $rules = array_merge($rules, [
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
                'profile_photo' => 'required|image|max:2048',
                'cv' => 'required|mimes:pdf|max:15360',
                'document' => 'required|mimes:doc,docx|max:15360',
            ]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors(), 'data' => '']);
        }

        DB::beginTransaction();

        try {
            $employee = $request->user_id ? User::find($request->user_id) : new User();
            if (!$employee) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => trans('message.employee-not-found'), 'data' => []]);
            }

            $employee->first_name = $request->input('first_name');
            $employee->last_name = $request->input('last_name');
            $employee->email = $request->input('email');
            $employee->description = $request->input('description');
            if ($request->input('password')){
                $employee->password = Hash::make($request->password);
            }
            $employee->save();

            $subFolderName = "employee" . $employee->id;

            $files = [
                'profile_photo' => ['multiple' => false, 'folder' => 'employees', 'subFolder' => $subFolderName],
                'cv' => ['multiple' => false, 'folder' => 'employees','subFolder' => $subFolderName],
                'document' => ['multiple' => false, 'folder' => 'employees', 'subFolder' => $subFolderName],
            ];

            $employee = handleFileUploads($request, $employee, $files);
            $employee->save();
            $employee->tags()->sync($request->input('tags'));
            DB::commit();

            $message = $request->user_id
            ? trans('message.Employee updated successfully')
            : trans('message.Employee added successfully');

            $isNew = $request->user_id ? false : true;
            return response()->json(['status' => true, 'message' => $message, 'isNew' => $isNew, 'data' => []]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Fehler beim Speichern der Daten.: ' . $e->getMessage(), 'data' => []]);
        }
    }

    public function employeeDetails(Request $request)
    {
        $employee = User::with(['tags', 'tenders', 'tenders.tenderStatus'])->find($request->id);
        if(!$employee){
            abort(404);
        }
        $tags = Tag::all();
        $this->getFilePath($employee);
        return view('admin.employee.details', compact('tags', 'employee'));
    }

}
