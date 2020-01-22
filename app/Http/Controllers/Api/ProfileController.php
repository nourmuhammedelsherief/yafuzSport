<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Validator;
use App\User;
use App;
use Auth;

class ProfileController extends Controller
{
    //

    public function activities()
    {
        $activities = App\Activity::all();
        $data =[];
        array_push($data,
            [
                'activities' =>$activities,
            ]);
        return ApiController::respondWithSuccess($data);
    }
    public function app_contact_info()
    {
        $app_contact_info = App\AboutApp::orderBy('created_at' , 'desc')->first();
        $data =[];
        array_push($data,
            [
                'app_contact_info' =>$app_contact_info,
            ]);
        return ApiController::respondWithSuccess($data);
    }
    public function about_us()
    {
        $about = App\AboutUs::first();
        $all=[
            'title'=>$about->title,
            'content'=>$about->content,
        ];
        return ApiController::respondWithSuccess($all);
    }
    public function terms_and_conditions()
    {
        $terms = App\TermsCondition::first();
        $all=[
            'title'=>$terms->title,
            'content'=>$terms->content,
        ];
        return ApiController::respondWithSuccess($all);
    }

}
