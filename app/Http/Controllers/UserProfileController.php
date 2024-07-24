<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class UserProfileController extends Controller
{
    public function profile(){
        $auth = Auth::user();
        return view('main.profile', compact('auth'));
    }

    //create function uplaod to deal with the image upload with javascript from profile.blade.php
    public function upload(Request $request){
        //uplaod  the image upload with javascript from profile.blade.php
        if($request->hasFile('image')){
            $photo = $request->file('image');

            $fileName = time().'_'. Auth::user()->id.".".$photo->getClientOriginalExtension();
            $path = $request->file('image')->move(public_path('profile-photos'),$fileName);
            $user = User::find(Auth::user()->id);
            $user->profile = $fileName;
            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'image has been uploaded to '.$fileName,
            ],200);
        }else{
            return response()->json([
                'message' => 'somthing is wrong',
            ],400);
        }
    }
    //create a passwordChange function to change the password
    public function changePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'oldpass' => 'required|current_password',
            'newpass' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update the user's password
        User::find(Auth::user()->id)->update(['password' => Hash::make($request->newpass)]);

        return redirect()->back()->with('pass-success', 'Password changed successfully.');
    }
    public function changeUsername(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:4|unique:users,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update the user's password
        User::find(Auth::user()->id)->update(['name' => $request->username]);

        return redirect()->back()->with('name-success', 'username changed successfully.');
    }

}
