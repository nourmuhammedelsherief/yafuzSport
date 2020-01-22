<?php

namespace App\Http\Controllers\AdminController;

use App\AboutApp;
use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Redirect;
use Image;
use Auth;
use App\Permission;

class SettingController extends Controller
{
    //
    public function index()
    {
          $settings = AboutApp::orderBy('created_at' , 'desc')->first();
          return view('admin.settings.index',compact('settings'));
    }
    public function store(Request $request)
    {
//       dd($request->all());
        $this->validate($request, [
            "address"      => "required|string|max:255",
            'email'        => 'required',
            'phone_number' => 'required|numeric',
        ]);
        AboutApp::where('id',1)->first()->update($request->all());

        return Redirect::back()->with('success', 'تم حفظ البيانات بنجاح');


    }

}
