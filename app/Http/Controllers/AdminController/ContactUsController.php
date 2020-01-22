<?php

namespace App\Http\Controllers\AdminController;

use App\ContactUs;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $contacts = ContactUs::orderBy('created_at' , 'desc')->get();
         return view('admin.contacts.index' , compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contactU  = ContactUs::findOrFail($id);
         return view('admin.contacts.show' , compact('contactU'));
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
    }
    public function replay(Request $request , $id)
    {
        $contact = ContactUs::find($id);
        dd($contact);
        $contact->replay = $request->replay;
        $contact->save();
    }
    public function AgreeToPost($id)
    {
        $contact = ContactUs::find($id);
        $user = User::whereId($contact->user_id)->first();
        $user->posts = '1';
        $user->save();
        return redirect()->back();
    }
    public function deleteContact($id)
    {
        $contact = ContactUs::find($id);
        $contact->delete();
        return redirect()->route('contact_us');
    }
}
