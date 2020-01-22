<?php

namespace App\Http\Controllers\Api;

use App\Country;
use App;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DetailsController extends Controller
{
    //

    public function school(Request $request){


        $schools = App\School::select('id','name')->get();

        return ApiController::respondWithSuccess($schools);
    }
    public function settings()
    {



        $about = App\Setting::first();
        $all=[
            'bank_name'=>$about->bank_name,
            'account_number'=>$about->account_number,
            'phone_number'=>$about->phone_number,
        ];


        return ApiController::respondWithSuccess($all);
    }
//    public  function all(Request $request){
//        App\Job::create(['name'=>serialize($request->name)]);
//    }

    public function refreshDeviceToken($lang,Request $request)
    {
        App::setLocale($lang);
        $rules = [
            'device_token'      => 'required',
            'device_type'     => 'required',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
        }

        $created = ApiController::createUserDeviceToken($request->user()->id, $request->device_token, $request->device_type);


            return $created
                ? ApiController::respondWithSuccess([])
                : ApiController::respondWithServerErrorArray();
    }
//    public function refreshToken($lang,Request $request)
//    {
//        App::setLocale($lang);
//        $rules = [
//            'old_token'      => 'required',
//
//        ];
//
//        $validator = Validator::make($request->all(), $rules);
//
//        if ($validator->fails()) {
//            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
//        }
//
//        $created = ApiController::createUserDeviceToken($request->user()->id, $request->device_token, $request->device_type);
//
//
//            return $created
//                ? ApiController::respondWithSuccess([])
//                : ApiController::respondWithServerErrorArray();
//    }
}
