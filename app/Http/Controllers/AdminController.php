<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->input();
            $adminCount = Admin::where(['username'=>$data['username'], 'password'=>md5($data['password']), 'status'=>1])->count();
            if ($adminCount > 0) {
                // echo "Success"; die;
                Session::put('adminSession', $data['username']);
                return redirect('/admin/dashboard');
            }else {
                // echo "Failed"; die;
                return redirect('/admin')->with('flash_message_error','Invalide Username or Password');
            }
        }
        return view('admin.admin_login');
    }

    /**
     * dashboard method
     */
    public function dashboard()
    {
        // if (Session::has('adminSession')) {
        //     # Perform all dashboard task
        // }else {
        //     return redirect('/admin')->with('flash_message_error','Please Login to Access');
        // }
        return view('admin.dashboard');
    }

    public function settings()
    {
        $adminDetails = Admin::where(['username'=>Session::get('adminSession')])->first();
        return view('admin.settings', compact('adminDetails'));
    }

    /**
     * Ajax chkPassword Method
     */
    public function chkPassword(Request $request)
    {
        $data = $request->all();
        $adminCount = Admin::where(['username'=> Session::get('adminSession'), 'password'=>md5($data['current_pwd'])])->count();
        if ($adminCount == 1) {
            echo "true"; die;
        }else {
            echo "false"; die;
        }
    }

    /**
     * Update Password
     */
    public function updatePassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $adminCount = Admin::where(['username'=> Session::get('adminSession'), 'password'=>md5($data['current_pwd'])])->count();
            if ($adminCount == 1) {
                $password = md5($data['new_pwd']);
                Admin::where('username',Session::get('adminSession'))->update(['password' => $password]);
                return redirect('/admin/settings')->with('flash_message_success','Password Update Successfully');
            }else {
                return redirect('/admin/settings')->with('flash_message_error','Incorrect Current Password');
            }
        }
    }

    /**
     * Logout
     */

    public function logout()
    {
        Session::flush();
        return redirect('/admin')->with('flash_message_success','Logout Successfully');
    }

}
