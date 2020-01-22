<?php

namespace App\Http\Controllers\Api;

use App\About;
use App\Activity;
use App\AppAdd;
use App\City;
use App\ContactUs;
use App\Country;
use App\Education;
use App\Field;
use App\Group;
use App\GroupUser;
use App\Notification;
use App\Rating;
use App\SawaqUserDevice;
use App\TermsCondition;
use App\User;
use App\UserDevice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class ApiController extends Controller
{




    public function getImagesPath() {

        $data['user'] = imgPath('users');
        $data['company'] = imgPath('companies');
        $data['group'] = imgPath('groups');
        $data['app_adds'] = imgPath('app_adds');

        return $this->respondWithSuccess($data);
    }



    public function getAbout(Request $request) {

        $rules = [
            'lang'   => 'required|in:ar,en'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return $this->respondWithError(validateRules($validator->errors(), $rules));

        $data = About::where('id', 1)->select($request->lang .'_content')->first();

        return $this->respondWithSuccess($data);
    }

    public function getTermsConditions(Request $request) {

        $rules = [
            'lang'   => 'required|in:ar,en'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return $this->respondWithError(validateRules($validator->errors(), $rules));

        $data = TermsCondition::where('id', 1)->select($request->lang .'_content')->first();

        return $this->respondWithSuccess($data);
    }

    public function contactUs(Request $request) {

        $rules = [
            'name'      => 'required|max:255',
            'email'     => 'required|max:194',
            'message'   => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return $this->respondWithError(validateRules($validator->errors(), $rules));

        $created = ContactUs::create($request->all());

        return $created
            ? $this->respondWithSuccess($created)
            : $this->respondWithServerError();
    }

    public function listNotifications(Request $request) {

        $notifications = Notification::Where('user_id', $request->user()->id)->select('id', 'type', 'title', 'message','contact_id','city_id','group_id', 'created_at')->orderBy('id','desc')->get();
        $new =[];
        foreach ($notifications as $notification)
        {
            if ($notification->type == '1' && $notification->group_id != null)
            {
                array_push($new,
                    [
                        'id'              =>intval($notification->id),
                        'type'            =>intval($notification->type),
                        'title'           =>$notification->title,
                        'message'         =>$notification->message,
                        'city_id'         =>intval($notification->city_id),
                        'contact_id'      =>intval($notification->contact_id),
                        'group_id'        =>intval($notification->group_id),
                        'group_name'      =>Group::find($notification->group_id)->name,
                        'created_at'      =>$notification->created_at->format('d-m-Y')
                    ]);
            }else if($notification->type == '0' && $notification->group_id != null){
                $group_model = [];
                $ggroup = Group::find($notification->group_id);
                    array_push($group_model,
                        [
                            'id'              =>intval($ggroup->id),
                            'name'            =>$ggroup->name,
                            'city_id'         =>intval($ggroup->city_id),
                            'city'            =>City::find($ggroup->city_id)->name,
                            'GroupImagesPath' =>'/uploads/groupImages',
                            'image'           =>$ggroup->photo,
                            'activity_id'     =>intval($ggroup->activity_id),
                            'activity'        =>Activity::find($ggroup->activity_id)->name,
                            'admin_id'        =>intval($ggroup->admin),
                            'admin'           =>User::find($ggroup->admin)->name,
                            'adminPath'       =>'/uploads/users',
                            'admin_image'     =>User::find($ggroup->admin)->image,
                            'about_me'        =>$ggroup->about_me,
                            'created_at'      =>$ggroup->created_at->format('d-m-Y')
                        ]);
                array_push($new,
                    [
                        'id'              =>intval($notification->id),
                        'type'            =>intval($notification->type),
                        'title'           =>$notification->title,
                        'message'         =>$notification->message,
                        'city_id'         =>intval($notification->city_id),
                        'contact_id'      =>intval($notification->contact_id),
                        'group_id'        =>intval($notification->group_id),
                        'group'           =>$group_model,
                        'created_at'      =>$notification->created_at->format('d-m-Y')
                    ]);
            }else{
                array_push($new,
                    [
                        'id'              =>intval($notification->id),
                        'type'            =>intval($notification->type),
                        'title'           =>$notification->title,
                        'message'         =>$notification->message,
                        'city_id'         =>intval($notification->city_id),
                        'contact_id'      =>intval($notification->contact_id),
                        'city_name'       =>City::find($notification->city_id)->name,
                        'country_name'    =>Country::whereId(City::find($notification->city_id)->country_id)->first()->name,
                        'created_at'      =>$notification->created_at->format('d-m-Y')
                    ]);
            }
        }
        $data=[];
        array_push($data , ['notifications'=> $new]);
        return $this->respondWithSuccess($data);
    }
    public function delete_Notifications( $id , Request $request) {

        $data = Notification::Where('id', $id)->where('user_id',$request->user()->id)->delete();
        return $data
            ? $this->respondWithSuccess([])
            :$this->respondWithServerErrorArray();
    }

    public static function createUserDeviceToken($userId, $deviceToken, $deviceType) {

        $created = UserDevice::create(['user_id' => $userId, 'device_type' => $deviceType, 'device_token' => $deviceToken]);

        return $created;
    }


    public static function respondWithSuccess($data) {
        http_response_code(200);
        return response()->json(['mainCode'=> 1,'code' =>  http_response_code()  , 'data' => $data, 'error' => null])->setStatusCode(200);
    }

    public static function respondWithErrorArray($errors) {
        http_response_code(422);  // set the code
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(422);
    }public static function respondWithErrorObject($errors) {
    http_response_code(422);  // set the code
    return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(422);
}
    public static function respondWithErrorNOTFoundObject($errors) {
        http_response_code(404);  // set the code
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(404);
    }
    public static function respondWithErrorNOTFoundArray($errors) {
        http_response_code(404);  // set the code
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(404);
    }
    public static function respondWithErrorClient($errors) {
        http_response_code(400);  // set the code
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(400);
    }
    public static function respondWithErrorAuthObject($errors) {
        http_response_code(401);  // set the code
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(401);
    }
    public static function respondWithErrorAuthArray($errors) {
        http_response_code(401);  // set the code
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(401);
    }


    public static function respondWithServerErrorArray() {
        $errors = 'Sorry something went wrong, please try again';
        http_response_code(500);
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(500);
    }
    public static function respondWithServerErrorObject() {
        $errors = 'Sorry something went wrong, please try again';
        http_response_code(500);
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(500);
    }



}
