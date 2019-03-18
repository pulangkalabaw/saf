<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Session;

class changePasswordController extends Controller
{
    public function changePassword(){
        return view('auth.change-password');
    }

    public function handleChangePassword(Request $request){

        $validatedData = $request->validate([
          // 'oldpass' => 'required|min:6',
          'newpassword' => 'required|string|min:6',
          'password' => 'required|same:password',
      ],[
          // 'oldpass.required' => 'Old password is required',
          // 'oldpass.min' => 'Old password needs to have at least 6 characters',
          'newpassword.required' => 'Password is required',
          'newpassword.min' => 'Password needs to have at least 6 characters',
          'password.required' => 'Passwords do not match'
      ]);

        if($request->input('newpassword') != $request->input('password')){
            Session::flash('message', "Passwords do not match");
            return view('auth.change-password');
        }

        $current_password = \Auth::User()->password;
        if($current_password){
          $user_id = \Auth::User()->id;
          $obj_user = User::find($user_id);
          $obj_user->password = \Hash::make($request->input('password'));
          $obj_user->password_status = $request->input('password_status');
          $obj_user->save();
          return redirect()->route('app.dashboard');
        }
        else{
          $data['errorMessage'] = 'Please enter correct current password';
          return redirect()->route('user.getChangePassword', $data);
        }
    }
}
