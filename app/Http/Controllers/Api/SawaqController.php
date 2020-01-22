<?php

namespace App\Http\Controllers\Api;

use App\City;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Validator;
use App\User;
use App;
use Auth;

class SawaqController extends Controller
{
    //


    public function my_orders(Request $request)
    {


        $orders = App\OrderForSawaq::where('order_for_sawaq.user_id',$request->user()->id)
            ->join('orders','orders.id','=','order_for_sawaq.order_id')
            ->select('order_for_sawaq.id as sawaq_id','order_for_sawaq.status as sawaq_status','order_for_sawaq.order_id as order_id'
                ,'orders.*')
            ->where('order_for_sawaq.status',$request->status == null ? 1 : $request->status )
            ->get();

//        return ApiController::respondWithSuccess($orders);
        $count= $orders->count();
        $currentPage =$request->page;
        $perPage=10;
        $currentPageItems1 = $orders->slice(($currentPage - 1) * $perPage, $perPage);
        $new =[];
        foreach($currentPageItems1 as $data){

                array_push($new,[
                    'id'=>intval($data->sawaq_id),
                    'order_id'=>intval($data->id),
                    'commission_status'=>App\SawaqOfferPrice::where('order_id',$data->id)->where('sawaq_user_id',$request->user()->id)->first() == null ? null : App\SawaqOfferPrice::where('order_id',$data->id)->where('sawaq_user_id',$request->user()->id)->first()->commission_status ,
                    'order_type'=>intval($data->order_type),
                    'user_id'=>App\Order::find($data->order_id)->user_id,
                    'from_city_id'=>intval($data->from_city_id),
                    'from_city'=>App\City::find($data->from_city_id)->name,
                    'from_region_id'=>intval($data->from_region_id),
                    'from_region'=>App\City::find($data->from_region_id)->name,
                    'longitude'=>App\City::find($data->from_region_id)->longitude,
                    'latitude'=>App\City::find($data->from_region_id)->latitude,
                    'deliver_time'=>intval($data->deliver_time),
                    'from_time'=>$data->from_time,
                    'to_time'=>$data->to_time,
                    'status'=>$data->sawaq_status,
                    'created_at'=>$data->created_at->format('Y-m-d'),
                    'price'=>$data->price,
                    'sawaq_user_id'=>$data->sawaq_user_id,
                    'phone_number'=>User::find($request->user()->id)->phone_number,
                    'username'=>User::find($request->user()->id)->name,
                    'to_region_id'=>intval($data->to_region_id),
                    'to_region'=> $data->order_type == 2 ?   App\City::find($data->to_region_id)->name : null,
                    'address'=>$data->address,
                    'start_date'=>$data->start_date,
                    'end_date'=>$data->end_date,
                    'to_school'=>intval($data->to_school),
                    'school'=>$data->order_type == 1 ? App\School::find($data->to_school)->name : null,

                ]);


        }
        $data=[];
        array_push($data , ['orders'=> $new , 'count'=> $count]);
        return ApiController::respondWithSuccess($data);
    }

