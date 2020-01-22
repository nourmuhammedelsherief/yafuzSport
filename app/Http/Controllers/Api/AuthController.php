<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App;
use Auth;
use App\User;
use App\City;
use App\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmCode;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    public function registerMobile(Request $request) {
        $rules = [
            'phone_number' => 'required|unique:users',
//            'phone_number' => 'required|max:9|unique:users|regex:/(5)[0-9]{8}/',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $code = mt_rand(1000, 9999);
            $jsonObj = array(
            'mobile' => 'yafuzsport',
            'password' => '589935sa',
            'sender'=>'yafuzsport',
            'numbers' => $request->phone_number,
            'msg'=>'كود التأكيد الخاص بك في حواري sport هو :'.$code,

            'msgId' => rand(1,99999),

            'timeSend' => '0',

            'dateSend' => '0',

            'deleteKey' => '55348',
            	'lang' => '3',
            	'applicationType' => 68,
            );
            // دالة الإرسال JOSN
            $result=$this->sendSMS($jsonObj);


//        $ans= substr($ans,0,1);
        $created = App\PhoneVerification::create([
            'code'=>$code,
            'phone_number'=>$request->phone_number
        ]);


        return  ApiController::respondWithSuccess([]);


    }
  public  function sendSMS($jsonObj)
{
    $contextOptions['http'] = array('method' => 'POST', 'header'=>'Content-type: application/json', 'content'=> json_encode($jsonObj), 'max_redirects'=>0, 'protocol_version'=> 1.0, 'timeout'=>10, 'ignore_errors'=>TRUE);
    $contextResouce  = stream_context_create($contextOptions);
    $url = "http://www.alfa-cell.com/api/msgSend.php";
    $arrayResult = file($url, FILE_IGNORE_NEW_LINES, $contextResouce);
    $result = $arrayResult[0];

    return $result;
}
    public function register_phone_post(Request $request){

        $rules = [
            'code' => 'required',
            'phone_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user= App\PhoneVerification::where('phone_number',$request->phone_number)->orderBy('id','desc')->first();

        if ($user){

            if($user->code == $request->code){
                $successLogin = ['key'=>'message',
                    'value'=> "كود التفعيل صحيح"
                ];
                return ApiController::respondWithSuccess($successLogin);
            }else{
                $errorsLogin = ['key'=>'message',
                    'value'=> trans('messages.error_code')
                ];
                return ApiController::respondWithErrorClient(array($errorsLogin));
            }

        }else{

            $errorsLogin = ['key'=>'message',
                'value'=> trans('messages.error_code')
            ];
            return ApiController::respondWithErrorClient(array($errorsLogin));
        }


    }
    public function resend_code(Request $request){

        $rules = [
            'phone_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));


        $code = mt_rand(1000, 9999);


    $jsonObj = array(
            'mobile' => 'yafuzsport',
            'password' => '589935sa',
            'sender'=>'yafuzsport',
            'numbers' => $request->phone_number,
            'msg'=>'كود التأكيد الخاص بك في حواري sport هو :'.$code,

            'msgId' => rand(1,99999),

            'timeSend' => '0',

            'dateSend' => '0',

            'deleteKey' => '55348',
            	'lang' => '3',
            	'applicationType' => 68,
            );
            // دالة الإرسال JOSN
            $result=$this->sendSMS($jsonObj);

        $created = App\PhoneVerification::create([
            'code'=>$code,
            'phone_number'=>$request->phone_number
        ]);


            return $created
                ? ApiController::respondWithSuccess( trans('messages.success_send_code'))
                : ApiController::respondWithServerErrorObject();





    }

    public function register_data(){

        $nationalities = App\Nationality::select('id','name')->get();
        $ages = App\Age::select('id','number')->get();
        $companies = App\Company::select('id','name')->get();
        $car_models = App\CarModel::select('id','name')->get();
        $model_cities = App\ModelCity::select('id','year')->get();
        $passengers = App\Passenger::select('id','number')->get();
        $cities = App\City::select('id','name')->where('parent_id',null)->get();

        $data =[];
        array_push($data,['nationalities'=>$nationalities,
            'ages'=>$ages,'companies'=>$companies,'car_models'=>$car_models,
            'model_cities'=>$model_cities,'passengers'=>$passengers,'cities'=>$cities]);
        return ApiController::respondWithSuccess($data);

    }
    public function register_countries()
    {
        $countries = App\Country::all();
        $data =[];
        array_push($data,
            [
                'countries' =>$countries,
            ]);
        return ApiController::respondWithSuccess($data);

    }
    public function register_cities($id)
    {
        $cities = App\City::whereCountry_id($id)->get();
        $data =[];
        array_push($data,
            [
                'cities' =>$cities,
            ]);
        return ApiController::respondWithSuccess($data);
    }
    public function get_region($id){

        $cities = App\City::find($id);
        if ($cities == null){
            $errorsLogin = ['key'=>'message',
                'value'=> "لا يوجد"
            ];
            return ApiController::respondWithErrorClient(array($errorsLogin));
        }

        $cities = App\City::select('id','name')->where('parent_id',$id)->get();


        return ApiController::respondWithSuccess($cities);

    }

    public function register(Request $request) {

        $rules = [
            'phone_number'          => 'required|unique:users',
            'name'                  => 'required|max:255',
            'city_id'               => 'required|exists:cities,id',
            'country_id'            => 'required|exists:countries,id',
            'image'                 => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'password'              => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
            'device_token'          => 'required',
//            'gender'                => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
        $all=[];

            $user = User::create([
                'phone_number'  => $request->phone_number,
                'name'          => $request->name,
                'city_id'       => $request->city_id,
                'password'      => Hash::make($request->password),
                'image'         => $request->file('image') == null ? null : UploadImage($request->file('image'), 'image', '/uploads/users'),
//                'gender'        => $request->gender,
                'country_id'    =>$request->country_id,
                'active'        =>'1',
            ]);

            $user->update(['api_token' => generateApiToken($user->id, 10)]);


         App\PhoneVerification::where('phone_number',$request->phone_number)->orderBy('id','desc')->delete();
            array_push($all,[
                'id'=>$user->id,
                'name'=>$user->name,
                'phone_number'=>$user->phone_number,
                'city_id'=>intval($user->city_id),
                'city'=>App\City::find($user->city_id)->name,
                'country_id'=>intval($user->country_id),
                'country'=>App\Country::find($user->country_id)->name,
                'ImgPath'=>"/uploads/users/",
                'image'            => $user->image,
                'api_token'=>$user->api_token,
//                'gender'   =>$user->gender,
                'device_token'=>$request->device_token,
                'created_at'=>$user->created_at->format('Y-m-d'),
            ]);

        //save_device_token....
        $created = ApiController::createUserDeviceToken($user->id, $request->device_token, $request->device_type);

        return $user
            ? ApiController::respondWithSuccess($all)
            : ApiController::respondWithServerErrorArray();

    }


    public function login(Request $request) {

        $rules = [
            'phone_number'  => 'required',
            'password'      => 'required',
            'device_token'  => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));


            if (Auth::attempt(['phone_number' => $request->phone_number, 'password' => $request->password , 'active'=>'1']))
            {

//                    $errors = ['key'=>'message',
//                        'value'=> trans('messages.Sorry_your_membership_was_stopped_by_Management')
//                    ];
//                    return ApiController::respondWithErrorArray(array($errors));

                //save_device_token....
                $created = ApiController::createUserDeviceToken(Auth::user()->id, $request->device_token , null);

                $all = User::where('phone_number', $request->phone_number)->first();
                $all->update(['api_token' => generateApiToken($all->id, 10)]);
                $user =  User::where('phone_number', $request->phone_number)->first();

                $all=[];
                array_push($all,[
                    'id'=>$user->id,
                    'name'=>$user->name,
                    'phone_number'=>$user->phone_number,
                    'city_id' =>intval($user->city_id),
                    'city'=>App\City::find($user->city_id)->name,
                    'country_id' =>intval($user->country_id),
                    'country'=>App\Country::find($user->country_id)->name,
                    'ImgPath'=>"/uploads/users/",
                    'image'            => $user->image,
//                    'gender' => $user->gender,
                    'api_token'=>$user->api_token,
                    'created_at'=>$user->created_at->format('Y-m-d'),
                ]);

                return $created
                    ? ApiController::respondWithSuccess($all)
                    : ApiController::respondWithServerErrorArray();
            }
            else{
                $userPhone = User::wherePhone_number($request->phone_number)->first();
                if ($userPhone == null)
                {
                    $errors = [
                        'key'=>'message',
                        'value'=>trans('رقم الهاتف  الذي  أدخلتة غير صحيح'),
                    ];
                    return ApiController::respondWithErrorNOTFoundArray(array($errors));
                }else{
                    $errors = [
                        'key'=>'message',
                        'value'=>trans('كلمة المرور غير صحيحة'),
                    ];
                    return ApiController::respondWithErrorNOTFoundArray(array($errors));
                }

            }
    }
    public function forgetPassword(Request $request) {
        $rules = [
            'phone_number' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = User::where('phone_number',$request->phone_number)->first();

        if($user) {
            $code = mt_rand(1000, 9999);


                   $jsonObj = array(
            'mobile' => 'yafuzsport',
            'password' => '589935sa',
            'sender'=>'yafuzsport',
            'numbers' => $request->phone_number,
            'msg'=>'كود التأكيد الخاص بك في حواري sport هو :'.$code,

            'msgId' => rand(1,99999),

            'timeSend' => '0',

            'dateSend' => '0',

            'deleteKey' => '55348',
            	'lang' => '3',
            	'applicationType' => 68,
            );
            // دالة الإرسال JOSN
            $result=$this->sendSMS($jsonObj);
            $updated=  User::where('phone_number',$request->phone_number)->update([
                'verification_code'=>$code,
            ]);
            $success = ['key'=>'message',
                'value'=> "تم ارسال الكود بنجاح"
            ];

                return $updated
                    ? ApiController::respondWithSuccess($success)
                    : ApiController::respondWithServerErrorObject();




        }
        $errorsLogin = ['key'=>'message',
            'value'=> trans('messages.Wrong_phone')
        ];
        return ApiController::respondWithErrorClient(array($errorsLogin));
    }
    public function confirmResetCode(Request $request){

        $rules = [
            'phone_number' => 'required',
            'code' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user= User::where('phone_number',$request->phone_number)->where('verification_code',$request->code)->first();
        if ($user){
            $updated=  User::where('phone_number',$request->phone_number)->where('verification_code',$request->code)->update([
                'verification_code'=>null
            ]);
            $success = ['key'=>'message',
                'value'=> "الكود صحيح"
            ];
            return $updated
                ? ApiController::respondWithSuccess($success)
                : ApiController::respondWithServerErrorObject();
        }else{

            $errorsLogin = ['key'=>'message',
                'value'=> trans('messages.error_code')
            ];
            return ApiController::respondWithErrorClient(array($errorsLogin));
        }


    }

    public function resetPassword(Request $request) {
        $rules = [
            'phone_number'                 => 'required|numeric',
//            'phone'                 => 'required',
            'password'              => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = User::where('phone_number',$request->phone_number)->first();
//        $user = User::wherePhone($request->phone)->first();

        if($user)
            $updated = $user->update(['password' => Hash::make($request->password)]);
        else{
            $errorsLogin = ['key'=>'message',
                'value'=> trans('messages.Wrong_phone')
            ];
            return ApiController::respondWithErrorClient(array($errorsLogin));
        }


        return $updated
            ? ApiController::respondWithSuccess(trans('messages.Password_reset_successfully'))
            : ApiController::respondWithServerErrorObject();
    }

    public function changePassword(Request $request) {

        $rules = [
            'current_password'      => 'required',
            'new_password'          => 'required',
            'password_confirmation' => 'required|same:new_password',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $error_old_password = ['key'=>'message',
            'value'=> trans('messages.error_old_password')
        ];
        if (!(Hash::check($request->current_password, $request->user()->password)))
                return ApiController::respondWithErrorNOTFoundObject(array($error_old_password));
//        if( strcmp($request->current_password, $request->new_password) == 0 )
//            return response()->json(['status' => 'error', 'code' => 404, 'message' => 'New password cant be the same as the old one.']);

        //update-password-finally ^^
        $updated = $request->user()->update(['password' => Hash::make($request->new_password)]);

        $success_password = ['key'=>'message',
            'value'=> trans('messages.Password_reset_successfully')
        ];

        return $updated
            ? ApiController::respondWithSuccess($success_password)
            : ApiController::respondWithServerErrorObject();
    }

    public function change_phone_number(Request $request) {


        $rules = [
            'phone_number' => 'required|numeric|unique:users,phone_number,'.$request->user()->id,
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));



            $code = mt_rand(1000, 9999);


                   $jsonObj = array(
            'mobile' => 'yafuzsport',
            'password' => '589935sa',
            'sender'=>'yafuzsport',
            'numbers' => $request->phone_number,
            'msg'=>'كود التأكيد الخاص بك في حواري sport هو :'.$code,
            'msgId' => rand(1,99999),
            'timeSend' => '0',
            'dateSend' => '0',
            'deleteKey' => '55348',
            	'lang' => '3',
            	'applicationType' => 68,
            );
            // دالة الإرسال JOSN
            $result=$this->sendSMS($jsonObj);
            $updated=  User::where('id',$request->user()->id)->update([
                'verification_code'=>$code,
            ]);

        $success = ['key'=>'message',
            'value'=> trans('messages.success_send_code')
        ];
        return $updated
                    ? ApiController::respondWithSuccess($success)
                    : ApiController::respondWithServerErrorObject();
    }
    public function check_code_changeNumber(Request $request){

        $rules = [
            'code' => 'required',
            'phone_number' => 'required|numeric|unique:users,phone_number,'.$request->user()->id,
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user= User::where('id',$request->user()->id)->where('verification_code', $request->code)->first();
        if ($user){
            $updated=  $user->update([
                'verification_code'=>null,
                'phone_number'=>$request->phone_number,
            ]);

            $success = ['key'=>'message',
                'value'=> "success your phone number changed"
            ];
            return $updated
                ? ApiController::respondWithSuccess($success)
                : ApiController::respondWithServerErrorObject();
        }else{

            $errorsLogin = ['key'=>'message',
                'value'=> trans('messages.error_code')
            ];
            return ApiController::respondWithErrorClient(array($errorsLogin));
        }


    }

    public function logout(Request $request)
    {

        $rules = [
            'device_token'     => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $exists = App\UserDevice::where('id',$request->user()->id)->where('device_token',$request->device_token)->get();

        if (count($exists) !== 0){
            foreach ($exists  as $new){
                $new->delete();
            }

        }
        $users=  User::where('id',$request->user()->id)->first()->update(
            [
                'api_token'=>null
            ]
        );
        return $users
            ? ApiController::respondWithSuccess([])
            : ApiController::respondWithServerErrorArray();


    }

}
