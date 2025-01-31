<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 1)
                  ->where('id', '!=', Auth::id())
                  ->get();
        return view('admin.employee.admin-list', compact('admins'));
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
            'status' => 'required',
            'profile_photo' => 'nullable|image|max:2048',
        ];

        if (!$request->user_id) {
            $rules = array_merge($rules, [
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
                'profile_photo' => 'required|image|max:2048',
            ]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors(), 'data' => '']);
        }

        DB::beginTransaction();

        try {
            $user = $request->user_id ? User::find($request->user_id) : new User();
            if (!$user) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Admin Not Found', 'data' => []]);
            }

            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
            $user->description = $request->input('description');
            $user->is_active = $request->input('status');
            $user->role = 1;
            if ($request->input('password')){
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $subFolderName = "admin" . $user->id;

            $files = [
                'profile_photo' => ['multiple' => false, 'folder' => 'admins', 'subFolder' => $subFolderName],
            ];

            $user = handleFileUploads($request, $user, $files);
            $user->save();
            DB::commit();

            $message = $request->user_id ? 'Admin updated successfully.' : 'Admin added successfully.';
            $isNew = $request->user_id ? false : true;
            return response()->json(['status' => true, 'message' => $message, 'isNew' => $isNew, 'data' => []]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Error in saving data: ' . $e->getMessage(), 'data' => []]);
        }
    }

    public function detail(Request $request)
    {
        $admin = User::find($request->id);
        if(!$admin){
            return response()->json(['message' => 'Admin not found.'], 404);
        }

        $this->getFilePath($admin);
        return response()->json($admin);
    }

    private function getFilePath($admin)
    {
        $admin->profile_photo_url = $admin->profile_photo
            ? $admin->getAdminProfilePicUrl()
            : null;

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $admin = User::find($request->id);
            if (!$admin) {
                return response()->json(['message' => 'Admin not found.'], 404);
            }
            $folder = "admins";
            $subFolder = "admin".$admin->id;

            $directoryPath = $folder . '/' . $subFolder;

            if (Storage::disk('public')->exists($directoryPath)) {
                Storage::disk('public')->deleteDirectory($directoryPath);
            }
            $admin->delete();

            DB::commit();
            return response()->json(['message' => 'Admin deleted successfully!']);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred while deleting the admin.'], 500);
        }
    }


}
