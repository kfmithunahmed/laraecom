<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;
use App\User;
use Auth;
use Illuminate\Support\Facades\DB;
use Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    
    public function userLoginRegister()
    {
        $meta_title = "User Login / Register - E-com Website";
        return view('users.login_register', compact('meta_title'));
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
                $userStatus = User::where('email',$data['email'])->first();
                if ($userStatus->status == 0) {
                    return redirect()->back()->with('flash_message_error', 'Your account is not activated ! Please Confirm your email to Activate.');
                }
                Session::put('frontSession', $data['email']);

                if (!empty(Session::get('session_id'))) {
                    $session_id = Session::get('session_id');
                    DB::table('cart')->where('session_id',$session_id)->update(['user_email' => $data['email']]);
                }

                return redirect('/cart');
            }else {
                return redirect()->back()->with('flash_message_error', 'Invalid Username or Password');
            }
        }
    }

    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            // check if user already exists
            $usersCount = User::where('email', $data['email'])->count();
            if ($usersCount > 0) {
                return redirect()->back()->with('flash_message_error', 'Email already exists !');
            }else {
                $user = new User;
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = bcrypt($data['password']);
                $user->save();

                /*// Send Register Email
                $email = $data['email'];
                $messageData = ['email'=>$data['email'],'name'=>$data['name']];
                Mail::send('emails.register',$messageData,function($message) use($email){
                    $message->to($email)->subject('Registration with E-com Website');
                });*/
                
                // Send Confirm Email
                $email = $data['email'];
                $messageData = ['email'=>$data['email'],'name'=>$data['name'],'code'=>base64_encode($data['email'])];
                Mail::send('emails.confirmation',$messageData,function($message) use($email){
                    $message->to($email)->subject('Registration with E-com Website');
                });
                return redirect()->back()->with('flash_message_success', 'Please confirm your email to active your Account !');


                if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {                    
                    Session::put('frontSession', $data['email']);

                    if (!empty(Session::get('session_id'))) {
                        $session_id = Session::get('session_id');
                        DB::table('cart')->where('session_id',$session_id)->update(['user_email' => $data['email']]);
                    }
                    return redirect('/cart');
                }
            }
        }
    }

    public function forgotPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $userCount = User::where('email',$data['email'])->count();
            if ($userCount == 0) {
                return redirect()->back()->with('flash_message_error', 'Email does not exists');
            }
            
            //  Get User Details
            $userDetails = User::where('email',$data['email'])->first();

            // Generate Random Password
            $random_password = str_random(8);

            // Encode/Secure Password
            $new_password = bcrypt($random_password);

            // Update Password
            User::where('email',$data['email'])->update(['password'=>$new_password]);

            // Send Forget Password Email Code
            $email = $data['email'];
            $name = $userDetails->name;
            $messageData = [
                'email'=>$email,
                'name'=>$name,
                'password'=>$random_password
            ];
            Mail::send('emails.forgotpassword',$messageData, function($message) use($email){
                $message->to($email)->subject('New Password - E-com Website');
            });
            return redirect('login-register')->with('flash_message_success','Please check your email for new password');
        }
        return view('users.forgot_password');
    }

    public function confirmAccount($email)
    {
        $email = base64_decode($email);
        $userCount = User::where('email', $email)->count();
        if ($userCount > 0) {
            $userDetails = User::where('email',$email)->first();
            if ($userDetails->status == 1) {
                return redirect('login-register')->with('flash_message_success', 'Your Email Account is already activated. You can login now.');
            }else {
                User::where('email',$email)->update(['status'=>'1']);

                // Send Welcome Email
                $messageData = ['email'=>$email,'name'=>$userDetails->name];
                Mail::send('emails.welcome',$messageData,function($message) use($email){
                    $message->to($email)->subject('Wellcome to E-com Website');
                });
                return redirect('login-register')->with('flash_message_success', 'Your Email Account is already activated. You can login now.');
            }
        }else {
            abort(404);
        }
    }

    public function account(Request $request)
    {
        $user_id = Auth::user()->id;
        $userDetails = User::find($user_id);
        $countries = Country::get();

        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            if (empty($data['name'])) {
                return redirect()->back()->with('flash_message_error', 'Please enter your name to update your account details !');
            }
            if (empty($data['address'])) {
                $data['address'] = '';
            }
            if (empty($data['city'])) {
                $data['city'] = '';
            }
            if (empty($data['state'])) {
                $data['state'] = '';
            }
            if (empty($data['country'])) {
                $data['country'] = '';
            }
            if (empty($data['pincode'])) {
                $data['pincode'] = '';
            }
            if (empty($data['mobile'])) {
                $data['mobile'] = '';
            }

            $user = User::find($user_id);
            $user->name = $data['name'];
            $user->address = $data['address'];
            $user->city = $data['city'];
            $user->state = $data['state'];
            $user->country = $data['country'];
            $user->pincode = $data['pincode'];
            $user->mobile = $data['mobile'];
            $user->save();
            return redirect()->back()->with('flash_message_success', 'Your Account details has been Updated Successfully !!!');
        }
        return view('users.account', compact('countries','userDetails'));
    }

    public function chkUserPassword(Request $request)
    {
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        $current_password = $data['current_pwd'];
        $user_id = Auth::User()->id;
        $check_password = User::where('id',$user_id)->first();
        if (Hash::check($current_password, $check_password->password)){
            echo "true"; die;
        }else {
            echo "false"; die;
        }
    }

    public function updatePassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $old_pwd = User::where('id', Auth::User()->id)->first();
            $current_pwd = $data['current_pwd'];
            if (Hash::check($current_pwd, $old_pwd->password)) {
                // Update Password
                $new_pwd = bcrypt($data['new_pwd']);
                User::where('id', Auth::User()->id)->update(['password' => $new_pwd]);
                return redirect()->back()->with('flash_message_success','Password Updated Successfully');
            }else {
                return redirect()->back()->with('flash_message_error','Current Password is Incorrect');
            }
        }
    }

    public function logout()
    {
        Auth::logout();
        Session::forget('frontSession');
        Session::forget('Session_id');
        return redirect('/');
    }

    public function checkEmail(Request $request)
    {
        // Check if user already exists
        $data = $request->all();
        $usersCount = User::where('email', $data['email'])->count();
        if ($usersCount > 0) {
            echo "false";
        }else {
            echo "true"; die;
        }
    }

    public function viewUsers()
    {
        $users = User::get();
        return view('admin.users.view_users', compact('users'));
    }



}