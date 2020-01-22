<?php

namespace App\Http\Controllers\AdminController;

use App\City;
use App\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function GuzzleHttp\Promise\all;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::orderBy('created_at' , 'desc')->get();
        return view('admin.news.index' , compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::orderBy('created_at' , 'desc')->get();
        return view('admin.news.create', compact('cities'));
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
              "details" => "required",
              "cover_image" =>"nullable|mimes:jpeg,bmp,png,jpg|max:5000",
        ]);
        /**
         * create New @public_News
        */
        News::create([
            'title'=>$request->title,
            'city_id'=>$request->city_id,
            'details'=>$request->details,
            'user_id'=>$request->user()->id,
            'cover_image'       => $request->file('cover_image') == null ? null : UploadImage($request->file('cover_image'), 'cover_image', '/uploads/cover_images'),
        ]);
        return redirect()->route('news');
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
        $news = News::findOrFail($id);
        $cities = City::orderBy('created_at' , 'desc')->get();
        return view('admin.news.edit' , compact('news' , 'cities'));
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
        $news = News::findOrFail($id);
        $this->validate($request , [
            "title" => "required",
            "city_id" => "required",
            "details" => "required",
            "cover_image" =>"nullable|mimes:jpeg,bmp,png,jpg|max:5000",
        ]);
        /**
         * create New @public_News
         */
        $news->update([
            'title'=>$request->title,
            'city_id'=>$request->city_id,
            'details'=>$request->details,
            'cover_image' => $request->file('cover_image') == null ?  : UploadImage($request->file('cover_image'), 'cover_image', '/uploads/cover_images'),
        ]);
        return redirect()->route('news');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();
        return redirect()->route('news');
    }
}
