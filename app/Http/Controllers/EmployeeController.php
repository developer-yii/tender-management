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

class EmployeeController extends Controller
{
    public function index()
    {
        $users = User::where('role', 2)->get();
        $tags = Tag::with('users')->get();
        $employeesWithoutTags = User::doesntHave('tags')->where('role', 2)->get();

        return view('admin.employee.index', compact('tags', 'employeesWithoutTags', 'users'));
    }

    public function addupdate(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => 'Invalid Request.', 'data' => []]);
        }

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|max:255|unique:users,email,' . $request->employee_id . ',id,deleted_at,NULL',
            'user_role' => 'required',
            'user_status' => 'required',
            'profile_photo' => 'nullable|image|max:2048',
            'cv' => 'nullable|mimes:pdf|max:5120',
            'document' => 'nullable|mimes:doc,docx|max:5120',
        ];

        if (!$request->employee_id) {
            $rules = array_merge($rules, [
                'password' => 'required',
                'profile_photo' => 'required|image|max:2048',
                'cv' => 'required|mimes:pdf|max:5120',
                'document' => 'required|mimes:doc,docx|max:5120',
            ]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors(), 'data' => '']);
        }

        DB::beginTransaction();

        try {
            $employee = $request->employee_id ? User::find($request->employee_id) : new User();
            if (!$employee) {
                DB::rollBack();
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

            $message = $request->employee_id ? 'Employee updated successfully.' : 'Employee added successfully.';
            $isNew = $request->employee_id ? false : true;
            return response()->json(['status' => true, 'message' => $message, 'isNew' => $isNew, 'data' => []]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Error in saving data: ' . $e->getMessage(), 'data' => []]);
        }
    }

    public function employeeDetails(Request $request)
    {
        $employee = User::with(['tags', 'tenders'])->find($request->id);
        if(!$employee){
            abort(404);
        }
        $tags = Tag::all();
        $this->getFilePath($employee);
        return view('admin.employee.details', compact('tags', 'employee'));
    }

    public function detail(Request $request)
    {
        $employee = User::with('tags')->find($request->id);
        if(!$employee){
            return response()->json(['message' => 'Employee not found.'], 404);
        }

        $this->getFilePath($employee);
        return response()->json($employee);
    }

    private function getFilePath($employee)
    {
        $employee->profile_photo_url = $employee->profile_photo
            ? $employee->getProfilePicUrl()
            : null;

        $employee->cv_url = $employee->cv
            ? $employee->getCvUrl()
            : null;

        $employee->document_url = $employee->document
            ? $employee->getDocumentUrl()
            : null;
    }
}
