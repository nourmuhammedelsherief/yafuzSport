<?php

namespace App\Http\Controllers\AdminController;

use App\Activity;
use App\City;
use App\Group;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::orderBy('created_at' , 'desc')->get();
        return view('admin.groups.index' , compact('groups'));
    }
   public function active_group($group_id)
   {
       $group = Group::find($group_id);
       $group->active = '1';
       $group->save();
       return redirect()->back();
   }
   public function stop_group($group_id)
   {
       $group = Group::find($group_id);
       $group->active = '0';
       $group->save();
       return redirect()->back();
   }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $activities = Activity::all();
        $cities = City::orderBy('created_at' , 'desc')->get();
        $users = User::orderBy('created_at' , 'desc')->get();
        return view('admin.groups.create' , compact('activities', 'cities' , 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request , [
              "name" => "required",
              "activity_id" => "required",
              "city_id" => "required",
              "admin" => "required",
              "about_me" => "required",
              "photo" => "nullable|mimes:jpeg,bmp,png,jpg|max:5000",
        ]);
        /**
         * create new @Group
        */
        Group::create([
           'name'=>$request->name,
           'activity_id'=>$request->activity_id,
           'city_id'=>$request->city_id,
           'admin'=>$request->admin,
           'about_me'=>$request->about_me,
           'active'=>'1',
           'photo'=>$request->file('photo') == null ? null : UploadImage($request->file('photo'), 'photo', '/uploads/groupImages'),
        ]);
        return redirect()->route('groups');
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
        $group = Group::findOrFail($id);
        $activities = Activity::all();
        $cities = City::orderBy('created_at' , 'desc')->get();
        $users = User::orderBy('created_at' , 'desc')->get();
        return view('admin.groups.edit' , compact('group' , 'activities' , 'cities' , 'users'));
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
        $group = Group::findOrFail($id);
        $this->validate($request , [
            "name" => "required",
            "activity_id" => "required",
            "city_id" => "required",
            "admin" => "required",
            "about_me" => "required",
            "photo" => "nullable|mimes:jpeg,bmp,png,jpg|max:5000",
        ]);
        /**
         * create new @Group
         */
        $group->update([
            'name'=>$request->name,
            'activity_id'=>$request->activity_id,
            'city_id'=>$request->city_id,
            'admin'=>$request->admin,
            'about_me'=>$request->about_me,
            'photo'=>$request->file('photo') == null ? null : UploadImage($request->file('photo'), 'photo', '/uploads/groupImages'),
        ]);
        return redirect()->route('groups');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        $group->delete();
        return redirect()->route('groups');
    }
}
