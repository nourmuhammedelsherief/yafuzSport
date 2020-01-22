<?php

namespace App\Http\Controllers\AdminController;

use App\City;

use App\ContactUs;
use App\Country;
use App\Group;
use App\GroupChat;
use App\GroupMember;
use App\GroupNews;
use App\Http\Controllers\Controller;
use App\News;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;

use Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            $cities = City::all();
            $users =User::orderBy('id','desc')->get();
            return view('admin.users.index_single',compact('cities','users'));
    }

    // Active User
    public function active_user($user_id)
    {
        $user = User::find($user_id);
        $user->active = '0';
        $user->save();
        //see user groups and stop it
        $userGroups = Group::whereAdmin($user_id)->get();
        foreach ($userGroups as $userGroup)
        {
            $userGroup->active = '0';
            $userGroup->save();
        }
        return redirect()->back();
    }
    public function dispose_user($user_id)
    {
        $user = User::find($user_id);
        $user->active = '1';
        $user->save();
        $userGroups = Group::whereAdmin($user_id)->get();
        foreach ($userGroups as $userGroup)
        {
            $userGroup->active = '1';
            $userGroup->save();
        }
        return redirect()->back();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            $cities = City::all();
            $countries = Country::all();
            return view('admin.users.create_single', compact('cities' , 'countries'));
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
              "name"         => "required",
              "phone_number" => "required",
              'password'     => 'required|string|min:6|confirmed',
              "country_id"   => "required",
              "city_id"      => "required",
              "gender"       => "required",
              "image"        => "nullable|mimes:jpeg,bmp,png,jpg|max:5000",
        ]);
            // end certificate_image
            $user= User::create([
                'phone_number'  => $request->phone_number,
                'name'          => $request->name,
                'city_id'       => $request->city_id,
                'password'      => Hash::make($request->password),
                'image'         => $request->file('image') == null ? null : UploadImage($request->file('image'), 'image', '/uploads/users'),
                'gender'        => $request->gender,
                'country_id'    =>$request->country_id,
            ]);

            return redirect()->route('users');

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
        $cities = City::all();
        $countries = Country::all();
        $user = User::findOrfail($id);
        return view('admin.users.edit_single' ,compact('countries','user' , 'cities'));
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
        $this->validate($request , [
            "name"         => "required",
            "phone_number" => "required",
            "country_id"   => "required",
            "city_id"      => "required",
            "gender"       => "required",
            "image"        => "nullable|mimes:jpeg,bmp,png,jpg|max:5000",
        ]);
            User::where('id',$id)->first()->update([
                'phone_number'  => $request->phone_number,
                'name'          => $request->name,
                'city_id'       => $request->city_id,
                'image'         => $request->file('image') == null ? null : UploadImage($request->file('image'), 'image', '/uploads/users'),
                'gender'        => $request->gender,
                'country_id'    =>$request->country_id,
            ]);

            return redirect()->route('users')->with('information', 'تم تعديل بيانات المستخدم');
    }
    public function update_pass(Request $request, $id)
    {
        //
        $this->validate($request, [
            'password' => 'required|string|min:6|confirmed',

        ]);
        $users = User::findOrfail($id);
        $users->password = Hash::make($request->password);

        $users->save();

        return redirect()->back()->with('information', 'تم تعديل كلمة المرور المستخدم');
    }
    public function update_privacy(Request $request, $id)
    {
        //
        $this->validate($request, [
            'active' => 'required',

        ]);
        $users = User::findOrfail($id);
        $users->active =$request->active;

        $users->save();

        return redirect()->back()->with('information', 'تم تعديل اعدادات المستخدم');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
            $users = User::find($id);
            $groups = Group::whereAdmin($users->id)->get();
            foreach ($groups as $group)
            {
                $group->delete();
            }
            $groupMembers = GroupMember::whereUser_id($users->id)->get();
            foreach($groupMembers as $groupMember)
            {
                $groupMember->delete();
            }
            $groupNews = GroupNews::whereUser_id($users->id)->get();
            foreach($groupNews as $groupNew)
            {
                $groupNew->delete();
            }
            $groupChat = GroupChat::whereUser_id($users->id)->get();
            foreach($groupChat as $groupCha)
            {
                $groupCha->delete();
            }
            $news = News::whereUser_id($users->id)->get();
            foreach($news as $new)
            {
                $new->delete();
            }
            $contacts = ContactUs::whereUser_id($users->id)->get();
            foreach($contacts as $contact)
            {
                $contact->delete();
            }
            $users->delete();
            return back();
    }
}
