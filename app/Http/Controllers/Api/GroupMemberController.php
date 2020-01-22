<?php

namespace App\Http\Controllers\Api;

use App\City;
use App\Group;
use App\GroupMember;
use App\User;
use Validator;
use App\UserDevice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use phpDocumentor\Reflection\Types\Null_;

class GroupMemberController extends Controller
{
    /**
     * @check_city_admin
    */
    public function check_city_admin(Request $request , $id)
    {
        $group = Group::find($id);
        $group_admin = Group::whereCity_id($group->city_id)
            ->where('admin' , $request->user()->id)
            ->first();
        if($group_admin != null) {
            $success = ['key' => 'admin_phone',
                'value' => User::find($group->admin)->phone_number,
            ];
            return $group_admin
                ? ApiController::respondWithSuccess($success)
                : ApiController::respondWithServerErrorArray();
        }else {
            $successPermission = ['key'=>'message',
                'value'=> trans('عفوا ! لا  يمكنك التواصل مع مدير  المجموعة')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }
    }
    /**
     * @group_join_demand
    */
    public function group_join_demand(Request $request , $id)
    {
        $group = Group::find($id);
        $all=[];
        if($group != null)
        {
                $checkGroupMember = GroupMember::whereGroup_id($id)
                    ->where('user_id' , $request->user()->id)
                    ->where('is_join' , '1')
                    ->first();
                if ($checkGroupMember !== null)
                {
                    $successPermission = ['key'=>'message',
                        'value'=> trans('عفوا ! لقد قمت بتقديم طلب أنضمام لهذه المجموعه')
                    ];
                    return ApiController::respondWithErrorClient(array($successPermission));
                }else{
                    $oldGroupMember = GroupMember::whereGroup_id($id)
                        ->where('user_id' , $request->user()->id)
                        ->where('is_join' , '0')
                        ->first();
                    if ($oldGroupMember !== null)
                    {
                        $oldGroupMember->update([
                            'user_id'       => $request->user()->id,
                            'city_id'       => $group->city_id,
                            'group_id'      =>$id,
                            'is_join'       => '1',
                        ]);
                        $groupMember = GroupMember::whereGroup_id($id)
                            ->where('user_id' , $request->user()->id)
                            ->where('is_join' , '1')
                            ->first();
                        array_push($all,[
                            'id'        =>intval($groupMember->id),
                            'user_id'      =>intval($groupMember->user_id),
                            'user'  => User::find($groupMember->user_id)->name,
                            'city_id'   =>intval($groupMember->city_id),
                            'city'      =>City::find($groupMember->city_id)->name,
                            'group_id'   =>intval($groupMember->group_id),
                            'group'      =>Group::find($groupMember->group_id)->name,
                            'is_join'    =>intval($groupMember->is_join),
                            'created_at'=>$groupMember->created_at->format('Y-m-d'),
                        ]);
                        $devicesTokens = UserDevice::where('user_id', $group->admin)
                            ->get()
                            ->pluck('device_token')
                            ->toArray();
                        if ($devicesTokens) {
                            sendMultiNotification("المجموعات", "هناك طلب انضمام جديد الي مجموعتك" ,$devicesTokens);
                        }

                        saveNotification($group->admin, "المجموعات" , '5', "هناك طلب انضمام جديد الي مجموعتك" , $group->id , $group->city_id , $group->id);

                        return $groupMember
                            ? ApiController::respondWithSuccess($all)
                            : ApiController::respondWithServerErrorArray();
                    }else
                    {
                        $groupMember = GroupMember::create([
                            'user_id'       => $request->user()->id,
                            'city_id'       => $group->city_id,
                            'group_id'      =>$id,
                            'is_join'       => '1',
                        ]);

                        array_push($all,[
                            'id'        =>intval($groupMember->id),
                            'user_id'      =>intval($groupMember->user_id),
                            'user'  => User::find($groupMember->user_id)->name,
                            'city_id'   =>intval($groupMember->city_id),
                            'city'      =>City::find($groupMember->city_id)->name,
                            'group_id'   =>intval($groupMember->group_id),
                            'group'      =>Group::find($groupMember->group_id)->name,
                            'is_join'    =>intval($groupMember->is_join),
                            'created_at'=>$groupMember->created_at->format('Y-m-d'),
                        ]);


                        return $groupMember
                            ? ApiController::respondWithSuccess($all)
                            : ApiController::respondWithServerErrorArray();

                }
            }

        }else
        {
            $successPermission = ['key'=>'message',
                'value'=> trans('عفوا ! لا هذه المجموعه')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }

    }
    public function group_admin_add_member(Request $request , $group_id)
    {
         $rules = [
            'phone_number' =>'required'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));


        $user = User::wherePhone_number($request->phone_number)
            ->where('phone_number' , '!=' , $request->user()->phone_number)
            ->first();
        if ($user != null)
        {
            $user_id = User::wherePhone_number($request->phone_number)->first()->id;
            $group = Group::find($group_id);
            if ($group != null)
            {
                $all =[];
                $checkGroupMember = GroupMember::whereGroup_id($group_id)
                    ->where('user_id' , $user_id)
                    ->where('is_join' , '1')
                    ->where('accepted' , '1')
                    ->first();
                if ($checkGroupMember == null)
                {
                    $groupMember = GroupMember::create([
                        'user_id'       => $user_id,
                        'city_id'       => $group->city_id,
                        'group_id'      =>$group_id,
                        'is_join'       => '1',
                        'accepted'      =>'1'
                    ]);

                    array_push($all,[
                        'id'        =>intval($groupMember->id),
                        'user_id'      =>intval($groupMember->user_id),
                        'user'  => User::find($groupMember->user_id)->name,
                        'city_id'   =>intval($groupMember->city_id),
                        'city'      =>City::find($groupMember->city_id)->name,
                        'group_id'   =>intval($groupMember->group_id),
                        'group'      =>Group::find($groupMember->group_id)->name,
                        'is_join'    =>intval($groupMember->is_join),
                        'created_at'=>$groupMember->created_at->format('Y-m-d'),
                    ]);
                    $devicesTokens = UserDevice::where('user_id', $user_id)
                        ->get()
                        ->pluck('device_token')
                        ->toArray();
                    if ($devicesTokens) {
                        sendMultiNotification("المجموعات", "تم أضافتك الي  المجموعة $group->name" ,$devicesTokens);
                    }

                    saveNotification($user_id, "المجموعات" , '0', "تم أضافتك الي  المجموعة $group->name" , $group->id , $group->city_id , $group->id);

                    return $groupMember
                        ? ApiController::respondWithSuccess($all)
                        : ApiController::respondWithServerErrorArray();

                }else{
                    $successPermission = ['key'=>'message',
                        'value'=> trans(' !!!!!!!!!!! هذا المستخدم موجود بالفعل في  مجموعتك !!!!!!!!!!!')
                    ];
                    return ApiController::respondWithErrorClient(array($successPermission));
                }
            }else{
                $successPermission = ['key'=>'message',
                    'value'=> trans(' !!!!!!!!!!عفوا لا توجد هذة  المجموعة !!!!')
                ];
                return ApiController::respondWithErrorClient(array($successPermission));
            }

        }else
        {
            $successPermission = ['key'=>'message',
                'value'=> trans(' !!!!!!!!!!عفوا  هذا المستخدم لا يوجد في  التطبيق !!!!')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }

    }
    public function group_cancel_join_demand(Request $request , $id)
    {
        $group = Group::find($id);
        $checkGroupMember = GroupMember::whereGroup_id($id)
            ->where('user_id' , $request->user()->id)
            ->first();
        if ($checkGroupMember == null)
        {
            $successPermission = ['key'=>'message',
                'value'=> trans('عفوا ! لم تقم بتقديم طلب أنضمام لهذه المجموعه')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }else{
            GroupMember::whereGroup_id($id)
                ->where('user_id' , $request->user()->id)
                ->first()->update([
                    'user_id'       => $request->user()->id,
                    'city_id'       => $group->city_id,
                    'group_id'      =>$id,
                    'is_join'       => '0',
                    'accepted'      => '0',
                    'super_visor'   => '0'
                ]);
            $groupMember = GroupMember::whereGroup_id($id)
                ->where('user_id' , $request->user()->id)
                ->first();
            $all=[];
            array_push($all,[
                'id'        =>intval($groupMember->id),
                'user_id'      =>intval($groupMember->user_id),
                'user'  => User::find($groupMember->user_id)->name,
                'city_id'   =>intval($groupMember->city_id),
                'city'      =>City::find($groupMember->city_id)->name,
                'group_id'   =>intval($groupMember->group_id),
                'group'      =>Group::find($groupMember->group_id)->name,
                'is_join'    =>intval($groupMember->is_join),
                'created_at'=>$groupMember->created_at->format('Y-m-d'),
            ]);


            return $groupMember
                ? ApiController::respondWithSuccess($all)
                : ApiController::respondWithServerErrorArray();
        }
    }
    public function get_group_demands(Request $request , $id)
    {
        $group = Group::find($id);
        $groupMembers = GroupMember::whereGroup_id($group->id)
            ->where('accepted' , '0')
            ->where('user_id' , '!=' , $request->user()->id)
            ->where('is_join' , '1')
            ->get();
        $count= $groupMembers->count();
        if ($count > 0)
        {
            $currentPage =$request->page;
            $perPage=10;
            $currentPageItems1 = $groupMembers->slice(($currentPage - 1) * $perPage, $perPage);
            $new =[];
            foreach ($currentPageItems1 as $groupMember)
            {
                array_push($new,
                    [
                        'id'              =>intval($groupMember->id),
                        'user_id'         =>intval($groupMember->user_id),
                        'user'            =>User::find($groupMember->user_id)->name,
                        'ImgPath'         =>"/uploads/users/",
                        'userImage'       =>User::find($groupMember->user_id)->image,
                        'city_id'         =>intval($groupMember->city_id),
                        'city'            =>City::find($groupMember->city_id)->name,
                        'group_id'         =>intval($groupMember->group_id),
                        'group'            =>Group::find($groupMember->group_id)->name,
                        'created_at'      =>$group->created_at->format('d-m-Y')
                    ]);
            }
            $data=[];
            array_push($data , ['group_members'=> $new , 'count'=> $count]);
            return ApiController::respondWithSuccess($data);
        }else
        {
            $successPermission = ['key'=>'message',
                'value'=> trans('لا توجد طلبات أنضمام')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }

    }
    public function group_refuse_demand(Request $request , $id)
    {
        $group_demand = GroupMember::find($id);
        $group = Group::find($group_demand->group_id);
        if ($group->admin == $request->user()->id)
        {
            $successPermission = ['key'=>'message',
                'value'=> trans('تم الغاء طلب الانضمام  الي المجموعه ')
            ];
            $devicesTokens = UserDevice::where('user_id', $group_demand->user_id)
                ->get()
                ->pluck('device_token')
                ->toArray();
            if ($devicesTokens) {
                sendMultiNotification("المجموعات", "لقد تم رفض طلبك للانضمام" ,$devicesTokens);
            }

            saveNotification($group_demand->user_id, "المجموعات" , '0', "تم الغاء طلب الانضمام  الي المجموعه " , $group->id , $group->city_id ,$group->id);
            $group_demand->delete();
            return ApiController::respondWithSuccess(array($successPermission));

        }else
        {
            $errorsPermission = ['key'=>'message',
                'value'=> trans('عفوا ليس لديك صلاحيه لألغاء طلبات الانضمام الي هذه المجموعه')
            ];
            return ApiController::respondWithErrorClient(array($errorsPermission));
        }
    }
    public function group_accept_demand(Request $request , $id)
    {
        $group_demand = GroupMember::find($id);
        $group = Group::find($group_demand->group_id);
        if ($group->admin == $request->user()->id)
        {
            GroupMember::find($id)->update([
                'accepted' => '1',
                'is_join' => '1'
            ]);

            $successPermission = ['key'=>'message',
                'value'=> trans('تم أضافه العضو الي المجموعه بنجاح')
            ];
            $devicesTokens = UserDevice::where('user_id', $group_demand->user_id)
                ->get()
                ->pluck('device_token')
                ->toArray();
            if ($devicesTokens) {
                sendMultiNotification("المجموعات", "لقد تم الموافقه علي طلبك للانضمام" ,$devicesTokens);
            }

            saveNotification($group_demand->user_id, "المجموعات" , '0', "لقد تم الموافقه علي طلبك للانضمام" , $group->id , $group->city_id ,$group->id);
            return ApiController::respondWithSuccess(array($successPermission));

        }else
        {
            $errorsPermission = ['key'=>'message',
                'value'=> trans('عفوا ليس لديك صلاحيه لأضافه أعضاء  في  هذه المجموعه')
            ];
            return ApiController::respondWithErrorClient(array($errorsPermission));
        }
    }
    public function get_group_members(Request $request ,$id)
    {
        $group = Group::find($id);
        $groupMembers = GroupMember::whereGroup_id($group->id)
            ->where('accepted' , '1')
            ->get();
        $count= $groupMembers->count();
        $currentPage =$request->page;
        $perPage=10;
        $currentPageItems1 = $groupMembers->slice(($currentPage - 1) * $perPage, $perPage);
        $new =[];
        foreach ($currentPageItems1 as $groupMember)
        {
            array_push($new,
                [
                    'id'              =>intval($groupMember->id),
                    'user_id'         =>intval($groupMember->user_id),
                    'user'            =>User::find($groupMember->user_id)->name,
                    'ImgPath'         =>"/uploads/users/",
                    'userImage'       =>User::find($groupMember->user_id)->image,
                    'super_visor'     =>intval($groupMember->super_visor),
                    'city_id'         =>intval($groupMember->city_id),
                    'city'            =>City::find($groupMember->city_id)->name,
                    'group_id'        =>intval($groupMember->group_id),
                    'group'           =>Group::find($groupMember->group_id)->name,
                    'created_at'      =>$groupMember->created_at->format('d-m-Y')
                ]);
        }
        $data=[];
        array_push($data , ['group_members'=> $new , 'count'=> $count]);
        return ApiController::respondWithSuccess($data);
    }
    public function group_cancel_user(Request $request , $group_id , $user_id)
    {
        $group = Group::find($group_id);
        $user  = User::find($user_id);
        if ($group->admin == $request->user()->id)
        {
            $group_member = GroupMember::whereGroup_id($group_id)
                ->where('user_id' , $user_id)
                ->first();
            $group_member->delete();

            $successPermission = ['key'=>'message',
                'value'=> trans('تم أزاله العضو من المجموعه بنجاح')
            ];
            $devicesTokens = UserDevice::where('user_id', $user->id)
                ->get()
                ->pluck('device_token')
                ->toArray();
            if ($devicesTokens) {
                sendMultiNotification("المجموعات", " تم أزالتك من  المجموعة $group->name" ,$devicesTokens);
            }

            saveNotification($user->id, "المجموعات" , '6', " تم أزالتك من المجموعة $group->name" , $group->id , $group->city_id , $group->id);

            //return ApiController::respondWithErrorClient(array($successPermission));
            return ApiController::respondWithSuccess(array($successPermission));

        }else
        {
            $errorsPermission = ['key'=>'message',
                'value'=> trans('عفوا ليس لديك صلاحيه لازاله أعضاء  من  هذه المجموعه')
            ];
            return ApiController::respondWithErrorClient(array($errorsPermission));
        }

    }
    public function group_make_user_super_visor(Request $request , $group_id , $user_id)
    {
        $group = Group::find($group_id);
        $user  = User::find($user_id);
        if ($group->admin == $request->user()->id)
        {
            $group_member = GroupMember::whereGroup_id($group_id)
                ->where('user_id' , $user_id)
                ->first();
            $group_member->update([
                'super_visor' => '1',
                'accepted' , '1'
            ]);
            $devicesTokens = UserDevice::where('user_id', $user->id)
                ->get()
                ->pluck('device_token')
                ->toArray();
            if ($devicesTokens) {
                sendMultiNotification("المجموعات", "تم تعيينك كمشرف في  المجموعة $group->name" ,$devicesTokens);
            }

            saveNotification($user->id, "المجموعات" , '0', "تم تعيينك كمشرف في  المجموعة $group->name" , $group->id , $group->city_id , $group->id);


            $successPermission = ['key'=>'message',
                'value'=> trans('تم تعيين  العضو كمشرف الي المجموعه بنجاح')
            ];
            //return ApiController::respondWithErrorClient(array($successPermission));
            return ApiController::respondWithSuccess(array($successPermission));

        }else
        {
            $errorsPermission = ['key'=>'message',
                'value'=> trans('عفوا ليس لديك صلاحيه لتعيين مشرفين في  هذه المجموعه')
            ];
            return ApiController::respondWithErrorClient(array($errorsPermission));
        }

    }
    public function group_cancel_super_visor(Request $request , $group_id , $user_id)
    {
        $group = Group::find($group_id);
        $user  = User::find($user_id);
        if ($group->admin == $request->user()->id)
        {
            $group_member = GroupMember::whereGroup_id($group_id)
                ->where('user_id' , $user_id)
                ->first();
            $group_member->update([
                'super_visor' => '0',
                'accepted' , '1'
            ]);

            $successPermission = ['key'=>'message',
                'value'=> trans('تم ألغاء العضو كمشرف من المجموعه بنجاح')
            ];
            $devicesTokens = UserDevice::where('user_id', $user->id)
                ->get()
                ->pluck('device_token')
                ->toArray();
            if ($devicesTokens) {
                sendMultiNotification("المجموعات", "تم أزالتك كمشرف من  المجموعة $group->name" ,$devicesTokens);
            }

            saveNotification($user->id, "المجموعات" , '0', "تم أزالتك كمشرف من  المجموعة $group->name" , $group->id , $group->city_id , $group->id);
            //return ApiController::respondWithErrorClient(array($successPermission));
            return ApiController::respondWithSuccess(array($successPermission));

        }else
        {
            $errorsPermission = ['key'=>'message',
                'value'=> trans('عفوا ليس لديك صلاحيه لتعديل اعضاء المجموعه')
            ];
            return ApiController::respondWithErrorClient(array($errorsPermission));
        }

    }
    public function get_group_super_visors(Request $request , $id)
    {
        $group = GroupMember::whereGroup_id($id)
            ->where('super_visor' , '1')
            ->get();
        $count= $group->count();
        $currentPage =$request->page;
        $perPage=10;
        $currentPageItems1 = $group->slice(($currentPage - 1) * $perPage, $perPage);
        $new =[];
        foreach ($currentPageItems1 as $groupMember)
        {
            array_push($new,
                [
                    'id'              =>intval($groupMember->id),
                    'user_id'         =>intval($groupMember->user_id),
                    'user'            =>User::find($groupMember->user_id)->name,
                    'ImgPath'         =>"/uploads/users/",
                    'userImage'       =>User::find($groupMember->user_id)->image,
                    'city_id'         =>intval($groupMember->city_id),
                    'city'            =>City::find($groupMember->city_id)->name,
                    'group_id'         =>intval($groupMember->group_id),
                    'group'            =>Group::find($groupMember->group_id)->name,
                    'created_at'      =>$groupMember->created_at->format('d-m-Y')
                ]);
        }
        $data=[];
        array_push($data , ['group_super_visors'=> $new , 'count'=> $count]);
        return ApiController::respondWithSuccess($data);
    }
}
