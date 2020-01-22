<?php

namespace App\Http\Controllers\AdminController;

use App\Age;
use App\CarModel;
use App\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Redirect;
use Auth;
use PHPUnit\Framework\Constraint\Count;

class CompanyController extends Controller
{
    //
    public function index()
    {


            $places = Company::orderBy('id','desc')->paginate(10);
            return view('admin.companies.index',compact('places'));

//


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //


            return view('admin.companies.create',compact('countries'));

//


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            "name"  => "required|string|max:225",
        ]);
        $places = new Company();
        $places->name= $request->name;

        $places->save();
        return redirect('admin/company');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //


            $place= Company::find($id);

            return view('admin.companies.edit',compact('place'));

//


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            "name"  => "required|string|max:225",



        ]);
        $places = Company::find($id);
        $places->name= $request->name;

        $places->save();
        return redirect('admin/company');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //


            $places = Company::findOrfail($id);

            $ages =DB::table('users')->where('company_id','=',$places->id)->get();

            if ( count($ages) == 0){
//

                $places->delete();
                return back();
            }else{

                return Redirect::back()->with('msg', 'لا تسطيع مسح الشركة المصنعة لانه مستخدم');

            }

//


    }
}
