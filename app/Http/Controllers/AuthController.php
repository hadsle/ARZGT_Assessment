<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Mail;
use App\Mail\EmailVerificationMail;
use App\PasswordReset;
use App\Mail\ForgetPasswordMail;
use Carbon\Carbon;
class AuthController extends Controller
{
    // This method returns a view for the user registration form.
    public function getRegister(){
    	return view('auth.register');
    }

    // This method checks if the entered email is unique or not.
    // It takes a Request object as a parameter and uses the User model to query the database to find a user with the same email.
    // If found, it echoes 'false', and if not, it echoes 'true'.
    public function check_email_unique(Request $request){
    	$user=User::where('email',$request->email)->first();
    	if($user){
    		echo 'false';
    	}else{
    		echo 'true';
    	}
    }

    // This method handles the registration form submission.
    public function postRegister(Request $request){
        // Validate the form data using Laravel's built-in validation rules.
        $request->validate([
            'name'=>'required|string|min:2|max:100',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|max:100',
            'confirm_password'=>'required|same:password',
            'terms'=>'required',
            'grecaptcha'=>'required'
         ],[
            'first_name.required'=>'First name is required',
            'last_name.required'=>'Last name is required',
         ]);

        // Verify the user's reCAPTCHA response using Google's API.
        $grecaptcha=$request->grecaptcha;
        $client = new Client();
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            ['form_params'=> [
                    'secret'=>env('GOOGLE_CAPTCHA_SECRET'),
                    'response'=>$grecaptcha
                ]
            ]
        );
        $body = json_decode((string)$response->getBody());
      
        // If the reCAPTCHA response is valid, create a new user in the database and send an email verification link to the user.
        if($body->success==true){
            $user=User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>bcrypt($request->password),
                'phone_number'=>$request->phone_number,
                'gender' => $request->gender,
                'department' => $request->department,
                'role' => $request->role,
                'email_verification_code'=>Str::random(40)
            ]);
            Mail::to($request->email)->send(new EmailVerificationMail($user));
            return redirect()->back()->with('success','Registration successfull.Please check your email address for email verification link.');
        }
        // If the reCAPTCHA response is invalid, redirect the user back to the registration form with an error message.
        else{
            return redirect()->back()->with('error','Invalid recaptcha');
        }
    }

    // This method verifies the user's email address using a unique verification code sent to the user's email.
    public function verify_email($verification_code){
        $user=User::where('email_verification_code',$verification_code)->first();
        if(!$user){
            return redirect()->route('getRegister')->with('error','Invalid URL');
        }else{
            if($user->email_verified_at){
                return redirect()->route('getRegister')->with('error','Email already verified');
            }else{
                $user->update([
                    'email_verified_at'=>\Carbon\Carbon::now()
                ]);
                return redirect()->route('getRegister')->with('success','Email successfully verified');
            }
        }
    }

    // This method returns a view for the user login form.
    public function getLogin(){
        return view('auth.login');
    }

    // This method handles the user login form submission.
    public function postLogin(Request $request){
        // Validate the form data using Laravel's built-in validation rules.
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:6|max:100',
            'grecaptcha'=>'required'
        ]);

        // Verify the user's reCAPTCHA response using Google's API.
        $grecaptcha=$request->grecaptcha;
        $client = new Client();
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            ['form_params'=> [
                    'secret'=>env('GOOGLE_CAPTCHA_SECRET'),
                    'response'=>$grecaptcha
                ]
            ]
        );
        $body = json_decode((string)$response->getBody());

        // If the reCAPTCHA response is valid, attempt to log the user in using Laravel's built-in auth system.
        if($body->success==true){
            $user=User::where('email',$request->email)->first();
            if(!$user){
                return redirect()->back()->with('error','Email is not registered');
            }else{
                if(!$user->email_verified_at){
                    return redirect()->back()->with('error','Email is not verified');
                }else{
                    if(!$user->is_active){
                        return redirect()->back()->with('error','User is not active. Contact admin');
                    }else{
                        $remember_me=($request->remember_me)?true:false;
                        if(auth()->attempt($request->only('email','password'),$remember_me)){
                            return redirect()->route('dashboard')->with('success','Login successfull');
                        }else{
                            return redirect()->back()->with('error','Invalid credentials');
                        }
                    }
                }
            }
        }
        // If the reCAPTCHA response is invalid, redirect the user back to the login form with an error message.
        else{
            return redirect()->back()->with('error','Invalid recaptcha');
        }
    }

    // This method logs the user out.
    public function logout(){
        auth()->logout();
        return redirect()->route('getLogin')->with('success','Logout successfull');
    }

    // This method returns a view for the forgot password form.
    public function getForgetPassword(){
        return view('auth.forget_password');
    }

    // This method handles the forgot password form submission.
    public function postForgetPassword(Request $request){
        // Validate the form data using Laravel's built-in validation rules.
        $request->validate([
            'email'=>'required|email'
        ]);

        // Find the user with the given email and generate a unique password reset code for the user.
        $user=User::where('email',$request->email)->first();
        if(!$user){
            return redirect()->back()->with('error','User not found.'); 
        }else{
            $reset_code=Str::random(200);
            PasswordReset::create([
                'user_id'=>$user->id,
                'reset_code'=>$reset_code
            ]); 

            // Send an email to the user with a link to reset their password.
            Mail::to($user->email)->send(new ForgetPasswordMail($user->first_name,$reset_code));
            return redirect()->back()->with('success','We have sent you a password reset link. Please check your email.');
        }
    }

    // This method returns a view for the password reset form.
    public function getResetPassword($reset_code){
        // Find the password reset data with the given reset code and ensure that the link has not expired.
        $password_reset_data=PasswordReset::where('reset_code',$reset_code)->first();
        if(!$password_reset_data || Carbon::now()->subMinutes(10)> $password_reset_data->created_at){
            return redirect()->route('getForgetPassword')->with('error','Invalid password reset link or link expired.');
        }else{
            // If the link is valid, return a view for the password reset form.
            return view('auth.reset_password',compact('reset_code'));
        }
    }

    // This method handles the password reset form submission.
    public function postResetPassword($reset_code, Request $request){
        // Find the password reset data with the given reset code and ensure that the link has not expired.
        $password_reset_data=PasswordReset::where('reset_code',$reset_code)->first();
        if(!$password_reset_data || Carbon::now()->subMinutes(10)> $password_reset_data->created_at){
            return redirect()->route('getForgetPassword')->with('error','Invalid password reset link or link expired.');
        }else{
            // If the link is valid, validate the form data using Laravel's built-in validation rules.
            $request->validate([
                'email'=>'required|email',
                'password'=>'required|min:6|max:100',
                'confirm_password'=>'required|same:password',
            ]);

            // Find the user with the given email and update their password.
            $user=User::find($password_reset_data->user_id);
            if($user->email!=$request->email){
                return redirect()->back()->with('error','Enter correct email.');
            }else{
                $password_reset_data->delete();
                $user->update([
                    'password'=>bcrypt($request->password)
                ]);
                return redirect()->route('getLogin')->with('success','Password successfully reset. ');
            }
        }
    }
}
