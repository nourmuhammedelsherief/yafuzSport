<?php

namespace App\Http\Controllers\AdminController;

use App\Admin;
use App\Permission;
use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function my_profile()
    {

            $data = Admin::find(Auth::id());
            return view('admin.admins.profile.profile', compact('data'));

    }
    public function my_profile_edit(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:admins,email,'.Auth::id(),
//            'password' => 'required|string|min:6|confirmed',
//            'password_confirm' => 'required_with:password|same:password|min:4',
            'phone' => 'required',
        ]);
            $data = Admin::where('id',Auth::id())->update(['name'=>$request->name ,
                'email'=>$request->email,
                'phone'=>$request->phone
                ]);
        return redirect(url('/admin/profile'))->with('msg', 'تم التعديل بنجاح');

    }
    public function change_pass()
    {

        return view('admin.admins.profile.change_pass');

    }
    public function change_pass_update(Request $request)
    {
        $this->validate($request, [

            'password' => 'required|string|min:6|confirmed',

        ]);


        $updated = Admin::where('id',Auth::id())->update([
            'password'=>Hash::make($request->password)
        ]);

        return redirect(url('/admin/profileChangePass'))->with('msg', 'تم التعديل بنجاح');
    }
    public function index()
    {


            $data = Admin::all();
            return view('admin.admins.admins.index', compact('data'));

    }

    public function create()
    {

            return view('admin.admins.admins.create');

    }
    public function edit($id)
    {



            $data = Admin::find($id);
            return view('admin.admins.admins.edit', compact('data'));


    }
    public function store(Request $request)
    {
//        dd($request->role);
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:6|confirmed',
//            'password_confirm' => 'required_with:password|same:password|min:4',
            'phone' => 'required',
        ]);



        $request['remember_token'] = Str::random(60);
        $request['password'] = Hash::make($request->password);
        Admin::create($request->all());

        return redirect(url('/admin/admins'))->with('msg', 'تم الاضافه بنجاح');
    }
    public function update(Request $request, $id)
    {
//        dd($request->role);
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:admins,email,'.$id,
//            'password' => 'required|string|min:6|confirmed',
//            'password_confirm' => 'required_with:password|same:password|min:4',
            'phone' => 'required',
        ]);



        $request['remember_token'] = Str::random(60);
//        $request['password'] = Hash::make($request->password);

        Admin::where('id',$id)->first()->update($request->all());

        return redirect(url('/admin/admins'))->with('msg', 'تم التعديل بنجاح');
    }
    public function admin_delete($id)
    {



            Admin::where('id', $id)->delete();
            return back()->with('msg', 'تم الحذف بنجاح');




    }

}