    public function refuse_order($order_id,Request $request)
    {
        $orders=App\OrderForSawaq::where('id',$order_id)
            ->where('user_id',$request->user()->id)->first();
        if ($orders == null){
            $errors = ['key'=>'message',
                'value'=> trans('messages.not_found')
            ];
            return ApiController::respondWithErrorArray(array($errors));
        }

        $orders=App\OrderForSawaq::where('id',$order_id)
            ->where('user_id',$request->user()->id)
            ->delete();

        return $orders
            ? ApiController::respondWithSuccess([])
            : ApiController::respondWithServerErrorArray();

    }
    public function send_offer($order_id,Request $request)
    {

        $order=App\OrderForSawaq::find($order_id);
        if ($order == null){
            $errors = ['key'=>'message',
                'value'=> trans('messages.not_found')
            ];
            return ApiController::respondWithErrorArray(array($errors));
        }
        $rules = [
            'price' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $offer=App\SawaqOfferPrice::updateOrCreate(
            ['sawaq_user_id' => $request->user()->id, 'order_id' => $order->order_id,'user_id'=>App\Order::find($order->order_id)->user_id],
            [ 'price' => $request->price,'status'=>1]
        );
        $devicesTokens =  App\UserDevice::where('user_id',App\Order::find($order->order_id)->user_id)
            ->get()
            ->pluck('device_token')
            ->toArray();
          

        if ($devicesTokens) {
            sendMultiNotification("طلب جديد", "هناك سواق تقدم علي طلبك تصفح العرض" ,$devicesTokens);
            
        }
        
        saveNotification(App\Order::find($order->order_id)->user_id, "طلب جديد" , '1', "هناك سواق تقدم علي طلبك تصفح العرض" , $order->order_id , $request->user()->id);

        return $offer
            ? ApiController::respondWithSuccess([])
            : ApiController::respondWithServerErrorArray();

    }

    public function commission_status(Request $request){
        $commission = App\SawaqOfferPrice::where('sawaq_user_id',$request->user()->id)
            ->where('commission_status','!=',1)->get();
        if (count($commission) !== 0){
            $success = ['key'=>'message',
                'value'=> 2 // should pay commission
            ];

            return ApiController::respondWithSuccess($success);
        }else{
            $success = ['key'=>'message',
                'value'=> 1
            ];

            return ApiController::respondWithSuccess($success);
        }
    }
    public function get_driver($id,Request $request)
    {

        $user=App\User::find($id);
        if ($user == null){
            $errors = ['key'=>'message',
                'value'=> trans('messages.not_found')
            ];
            return ApiController::respondWithErrorArray(array($errors));
        }
        $all=[];
        array_push($all,[
            'id'=>$user->id,
            'name'=>$user->name,
            'phone_number'=>$user->phone_number,
            'region_id'=>intval($user->region_id),
            'city_id'=>intval($user->city_id),
            'region'=>App\City::find($user->region_id)->name,
            'city'=>App\City::find($user->city_id)->name,
            'longitude'            => City::find($user->region_id)->longitude,
            'latitude'            => City::find($user->region_id)->latitude,
            'active'=>$user->active,
            'ImgPath'=>"/uploads/users/",
            'image'            => $user->image,
            'car_image'            => $user->car_image,
            'api_token'=>$user->api_token,

            'multi_place'=> $user->multi_place, // 0 for no 1 for yes
            'places_id'=> intval($user->places),
            'places'=> City::find($user->places)->name,
            'university_drive'=> $user->university_drive, // 0 for no 1 for yes
            'employees_drive'=> $user->employees_drive, // 0 for no 1 for yes
            'driver_id' => intval($user->driver_id),
            'driver' => App\Driver::find($user->driver_id)->name,
            'nationality_id' =>intval($user->nationality_id),
            'nationality' => App\Nationality::find($user->nationality_id)->name,
            'age_id' =>intval( $user->age_id),
            'age' => App\Age::find($user->age_id)->number,
            'company_id' =>intval( $user->company_id),
            'company' => App\Company::find($user->company_id)->name,
            'car_model_id' =>intval( $user->car_model_id),
            'car_model' => App\CarModel::find($user->car_model_id)->name,
            'city_mode_id' => intval($user->city_mode_id),
            'city_mode' => App\ModelCity::find($user->city_mode_id)->year,
            'passenger_id' => intval($user->passenger_id),
            'passenger' => App\Passenger::find($user->passenger_id)->number,
            'rate'=>(int) App\Rate::where('to_user_id',$user->id)->avg('rate'),

            'created_at'=>$user->created_at->format('Y-m-d'),
        ]);

        return $user
            ? ApiController::respondWithSuccess($all)
            : ApiController::respondWithServerErrorArray();

    }

    public function get_user($id,Request $request)
    {

        $user=App\User::find($id);
        if ($user == null){
            $errors = ['key'=>'message',
                'value'=> trans('messages.not_found')
            ];
            return ApiController::respondWithErrorArray(array($errors));
        }
        $all=[];
        array_push($all,[
            'id'=>$user->id,
            'name'=>$user->name,
            'phone_number'=>$user->phone_number,

            'active'=>$user->active,
            'ImgPath'=>"/uploads/users/",
            'image'            => $user->image,
            'api_token'=>$user->api_token,
            'created_at'=>$user->created_at->format('Y-m-d'),
        ]);

        return $user
            ? ApiController::respondWithSuccess($all)
            : ApiController::respondWithServerErrorArray();

    }
    public function pay_commission($id,Request $request){

        $rules = [
            'image'            => 'required|mimes:jpeg,bmp,png,jpg|max:5000',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        // $user=   App\SawaqOfferPrice::find($id);
            $offer= App\SawaqOfferPrice::where('order_id',$id)->where('sawaq_user_id',$request->user()->id)->first();
        $updated=  $offer->update([
            'commission'=>  UploadImageEdit($request->file('image'), 'image', '/uploads/users',$request->image),
        ]);


        $success = ['key'=>'image',
            'value'=> User::find($request->user()->id)->image
        ];
        return $updated
            ? ApiController::respondWithSuccess($success)
            : ApiController::respondWithServerErrorObject();



    }

}
