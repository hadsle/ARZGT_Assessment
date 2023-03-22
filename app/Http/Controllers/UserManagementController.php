<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use DB;
use App\User;
use Illuminate\Support\Str;
use Auth;
use App\Mail\EmailVerificationMail;
use App\PasswordReset;
use App\Mail\ForgetPasswordMail;
use Carbon\Carbon;
use Session;
use Mail;


class UserManagementController extends Controller
{
    public function index()
    {
        if (Auth::user()->role=='admin')
        {
            $data = DB::table('users')->get();
            return view('profile.user_control',compact('data'));
        }
        else
        {
            return redirect()->route('home');
        }
        
    }
    // view detail 
    public function edit_profile($id){
    	
    	$data = DB::table('users')->where('id',$id)->get();
    	return view('profile.view_details',compact('data'));
    }
    
    public function profile()
    {
        return view('usermanagement.profile_user');
    }
   
    // add new user
    public function addNewUser()
    {
        return view('profile.addNew');
    }

     // save new user
     public function newUserSave(Request $request){
    	
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
    
            $grecaptcha=$request->grecaptcha;
    
            $client = new Client();
    
            $response = $client->post(
                'https://www.google.com/recaptcha/api/siteverify',
                ['form_params'=>
                    [
                        'secret'=>env('GOOGLE_CAPTCHA_SECRET'),
                        'response'=>$grecaptcha
                     ]
                ]
            );
          
            $body = json_decode((string)$response->getBody());
    
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
              
            }else{
                return redirect()->back()->with('error','Invalid recaptcha');
            }
    
    
        }
        public function delete($id)
        {
    

            $user = Auth::User();
            Session::put('user', $user);
            $user=Session::get('user');
    
            $name     = $user->name;
            $email        = $user->email;
            $phone_number = $user->phone_number;
            $role      = $user->role;
            $gender   = $user->gender;
            $department =$user->department;
    
            $dt       = Carbon::now();
            $todayDate = $dt->toDayDateTimeString();
    

            $activityLog = [

                'name'=>$name,
                'email'=>$email,
                'phone_number'=>$phone_number,
                'gender' => $gender,
                'department' => $department,
                'role'=>$role,
                'modify_user'  => 'Delete',
                'date_time'    => $todayDate,
            ];
            $delete = User::find($id);
            $delete->delete();
            DB::table('user_activity_logs')->insert($activityLog);
            return redirect()->route('userManagement');
        }
    
 









    }