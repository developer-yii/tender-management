<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Traits\HasFilePath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use HasFilePath;

    public function myProfile()
    {
        $user = Auth::user();
        $user->load(['tags', 'tenders']);
        $this->getFilePath($user);
        return view('admin.profile.index',['user' => $user]);
    }

    public function edit()
    {
        $user = Auth::user();
        $user->load(['tags', 'tenders']);
        $this->getFilePath($user);
        $tags = Tag::with('users')->get();
        return view('admin.profile.edit', compact('tags', 'user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => trans('message.invalid-request'), 'data' => []]);
        }

        $rules = [
            'name' => 'required',
        ];

        // Apply email validation only if user_type is 1 or 2
        if (in_array($user->user_type, [1, 2])) {
            $rules['email'] = "required|email:rfc,dns|unique:users,email,{$user->id},id";
        }

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            $result = ['status' => false, 'message' => $validator->errors(), 'data' => []];
        }

        $user->name = $request->name;
        // $user->email = $request->email;
        $user->email = $request->input('email', $user->email);

        if($user->save()){
            $result = ['status' => true, 'message' => 'Profile update successfully.', 'data' => []];
        }else{
            $result = ['status' => false, 'message' => 'Profile update fail!', 'data' => []];
        }
        return response()->json($result);
    }
}
