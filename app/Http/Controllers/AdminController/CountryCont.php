<?php

namespace App\Http\Controllers\AdminController;

use App\City;
use App\ContactUs;
use App\Country;
use App\Group;
use App\GroupChat;
use App\GroupMember;
use App\GroupNews;
use App\News;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Constraint\Count;

class CountryCont extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::orderBy('created_at' , 'desc')->get();
        return  view('admin.countries.index' , compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.countries.create');
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
            'name'=>'required',
        ]);
        // Create New Country
        Country::create($request->all());
        Session::flash('success', 'تم أضافه الدوله بنجاح');
        return redirect()->route('countries');
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
        $country = Country::find($id);
        return view('admin.countries.edit' , compact('country'));
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
        $country = Country::findOrfail($id);
        $this->validate($request , [
            'name'=>'required',
        ]);
        $country->name = $request->name;
        $country->save();
        Session::flash('success', 'تم تعديل الدوله بنجاح');
        return redirect()->route('countries');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $country = Country::findOrfail($id);
        $users = User::where('country_id',$id)->get();
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
        $cities = City::where('country_id',$id)->get();
        foreach ($cities as $city)
        {
            $city->delete();
        }
            $country->delete();
            return back();

    }
}
