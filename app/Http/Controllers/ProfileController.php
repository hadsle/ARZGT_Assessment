<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\User;
use Carbon\Carbon;
use DB;
class ProfileController extends Controller
{
    public function dashboard(){
    	return view('profile.dashboard');
    }


    public function edit_profile(){
    	$user=auth()->user();
    	$data['user']=$user;
    	return view('profile.edit_profile',$data);
    }

    public function update_profile(Request $request){

        $dt       = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();
         $user=auth()->user();

         $user->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone_number'=>$request->phone_number,
            'gender' => $request->gender,
            'department' => $request->department,
         ]);

         $activityLog = [

            'name'=>$request->name,
            'email'=>$request->email,
            'phone_number'=>$request->phone_number,
            'gender' => $request->gender,
            'department' => $request->department,
            'role'=>$request->role,
            'modify_user'  => 'Update',
            'date_time'    => $todayDate,
        ];

        DB::table('user_activity_logs')->insert($activityLog);

         return redirect()->route('edit_profile')->with('success','Profile successfully updated');

    }

    public function change_password(){ 
        return view('profile.change_password');
    }


    public function update_password(Request $request){
        $request->validate([
        'old_password'=>'required|min:6|max:100',
        'new_password'=>'required|min:6|max:100',
        'confirm_password'=>'required|same:new_password'
        ]);

        $current_user=auth()->user();

        if(Hash::check($request->old_password,$current_user->password)){

            $current_user->update([
                'password'=>bcrypt($request->new_password)
            ]);

            return redirect()->back()->with('success','Password successfully updated.');

        }else{
            return redirect()->back()->with('error','Old password does not matched.');
        }



    }

}
