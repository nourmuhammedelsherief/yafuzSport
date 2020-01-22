<?php

namespace App\Http\Controllers\AdminController;

use App\ContactUs;
use App\Country;
use App\Group;
use App\GroupChat;
use App\GroupMember;
use App\GroupNews;
use App\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\City;
use App\User;
use DB;
use Auth;

class Citycontroller extends Controller
{
    public function index()
    {
        $cities = City::orderBY('created_at','desc')->get();
        return view('admin.cities.index',compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('admin.cities.create' , compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "name"        => "required|string|max:255",
            "country_id"  =>'required'
        ]);
        $request['parent_id']=null;
        City::create($request->all());
        return redirect()->route('cities');
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
        $city = City::findOrfail($id);
        $countries = Country::all();
        return view('admin.cities.edit',compact('city' , 'countries'));
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
            "name"  => "required|string|max:255",
            'country_id' =>'required'
        ]);
        $cities = City::findOrfail($id);
        $cities->name = $request->name;
        $cities->country_id = $request->country_id;
        $cities->save();
        return redirect()->route('cities');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cities = City::find($id);
        $users = User::where('city_id',$id)->get();
        foreach ($users as $user)
        {
            $groups = Group::whereAdmin($user->id)->get();
            foreach ($groups as $group)
            {
                $group->delete();
            }
            $groupMembers = GroupMember::whereUser_id($user->id)->get();
            foreach($groupMembers as $groupMember)
            {
                $groupMember->delete();
            }
            $groupNews = GroupNews::whereUser_id($user->id)->get();
            foreach($groupNews as $groupNew)
            {
                $groupNew->delete();
            }
            $groupChat = GroupChat::whereUser_id($user->id)->get();
            foreach($groupChat as $groupCha)
            {
                $groupCha->delete();
            }
            $news = News::whereUser_id($user->id)->get();
            foreach($news as $new)
            {
                $new->delete();
            }
            $contacts = ContactUs::whereUser_id($user->id)->get();
            foreach($contacts as $contact)
            {
                $contact->delete();
            }
            $user->delete();
        }
        $cities->delete();
        return back();

    }
}
