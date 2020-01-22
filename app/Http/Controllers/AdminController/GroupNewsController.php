<?php

namespace App\Http\Controllers\AdminController;

use App\City;
use App\Group;
use App\GroupNews;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;

class GroupNewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groupNews = GroupNews::orderBy('created_at' , 'desc')->get();
        return view('admin.GroupNews.index' , compact('groupNews'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::all();
        $groups = Group::all();
        return  view('admin.GroupNews.create'  , compact('cities' , 'groups'));
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
            "title" => "required",
            "city_id" => "required",
            "group_id" => "required",
            "details" => "required",
            "cover_image" =>"nullable|mimes:jpeg,bmp,png,jpg|max:5000",
        ]);
        /**
         * create New @public_News
         */
        GroupNews::create([
            'title'=>$request->title,
            'city_id'=>$request->city_id,
            'group_id'=>$request->group_id,
            'details'=>$request->details,
            'user_id'=>$request->user()->id,
            'cover_image'       => $request->file('cover_image') == null ? null : UploadImage($request->file('cover_image'), 'cover_image', '/uploads/cover_images'),
        ]);
        return redirect()->route('groupNews');
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
        $groupNews = GroupNews::findOrFail($id);
        $cities = City::all();
        $groups = Group::all();
        return view('admin.GroupNews.edit' , compact('cities' , 'groups' , 'groupNews'));
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
        $groupNews = GroupNews::findOrFail($id);
        $this->validate($request , [
            "title" => "required",
            "city_id" => "required",
            "group_id" => "required",
            "details" => "required",
            "cover_image" =>"nullable|mimes:jpeg,bmp,png,jpg|max:5000",
        ]);
        /**
         * create New @public_News
         */
        $groupNews->update([
            'title'=>$request->title,
            'city_id'=>$request->city_id,
            'group_id'=>$request->group_id,
            'details'=>$request->details,
            'user_id'=>$request->user()->id,
            'cover_image'       => $request->file('cover_image') == null ? null : UploadImage($request->file('cover_image'), 'cover_image', '/uploads/cover_images'),
        ]);
        return redirect()->route('groupNews');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $groupNews = GroupNews::findOrFail($id);
        $groupNews->delete();
        return redirect()->route('groupNews');
    }
}
