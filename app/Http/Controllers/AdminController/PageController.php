<?php

namespace App\Http\Controllers\AdminController;

use App\AboutUs;
use App\Setting;
use App\TermsCondition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Redirect;
use Image;
use Auth;
use App\Permission;

class PageController extends Controller
{
    //
    public function about()
    {



        $settings =AboutUs::find(1);
        return view('admin.pages.about',compact('settings'));

//
    }
    public function store_about(Request $request)
    {
//        dd($request->deposit_type);
        $this->validate($request, [
            "title"  => "required|string|max:255",
            'content'=> 'required|string',


        ]);

        AboutUs::where('id',1)->first()->update($request->all());

        return Redirect::back()->with('success', 'تم حفظ البيانات بنجاح');


    }
    public function terms()
    {



        $settings =TermsCondition::find(1);
        return view('admin.pages.terms',compact('settings'));

//
    }
    public function store_terms(Request $request)
    {
//        dd($request->deposit_type);
        $this->validate($request, [
            "title"  => "required|string|max:255",
            'content'=> 'required|string',


        ]);

        TermsCondition::where('id',1)->first()->update($request->all());

        return Redirect::back()->with('success', 'تم حفظ البيانات بنجاح');


    }

}
