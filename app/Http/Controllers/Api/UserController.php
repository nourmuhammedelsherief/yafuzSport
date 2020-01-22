<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Validator;
use App\User;
use App\ContactUs;
use App\UserDevice;
use App;
use App\Events\UpdateLocation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //
    public function change_image(Request $request){

        $rules = [
            'image'            => 'required|mimes:jpeg,bmp,png,jpg|max:5000',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user= User::where('id',$request->user()->id)->first();

        $updated=  $user->update([
            'image'=>  UploadImageEdit($request->file('image'), 'image', '/uploads/users',$request->user()->image),
        ]);

        $success = ['key'=>'image',
            'value'=> User::find($request->user()->id)->image
        ];
        return $updated
            ? ApiController::respondWithSuccess($success)
            : ApiController::respondWithServerErrorObject();



    }
    public function change_country(Request $request)
    {
        $rules = [
            'country_id'    => 'required|exists:countries,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user= User::where('id',$request->user()->id)->first();

        $updated=  $user->update([
            'country_id'=>  $request->country_id,
        ]);

        $success = ['key'=>'country_id',
            'value'=> User::find($request->user()->id)->country_id,
            'country'=> App\Country::find($user->country_id)->name
        ];
        return $updated
            ? ApiController::respondWithSuccess($success)
            : ApiController::respondWithServerErrorObject();
    }
    public function change_city(Request $request)
    {
        $rules = [
            'country_id'    => 'required|exists:countries,id',
            'city_id'    => 'required|exists:cities,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user= User::where('id',$request->user()->id)->first();

        $updated=  $user->update([
            'country_id'=>  $request->country_id,
            'city_id'=>  $request->city_id,
        ]);

        $success = ['key'=>'city_id',
            'country_id'=> User::find($request->user()->id)->country_id,
            'country'=> App\Country::find($user->country_id)->name,
            'city_id'=> User::find($request->user()->id)->city_id,
            'city'=> App\City::find($user->city_id)->name
        ];
        return $updated
            ? ApiController::respondWithSuccess($success)
            : ApiController::respondWithServerErrorObject();
    }
    public function change_gender(Request $request)
    {
        $rules = [
            'gender'    => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user= User::where('id',$request->user()->id)->first();

        $updated=  $user->update([
            'gender'=>  $request->gender,
        ]);

        $success = ['key'=>'gender',
            'value'=> User::find($request->user()->id)->gender,
        ];
        return $updated
            ? ApiController::respondWithSuccess($success)
            : ApiController::respondWithServerErrorObject();
    }
    public function contact_us(Request $request){
        $rules = [
            'name'                  => 'required|max:255',
            // 'email'                 => 'required',
            'description'           => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
        $all=[];
        $contact = ContactUs::create([
            'name'           => $request->name,
            // 'email'          => $request->email,
            'description'    =>$request->description,
            'user_id'        =>$request->user()->id,
        ]);
        array_push($all,[
            'id'=>intval($contact->id),
            'user_id' =>intval($request->user()->id),
            'name'=>$contact->name,
//            'email'=>$contact->email,
            'description'=>$contact->description,
            'created_at'=>$contact->created_at->format('Y-m-d'),
        ]);
        $devicesTokens = App\UserDevice::where('user_id', $contact->user_id)
            ->get()
            ->pluck('device_token')
            ->toArray();
        if ($devicesTokens) {
            sendMultiNotification("الاخبار", "لقد تمت الموافقه للنشر" ,$devicesTokens);
        }

        saveNotification($contact->user_id, "الاخبار" , '3', "لقد تمت الموافقه للنشر " , $contact->id);
        return $contact
            ? ApiController::respondWithSuccess($all)
            : ApiController::respondWithServerErrorArray();
    }
    public function check_user_permission(Request $request)
    {
        $user = User::find($request->user()->id);
        $yes = [];
        array_push($yes,[
            'posts'=>intval($user->posts),
        ]);
        $no = [];
        array_push($no,[
            'posts'=>intval($user->posts),
        ]);
        if ($user->posts == 1)
        {
            return $user
                ? ApiController::respondWithSuccess($yes)
                : ApiController::respondWithServerErrorArray();
        }else
        {
            return $user
                ? ApiController::respondWithSuccess($no)
                : ApiController::respondWithServerErrorArray();
        }
    }
    public function change_name(Request $request )
    {
        $rules = [
            'name'    => 'required|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user= User::where('id',$request->user()->id)->first();

        $updated=  $user->update([
            'name'=>  $request->name,
        ]);

        $success = ['key'=>'name',
            'value'=> User::find($request->user()->id)->name,
        ];
        return $updated
            ? ApiController::respondWithSuccess($success)
            : ApiController::respondWithServerErrorObject();
    }
    public function update_location(Request $request)
    {
//        $lang = $request->header('Accept-Language', 'ar');
//        \App::setLocale($lang);
        if (!$user = $request->user()) {
            return response()->json(['value' => false, 'msg' => \Lang::get('data.wrong_token')]);
        }

        $validator = \Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required'
        ]);
        if ($validator->passes()) {

            $user->lat = $request['lat'];
            $user->lng = $request['lng'];
            $user->update();

//            event(new UpdateLocation($request['lat'], $request['lng']));
// message
            $data = [
                'channel' => 'sending-chat',
                'data' => [
                    'id' => 2,
                    'user_id' =>6,
                    'message' =>'fuck to all',
                    'created_at'=>'2019-11-14'
                ]
            ];
            // redis publish
            //  channelName  , data

            Redis::publish('test', json_encode($data));
//            Redis::publish('test', json_encode($data));
            return response()->json($data);
        } else {
            return response()->json(['value' => false, 'msg' => $validator->errors()->first()]);
        }
    }


}
