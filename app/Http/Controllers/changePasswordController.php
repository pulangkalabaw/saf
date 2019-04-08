<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\User;
use Session;
use Mail;
use DB;
use Carbon;
use Auth;



class changePasswordController extends Controller
{
    // CHANGE PASSWORD VIEW
    public function changePassword(){
        return view('auth.change-password');
    }

    //CHANGE PASSWORD FUNCTION
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
            if(Auth::user()->role == base64_encode('encoder')){
                return redirect()->route('app.encoder-dashboard');
            } else {
                return redirect()->route('app.dashboard');
            }
        }
        else{
          $data['errorMessage'] = 'Please enter correct current password';
          return redirect()->route('user.getChangePassword', $data);
        }
    }

    //FORGOT PASSWORD VIEW
    public function forgotPassword(){

        return view('auth.forgot-password');
    }

    //RESET PASSWORD FUNCTION
    public function passwordReset(Request $request){
        $email = User::where('email', $request->email)->first();

        if($request->email != $email['email']){
            Session::flash('message', "Woops Invalid Email Address");
            return back();
        }else{

             //create a new token to be sent to the user.
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => str_random(60), //change 60 to any length you want
                'created_at' => \Carbon\Carbon::now()
            ]);

            $tokenData = DB::table('password_resets')
            ->where('email', $request->email)->first();

            $token = $tokenData->token;
            $email = $request->email;
            Session::flash('success', "Password link has been sent");
            $data = array('token'=> $token, "body" => "Test mail", 'to' => $request['email'] );
            Mail::send('auth.reset-password-link', $data, function($message) use ($data) {
                $message->to($data['to'], 'Artisans Web')
                        ->subject('Reset Password Link');
                $message->from('jdela4460@gmail.com','SAF');
            });

            return back();
        }

    }

    public function getnewPassword($token){
        $tokenData = DB::table('password_resets')
         ->where('token', $token)->first();

         if ( !$tokenData ) return view('errors.404'); //redirect them anywhere you want if the token does not exist.
        return view('auth.set-new-password', ['token' => $token]);
    }


    public function setnewPassword(Request $request, $token){

        $password = $request->password;
        $confirm_password = $request->confirm_password;
        if($password != $confirm_password){
            Session::flash('message', "Passwords do not match");
            return back();
        }else{
            $tokenData = DB::table('password_resets')
            ->where('token', $token)->first();
            $user = User::where('email', $tokenData->email)->first();
            if ( !$user ) return redirect()->back(); //or wherever you want
            $user->password = \Hash::make($password);
            $user->password_status = $request->password_status;
            $user->update(); //or $user->save();

            //do we log the user directly or let them login and try their password for the first time ? if yes
            Auth::login($user);

            // If the user shouldn't reuse the token later, delete the token
            DB::table('password_resets')->where('email', $user->email)->delete();
            //redirect where we want according to whether they are logged in or not.
            // Session::flash('message', "Your password has been resseted successfully please login");
            return redirect()->route('login');
        }

    }

    public function reset(){
        return view('auth.reset-password-link');
    }
}
