<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\User;
use App\UserDevice;
use App;
use Auth;

class OrderController extends Controller
{
    //


    public function order_post(Request $request){

        $rules = [
            'order_type'=> 'required',// 1 for student 2 for employee 3 for rent

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
        if ($request->order_type == 1){
            $rules = [
                'order_type'=> 'required',// 1 for student 2 for employee 3 for rent
                'from_city_id'=>'required|exists:cities,id',
                'from_region_id'=>'required|exists:cities,id',
                'deliver_time'=>'required',  // 1 for weekly 2 for monthly
                'from_time'=>'required',
//                'from_time'=>'required|date_format:h:i a',
                'to_time'=>'required',
                'to_school'=>'required|exists:schools,id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
            $created = App\Order::create([

                'order_type'=>$request->order_type,
                'from_city_id'=>$request->from_city_id,
                'from_region_id'=>$request->from_region_id,
                'deliver_time'=>$request->deliver_time,
                'from_time'=>$request->from_time,
                'to_time'=>$request->to_time,
                'to_school'=>$request->to_school,
                'status'=>1,
                'user_id'=>$request->user()->id,
            ]);

            $data=[
                'id'=>intval($created->id),
                'user_id'=>intval($request->user()->id),
                'phone_number'=>User::find($request->user()->id)->phone_number,
                'username'=>User::find($request->user()->id)->name,
                'order_type'=>intval($created->order_type),
                'from_city_id'=>intval($created->from_city_id),
                'from_city'=>App\City::find($created->from_city_id)->name,
                'from_region_id'=>intval($created->from_region_id),
                'from_region'=>App\City::find($created->from_region_id)->name,
                'deliver_time'=>intval($created->deliver_time),
                'from_time'=>$created->from_time,
                'to_time'=>$created->to_time,
                'to_school'=>intval($created->to_school),
                'school'=>App\School::find($created->to_school)->name,
                'status'=>$created->status,
                'to_region_id'=>null,
                'to_region'=>null,
                'address'=>null,
                'sawaq_user_id'=>null,
                'price'=>null,
                'start_date'=>null,
                'end_date'=>null,
                'created_at'=>$created->created_at->format('Y-m-d'),

            ];
            $users=User::where('city_id',$request->from_city_id)
                ->get();
            foreach ($users as $user){
                $commission = App\SawaqOfferPrice::where('sawaq_user_id',$user->id)->orderBy('id','desc')->first();
                if ($commission !== null && 
                ($commission->commission_status == 1 || $commission->commission_status == 0)){



                    App\OrderForSawaq::create([
                        'order_id'=>$created->id,
                        'user_id'=>$user->id,
                        'status'=>1,

                    ]);
                }elseif ($commission == null){

                    App\OrderForSawaq::create([
                        'order_id'=>$created->id,
                        'user_id'=>$user->id,
                        'status'=>1,

                    ]);
                }

            }

            return $created
                ? ApiController::respondWithSuccess(array($data))
                : ApiController::respondWithServerErrorArray();

        } elseif ($request->order_type == 2){
            $rules = [
                'order_type'=> 'required',// 1 for student 2 for employee 3 for rent
                'from_city_id'=>'required|exists:cities,id',
                'from_region_id'=>'required|exists:cities,id',
                'deliver_time'=>'required',  // 1 for weekly 2 for monthly
                'address'=>'required',
                'from_time'=>'required',
                'to_time'=>'required',
                'to_region_id'=>'required|exists:cities,id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
            $created = App\Order::create([

                'order_type'=>$request->order_type,
                'from_city_id'=>$request->from_city_id,
                'from_region_id'=>$request->from_region_id,
                'deliver_time'=>$request->deliver_time,
                'from_time'=>$request->from_time,
                'to_time'=>$request->to_time,
                'to_region_id'=>$request->to_region_id,
                'address'=>$request->address,
                'status'=>1,
                'user_id'=>$request->user()->id,
            ]);

            $data=[
                'id'=>intval($created->id),
                'user_id'=>intval($request->user()->id),
                'phone_number'=>User::find($request->user()->id)->phone_number,
                'username'=>User::find($request->user()->id)->name,
                'order_type'=>intval($created->order_type),
                'from_city_id'=>intval($created->from_city_id),
                'from_city'=>App\City::find($created->from_city_id)->name,
                'from_region_id'=>intval($created->from_region_id),
                'from_region'=>App\City::find($created->from_region_id)->name,
                'deliver_time'=>intval($created->deliver_time),
                'from_time'=>$created->from_time,
                'to_time'=>$created->to_time,
                'to_region_id'=>intval($created->to_region_id),
                'to_region'=>App\City::find($created->to_region_id)->name,
                'status'=>$created->status,
                'address'=>$created->address,

                'to_school'=>null,
                'school'=>null,


                'sawaq_user_id'=>null,
                'price'=>null,

                'start_date'=>null,
                'end_date'=>null,

                'created_at'=>$created->created_at->format('Y-m-d'),


            ];
            $users=User::where('region_id',$request->from_region_id)
                ->where('city_id',$request->from_city_id)
                ->get();
            foreach ($users as $user){
                $commission = App\SawaqOfferPrice::where('sawaq_user_id',$user->id)->orderBy('id','desc')->first();
                if ($commission !== null && ($commission->commission_status == 1 || $commission->commission_status == 0)){

                    App\OrderForSawaq::create([
                        'order_id'=>$created->id,
                        'user_id'=>$user->id,
                        'status'=>1,

                    ]);
                }elseif ($commission == null){

                    App\OrderForSawaq::create([
                        'order_id'=>$created->id,
                        'user_id'=>$user->id,
                        'status'=>1,

                    ]);
                }

            }

            return $created
                ? ApiController::respondWithSuccess(array($data))
                : ApiController::respondWithServerErrorArray();

        }elseif ($request->order_type == 3){
            $rules = [
                'order_type'=> 'required',// 1 for student 2 for employee 3 for rent
                'from_city_id'=>'required|exists:cities,id',
                'from_region_id'=>'required|exists:cities,id',
                'from_time'=>'required',
                'to_time'=>'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
            $created = App\Order::create([

                'order_type'=>$request->order_type,
                'from_city_id'=>$request->from_city_id,
                'from_region_id'=>$request->from_region_id,
                'from_time'=>$request->from_time,
                'to_time'=>$request->to_time,
                'status'=>1,
                'user_id'=>$request->user()->id,
            ]);

            $data=[
                'id'=>intval($created->id),
                'user_id'=>intval($request->user()->id),
                'phone_number'=>User::find($request->user()->id)->phone_number,
                'username'=>User::find($request->user()->id)->name,
                'order_type'=>intval($created->order_type),
                'from_city_id'=>intval($created->from_city_id),
                'from_city'=>App\City::find($created->from_city_id)->name,
                'from_region_id'=>intval($created->from_region_id),
                'from_region'=>App\City::find($created->from_region_id)->name,
                'from_time'=>$created->from_time,
                'to_time'=>$created->to_time,
                'status'=>$created->status,
                'deliver_time'=>null,
                'to_region_id'=>null,
                'to_region'=>null,
                'address'=>null,
                'to_school'=>null,
                'school'=>null,
                'sawaq_user_id'=>null,
                'price'=>null,

                'start_date'=>null,
                'end_date'=>null,
                'created_at'=>$created->created_at->format('Y-m-d'),

            ];
            $users=User::where('region_id',$request->from_region_id)
                ->where('city_id',$request->from_city_id)
                ->get();
            foreach ($users as $user){
                $commission = App\SawaqOfferPrice::where('sawaq_user_id',$user->id)->orderBy('id','desc')->first();
                if ($commission !== null && ($commission->commission_status == 1 || $commission->commission_status == 0)){

                    App\OrderForSawaq::create([
                        'order_id'=>$created->id,
                        'user_id'=>$user->id,
                        'status'=>1,

                    ]);
                }elseif ($commission == null){

                    App\OrderForSawaq::create([
                        'order_id'=>$created->id,
                        'user_id'=>$user->id,
                        'status'=>1,

                    ]);
                }

            }

            return $created
                ? ApiController::respondWithSuccess(array($data))
                : ApiController::respondWithServerErrorArray();

        }



    }
    public function accept_sawaq_offers_price($id,Request $request){


        $offer=App\SawaqOfferPrice::find($id);
        if ($offer == null){
            $errors = ['key'=>'message',
                'value'=> trans('messages.not_found')
            ];
            return ApiController::respondWithErrorObject(array($errors));
        }

        $order = App\Order::where('id',$offer->order_id)->first();
        if ($order->order_type == 1){
            if ($order->deliver_time == 1){
                $now = Carbon::now();
                $end = Carbon::now()->addDays(7);

            }else{
                $now = Carbon::now();
                $end = Carbon::now()->addDays(30);

            }
             App\Order::where('id',$offer->order_id)
                ->first()->update([
                    'status'=>2, //for done deal
                    'sawaq_user_id'=>$offer->sawaq_user_id,
                    'price'=>$offer->price,
                    'start_date'=>$now->format('Y-m-d'),
                    'end_date'=>$end->format('Y-m-d'),

                ]);
        }elseif ($order->order_type == 2){
            if ($order->deliver_time == 1){
                $now = Carbon::now();
                $end = Carbon::now()->addDays(7);

            }else{
                $now = Carbon::now();
                $end = Carbon::now()->addDays(30);

            }
             App\Order::where('id',$offer->order_id)
                ->first()->update([
                    'status'=>2, //for done deal
                    'sawaq_user_id'=>$offer->sawaq_user_id,
                    'price'=>$offer->price,
                    'start_date'=>$now->format('Y-m-d'),
                    'end_date'=>$end->format('Y-m-d'),

                ]);
        }elseif ($order->order_type == 3){
            App\Order::where('id',$offer->order_id)
                ->first()->update([
                    'status'=>2, //for done deal
                    'sawaq_user_id'=>$offer->sawaq_user_id,
                    'price'=>$offer->price,


                ]);
        }



        $devicesTokens = App\UserDevice::where('user_id', $offer->sawaq_user_id)
            ->get()
            ->pluck('device_token')
            ->toArray();

        if ($devicesTokens) {
            sendMultiNotification("العروض", "لقد تم قبول عرضك" ,$devicesTokens);
        }
        
        saveNotification($offer->sawaq_user_id, "العروض" , '1', "لقد تم قبول عرضك" , $offer->order_id ,null);

        $my0rder= App\Order::where('id',$offer->order_id)->first();
        $end = Carbon::now()->addDays(3);
        $all_offers=App\SawaqOfferPrice::where('id','=', $id)->first()->update(['end_date'=>$end->format('Y-m-d')]);
        $all_offers=App\SawaqOfferPrice::where('id','!=', $id)->get();
        foreach ($all_offers as $data){
//            $devicesTokens = App\UserDevice::where('user_id', $data->sawaq_user_id)
//                ->get()
//                ->pluck('device_token')
//                ->toArray();
//
//            if ($devicesTokens) {
//                sendMultiNotification("العروض", "العميل وافق على عرض سواق اخر"." "."حافظ علي تقديم عرض سعر مناسب المرة القادمة" ,$devicesTokens);
//            }
//            saveNotification($data->professional_user_id, "العروض" , '1', "العميل وافق على عرض سواق اخر"." "."حافظ علي تقديم عرض سعر مناسب المرة القادمة");
//
            $data->delete();

        }


        App\OrderForSawaq::where('order_id',$offer->order_id)->where('user_id','=', $offer->sawaq_user_id)->first()->update(['status'=>2]);




            $all_data=[
                'order_type'=>intval($my0rder->order_type),
                'phone_number'=>User::find($request->user()->id)->phone_number,
                'username'=>User::find($request->user()->id)->name,
                'from_city_id'=>intval($my0rder->from_city_id),
                'from_city'=>App\City::find($my0rder->from_city_id)->name,
                'from_region_id'=>intval($my0rder->from_region_id),
                'from_region'=>App\City::find($my0rder->from_region_id)->name,
                'deliver_time'=>intval($my0rder->deliver_time),
                'from_time'=>$my0rder->from_time,
                'to_time'=>$my0rder->to_time,
                'to_region_id'=>intval($my0rder->to_region_id),
                'to_region'=> $my0rder->order_type == 2 ?   App\City::find($my0rder->to_region_id)->name : null,
                'status'=>$my0rder->status,
                'sawaq_user_id'=>$my0rder->sawaq_user_id,
                'price'=>$my0rder->price,
                'address'=>$my0rder->address,
                'start_date'=>$my0rder->start_date,
                'end_date'=>$my0rder->end_date,
                'id'=>intval($my0rder->id),
                'to_school'=>intval($my0rder->to_school),
                'school'=>$my0rder->order_type == 1 ? App\School::find($my0rder->to_school)->name : null,
                'user_id'=>intval($request->user()->id),



                'created_at'=>$my0rder->created_at->format('Y-m-d'),



            ];




        return $order
            ? ApiController::respondWithSuccess($all_data)
            : ApiController::respondWithServerErrorObject();
    }
    public function delete_sawaq_offers_price($id,Request $request){

        $offer=App\SawaqOfferPrice::find($id);
        $order = App\Order::find($offer->order_id);

        $devicesTokens = App\UserDevice::where('user_id', $offer->sawaq_user_id)
            ->get()
            ->pluck('device_token')
            ->toArray();

        if ($devicesTokens) {
            sendMultiNotification("العروض", "لقد تم رفض عرضك"   ,$devicesTokens);
        }
        saveNotification($offer->sawaq_user_id, "العروض" , '1', "لقد تم رفض عرضك");

        $offer->delete();

        return $offer
            ? ApiController::respondWithSuccess([])
            : ApiController::respondWithServerErrorArray();
    }

}
