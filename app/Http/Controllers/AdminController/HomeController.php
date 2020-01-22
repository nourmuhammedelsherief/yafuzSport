<?php

namespace App\Http\Controllers\AdminController;

use App\City;
use App\Http\Controllers\Controller;
use App\Notification;
use App\User;
use App\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('users')->count();
        $admins = DB::table('admins')->count();
        $groups = DB::table('groups')->count();

        return view('admin.home' , compact('users','admins','groups'));
    }
    public function get_regions($id)
    {
        $regions = City::where('parent_id',$id)->select('id','name')->get();
        $data['regions']= $regions;
        return json_encode($data);
    }
    public function public_notifications()
    {
        return view('admin.public_notifications');
    }
    public function store_public_notifications(Request $request)
    {
        $this->validate($request , [
            "title" => "required",
            "message" => "required"
        ]);
        // Create New Notification

        $users = User::all();
       foreach ($users as $user)
       {
           $devicesTokens = UserDevice::where('user_id', $user->id)
               ->get()
               ->pluck('device_token')
               ->toArray();
           if ($devicesTokens) {
               sendMultiNotification($request->title, $request->message ,$devicesTokens);
           }

           saveNotification( $user->id, $request->title , '4', $request->message , $user->id);
       }
       return redirect()->route('public_notifications');

    }
}
