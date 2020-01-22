<?php

namespace App\Http\Controllers\Api;

use App\Activity;
use App\City;
use App\Group;
use App\GroupChat;
use App\GroupMember;
use Validator;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class GroupsController extends Controller
{
    public function groups(Request $request)
    {
        $groups = Group::whereActive('1')->get();
        $count= $groups->count();
        if ($count > 0)
        {
            $currentPage =$request->page;
            $perPage=10;
            $currentPageItems1 = $groups->slice(($currentPage - 1) * $perPage, $perPage);
            $new =[];
            foreach ($currentPageItems1 as $group)
            {
                array_push($new,
                    [
                        'id'              =>intval($group->id),
                        'name'            =>$group->name,
                        'city_id'         =>intval($group->city_id),
                        'city'            =>City::find($group->city_id)->name,
                        'GroupImagesPath' =>'/uploads/groupImages',
                        'image'           =>$group->photo,
                        'activity_id'     =>intval($group->activity_id),
                        'activity'        =>Activity::find($group->activity_id)->name,
                        'admin_id'        =>intval($group->admin),
                        'admin'           =>User::find($group->admin)->name,
                        'about_me'        =>$group->about_me,
                        'created_at'      =>$group->created_at->format('d-m-Y')
                    ]);
            }
            $data=[];
            array_push($data , ['groups'=> $new , 'count'=> $count]);
            return ApiController::respondWithSuccess($data);
        }else{
            $successPermission = ['key'=>'message',
                'value'=> trans('لا توجد مجموعات')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }
    }
    public function get_groups_by_city_id(Request $request , $city_id)
    {
        $groups = Group::whereCity_id($city_id)
            ->where('admin' , '!=' , $request->user()->id)
            ->where('active' , '1')
            ->get();
        $count= $groups->count();
        if ($count > 0)
        {
            $currentPage =$request->page;
            $perPage=10;
            $currentPageItems1 = $groups->slice(($currentPage - 1) * $perPage, $perPage);
            $new =[];
            foreach ($currentPageItems1 as $group)
            {
                $is_join = GroupMember::whereGroup_id($group->id)->where('user_id' , $request->user()->id)->first();
                if ($is_join == null)
                {
                    $is_join = '0';
                }else
                {
                    $is_join = GroupMember::whereGroup_id($group->id)->where('user_id' , $request->user()->id)->first()->is_join;

                }
                array_push($new,
                    [
                        'id'              =>intval($group->id),
                        'name'            =>$group->name,
                        'city_id'         =>intval($group->city_id),
                        'city'            =>City::find($group->city_id)->name,
                        'GroupImagesPath' =>'/uploads/groupImages',
                        'image'           =>$group->photo,
                        'activity_id'     =>intval($group->activity_id),
                        'activity'        =>Activity::find($group->activity_id)->name,
                        'admin_id'        =>intval($group->admin),
                        'admin'           =>User::find($group->admin)->name,
                        'is_join'         =>intval($is_join),
                        'about_me'        =>$group->about_me,
                        'created_at'      =>$group->created_at->format('d-m-Y')
                    ]);
            }
            $data=[];
            array_push($data , ['groups'=> $new , 'count'=> $count]);
            return ApiController::respondWithSuccess($data);
        }else{
            $successPermission = ['key'=>'message',
                'value'=> trans('لا توجد مجموعات في هذه المدينه')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }


    }
    public function my_group(Request $request)
    {
        $groups = Group::whereAdmin($request->user()->id)
            ->where('active' , '1')
            ->get();
        $count= $groups->count();
        if ($count > 0)
        {
            $currentPage =$request->page;
            $perPage=10;
            $currentPageItems1 = $groups->slice(($currentPage - 1) * $perPage, $perPage);
            $new =[];
            foreach ($currentPageItems1 as $group)
            {
                array_push($new,
                    [
                        'id'              =>intval($group->id),
                        'name'            =>$group->name,
                        'city_id'         =>intval($group->city_id),
                        'city'            =>City::find($group->city_id)->name,
                        'GroupImagesPath' =>'/uploads/groupImages',
                        'image'           =>$group->photo,
                        'activity_id'     =>intval($group->activity_id),
                        'activity'        =>Activity::find($group->activity_id)->name,
                        'admin_id'        =>intval($group->admin),
                        'admin'           =>User::find($group->admin)->name,
                        'about_me'        =>$group->about_me,
                        'created_at'      =>$group->created_at->format('d-m-Y')
                    ]);
            }
            $data=[];
            array_push($data , ['groups'=> $new , 'count'=> $count]);
            return ApiController::respondWithSuccess($data);
        }else{
            $successPermission = ['key'=>'message',
                'value'=> trans('لم تقم بنشاء مجموعات حتي  الان')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }

    }
    public function super_visor_groups(Request $request , $city_id)
    {
        $groups = GroupMember::whereUser_id($request->user()->id)
            ->where('super_visor' , '1')
            ->where('city_id' ,$city_id)
            ->get();
        $count= $groups->count();
        if ($count > 0)
        {
            $currentPage =$request->page;
            $perPage=10;
            $currentPageItems1 = $groups->slice(($currentPage - 1) * $perPage, $perPage);
            $new =[];
            foreach ($currentPageItems1 as $group)
            {
                array_push($new,
                    [
                        'id'              =>intval(Group::find($group->group_id)->id),
                        'name'            =>Group::find($group->group_id)->name,
                        'city_id'         =>intval(Group::find($group->group_id)->city_id),
                        'city'            =>City::find(Group::find($group->group_id)->city_id)->name,
                        'GroupImagesPath' =>'/uploads/groupImages',
                        'image'           =>Group::find($group->group_id)->photo,
                        'activity_id'     =>intval(Group::find($group->group_id)->activity_id),
                        'activity'        =>Activity::find(Group::find($group->group_id)->activity_id)->name,
                        'admin_id'        =>intval(Group::find($group->group_id)->admin),
                        'admin'           =>User::find(Group::find($group->group_id)->admin)->name,
                        'about_me'        =>Group::find($group->group_id)->about_me,
                        'super_visor'     =>intval($group->super_visor),
                        'created_at'      =>Group::find($group->group_id)->created_at->format('d-m-Y')
                    ]);
            }
            $data=[];
            array_push($data , ['groups'=> $new , 'count'=> $count]);
            return ApiController::respondWithSuccess($data);
        }else{
            $successPermission = ['key'=>'message',
                'value'=> trans('لم تقم بنشاء مجموعات حتي  الان')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }

    }
    public function user_groups(Request $request , $city_id)
    {
        $groups = GroupMember::whereUser_id($request->user()->id)
            ->where('city_id' , $city_id)
            ->where('accepted' , '1')
            ->where('is_join' , '1')
            ->get();
//        dd($groups);
        $count= $groups->count();
        if ($count > 0)
        {
            $currentPage =$request->page;
            $perPage=10;
            $currentPageItems1 = $groups->slice(($currentPage - 1) * $perPage, $perPage);
            $new =[];
            foreach ($currentPageItems1 as $group)
            {
//                dd($group);
                $ggroup = Group::whereId($group->group_id)
                    ->where('admin' ,'!=', $request->user()->id)
                    ->where('active' , '1')
                    ->first();
                if ($ggroup != null)
                {
                    array_push($new,
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
                }
            }
            $data=[];
            array_push($data , ['groups'=> $new]);
            return ApiController::respondWithSuccess($data);
        }else{
            $successPermission = ['key'=>'message',
                'value'=> trans('لم تقم بالانضمام الي أي مجموعه حتي  الان')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }

    }
    public function create_group(Request $request)
    {
        $rules = [
            'name'                  => 'required|max:255',
            'city_id'               => 'required|exists:cities,id',
            'photo'                 => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'activity_id'           => 'required|exists:activities,id',
            'about_group'           => 'nullable'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        /**
         * check if the admin doing group  with the same city
         */
        $checkIfExistGroup = Group::whereCity_id($request->city_id)
            ->where('admin' , $request->user()->id)
            ->first();
        if ($checkIfExistGroup != null)
        {
            $successPermission = ['key'=>'message',
                'value'=> trans('عفوا ليس لديك صلاحيه بأنشاء مجموعه أخري في هذه المدينه')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }else
        {
            $all=[];

            $group = Group::create([
                'name'          => $request->name,
                'city_id'       => $request->city_id,
                'photo'         => $request->file('photo') == null ? null : UploadImage($request->file('photo'), 'photo', '/uploads/groupImages'),
                'gender'        => $request->gender,
                'activity_id'   =>$request->activity_id,
                'admin'         =>$request->user()->id,
                'about_me'      =>$request->about_me,
                'active'        =>'1',
            ]);

            array_push($all,[
                'id'        =>$group->id,
                'name'      =>$group->name,
                'city_id'   =>intval($group->city_id),
                'city'      =>City::find($group->city_id)->name,
                'photoPath' =>"/uploads/groupImages/",
                'photo'     => $group->photo,
                'about_me'  =>$group->about_me,
                'admin_id'  =>intval($group->admin),
                'admin'     =>User::find($group->admin)->name,
                'created_at'=>$group->created_at->format('Y-m-d'),
            ]);


            return $group
                ? ApiController::respondWithSuccess($all)
                : ApiController::respondWithServerErrorArray();
        }
    }
    public function check_groups_by_city_id(Request $request  , $id)
    {
        $group = Group::whereCity_id($id)
            ->where('admin' , $request->user()->id)
            ->where('active' , '1')
            ->first();
        if($group == null)
        {
            $successPermission = ['key'=>'message',
                'value'=> trans('عفوا ليس لديك مجموعات في هذه المدينه')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }else{
            $new =[];
            array_push($new,
                [
                    'id'              =>intval($group->id),
                    'name'            =>$group->name,
                    'city_id'         =>intval($group->city_id),
                    'city'            =>City::find($group->city_id)->name,
                    'GroupImagesPath' =>'/uploads/groupImages',
                    'image'           =>$group->photo,
                    'activity_id'     =>intval($group->activity_id),
                    'activity'        =>Activity::find($group->activity_id)->name,
                    'admin_id'        =>intval($group->admin),
                    'admin'           =>User::find($group->admin)->name,
                    'about_me'        =>$group->about_me,
                    'created_at'      =>$group->created_at->format('d-m-Y')
                ]);
            $data=[];
            array_push($data , ['group'=> $new]);
            return ApiController::respondWithSuccess($data);
        }
    }
    public function admin_change_group_photo(Request $request , $id)
    {
        $group = Group::find($id);
        if($group != null)
        {
            if ($group->admin == $request->user()->id)
            {
                $rules = [
                    'photo'     => 'required|mimes:jpeg,bmp,png,jpg|max:5000',
                ];

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails())
                    return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

                $updated =  $group->update([
                    'photo'         => $request->file('photo') == null ? null : UploadImage($request->file('photo'), 'photo', '/uploads/groupImages'),
                ]);

                $success = ['key'=>'Group_Photo',
                    'value'=> $group->photo
                ];
                return $updated
                    ? ApiController::respondWithSuccess($success)
                    : ApiController::respondWithServerErrorObject();
            }else
            {
                $successPermission = ['key'=>'message',
                    'value'=> trans('عفوا ليس لديك  صلاحيه لتغيير صوره المجموعه')
                ];
                return ApiController::respondWithErrorClient(array($successPermission));
            }
        }else{
            $successPermission = ['key'=>'message',
                'value'=> trans('عفوا لا توجد هذة المجموعه')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }
    }
    public function admin_change_group_name(Request $request , $id)
    {
        $group = Group::find($id);
        if ($group->admin == $request->user()->id)
        {
            $rules = [
                'name'     => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

            $updated =  $group->update([
                'name'         => $request->name,
            ]);

            $success = ['key'=>'Group_Name',
                'value'=> $group->name
            ];
            return $updated
                ? ApiController::respondWithSuccess($success)
                : ApiController::respondWithServerErrorObject();
        }else
        {
            $successPermission = ['key'=>'message',
                'value'=> trans('عفوا ليس لديك  صلاحيه لتغيير أسم المجموعه')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }
    }
    public function admin_change_group_activity(Request $request , $id)
    {
        $group = Group::find($id);
        if ($group->admin == $request->user()->id)
        {
            $rules = [
                'activity_id'           => 'required|exists:activities,id',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

            $updated =  $group->update([
                'activity_id'         => $request->activity_id,
            ]);

            $success = [
                'activity_id'=>intval($group->activity_id),
                'activity'=> Activity::find($group->activity_id)->name
            ];
            return $updated
                ? ApiController::respondWithSuccess($success)
                : ApiController::respondWithServerErrorObject();
        }else
        {
            $successPermission = ['key'=>'message',
                'value'=> trans('عفوا ليس لديك  صلاحيه لتغيير نشاط المجموعه')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }
    }
    public function admin_change_group_city(Request $request , $id)
    {
        $group = Group::find($id);
        if ($group->admin == $request->user()->id)
        {
            $rules = [
                'city_id'           => 'required|exists:cities,id',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));
            $check_group = Group::whereCity_id($request->city_id)
                ->where('admin' , $request->user()->id)
                ->first();
            if($check_group !== null)
            {
                $successPermission = ['key'=>'message',
                    'value'=> trans('عفوا توجد مجموعه أخري في هذه المدينه')
                ];
                return ApiController::respondWithErrorClient(array($successPermission));
            }else {
                $updated = $group->update([
                    'city_id' => $request->city_id,
                ]);

                $success = [
                    'city_id' => intval($group->city_id),
                    'city' => City::find($group->city_id)->name
                ];
                return $updated
                    ? ApiController::respondWithSuccess($success)
                    : ApiController::respondWithServerErrorObject();
            }
        }else
        {
            $successPermission = ['key'=>'message',
                'value'=> trans('عفوا ليس لديك  صلاحيه لتغيير مدينه المجموعه')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }
    }
    public function get_user_Admin_super_groups(Request $request , $city_id)
    {
        $adminGroups = Group::whereCity_id($city_id)
            ->where('admin' , $request->user()->id)
            ->get();
        $superGroups = GroupMember::whereUser_id($request->user()->id)
            ->where('super_visor' , '1')
            ->where('city_id' ,$city_id)
            ->get();
        $count= $adminGroups->count() + $superGroups->count();
        if ($count > 0)
        {
            $currentPage =$request->page;
            $perPage=10;
            $currentPageItems1 = $adminGroups->slice(($currentPage - 1) * $perPage, $perPage);
            $new =[];
            foreach ($currentPageItems1 as $group)
            {
                array_push($new,
                    [
                        'id'              =>intval($group->id),
                        'name'            =>$group->name,
                        'city_id'         =>intval($group->city_id),
                        'city'            =>City::find($group->city_id)->name,
                        'GroupImagesPath' =>'/uploads/groupImages',
                        'image'           =>$group->photo,
                        'activity_id'     =>intval($group->activity_id),
                        'activity'        =>Activity::find($group->activity_id)->name,
                        'admin_id'        =>intval($group->admin),
                        'admin'           =>User::find($group->admin)->name,
                        'about_me'        =>$group->about_me,
                        'created_at'      =>$group->created_at->format('d-m-Y')
                    ]);
            }
            $currentPageItems2 = $superGroups->slice(($currentPage - 1) * $perPage, $perPage);
            foreach ($currentPageItems2 as $group)
            {
                array_push($new,
                    [
                        'id'              =>intval(Group::find($group->group_id)->id),
                        'name'            =>Group::find($group->group_id)->name,
                        'city_id'         =>intval(Group::find($group->group_id)->city_id),
                        'city'            =>City::find(Group::find($group->group_id)->city_id)->name,
                        'GroupImagesPath' =>'/uploads/groupImages',
                        'image'           =>Group::find($group->group_id)->photo,
                        'activity_id'     =>intval(Group::find($group->group_id)->activity_id),
                        'activity'        =>Activity::find(Group::find($group->group_id)->activity_id)->name,
                        'admin_id'        =>intval(Group::find($group->group_id)->admin),
                        'admin'           =>User::find(Group::find($group->group_id)->admin)->name,
                        'about_me'        =>Group::find($group->group_id)->about_me,
//                        'super_visor'     =>intval($group->super_visor),
                        'created_at'      =>Group::find($group->group_id)->created_at->format('d-m-Y')
                    ]);
            }

            $data=[];
            array_push($data , ['groups'=> $new , 'count'=> $count]);
            return ApiController::respondWithSuccess($data);
        }else {
            $successPermission = ['key' => 'message',
                'value' => trans('لم تقم بنشاء مجموعات حتي  الان')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }
    }
    public function get_user_Admin_member_groups(Request $request , $city_id)
    {
        $adminGroups = Group::whereCity_id($city_id)
            ->where('admin' , $request->user()->id)
            ->where('active' , '1')
            ->get();
        $superGroups = GroupMember::whereUser_id($request->user()->id)
            ->where('city_id' ,$city_id)
            ->where('accepted' , '1')
            ->where('is_join' , '1')
            ->get();
        $count= $adminGroups->count() + $superGroups->count();
        if ($count > 0)
        {
            $currentPage =$request->page;
            $perPage=10;
            $currentPageItems1 = $adminGroups->slice(($currentPage - 1) * $perPage, $perPage);
            $new =[];
            foreach ($currentPageItems1 as $group)
            {
                array_push($new,
                    [
                        'id'              =>intval($group->id),
                        'name'            =>$group->name,
                        'city_id'         =>intval($group->city_id),
                        'city'            =>City::find($group->city_id)->name,
                        'GroupImagesPath' =>'/uploads/groupImages',
                        'image'           =>$group->photo,
                        'activity_id'     =>intval($group->activity_id),
                        'activity'        =>Activity::find($group->activity_id)->name,
                        'admin_id'        =>intval($group->admin),
                        'admin'           =>User::find($group->admin)->name,
                        'about_me'        =>$group->about_me,
                        'created_at'      =>$group->created_at->format('d-m-Y')
                    ]);
            }
            $currentPageItems2 = $superGroups->slice(($currentPage - 1) * $perPage, $perPage);
            foreach ($currentPageItems2 as $group)
            {
                array_push($new,
                    [
                        'id'              =>intval(Group::find($group->group_id)->id),
                        'name'            =>Group::find($group->group_id)->name,
                        'city_id'         =>intval(Group::find($group->group_id)->city_id),
                        'city'            =>City::find(Group::find($group->group_id)->city_id)->name,
                        'GroupImagesPath' =>'/uploads/groupImages',
                        'image'           =>Group::find($group->group_id)->photo,
                        'activity_id'     =>intval(Group::find($group->group_id)->activity_id),
                        'activity'        =>Activity::find(Group::find($group->group_id)->activity_id)->name,
                        'admin_id'        =>intval(Group::find($group->group_id)->admin),
                        'admin'           =>User::find(Group::find($group->group_id)->admin)->name,
                        'about_me'        =>Group::find($group->group_id)->about_me,
//                        'super_visor'     =>intval($group->super_visor),
                        'created_at'      =>Group::find($group->group_id)->created_at->format('d-m-Y')
                    ]);
            }

            $data=[];
            array_push($data , ['groups'=> $new , 'count'=> $count]);
            return ApiController::respondWithSuccess($data);
        }else {
            $successPermission = ['key' => 'message',
                'value' => trans('لم تقم بنشاء مجموعات حتي  الان')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }
    }
    public function group_messages(Request $request , $group_id)
    {
        $groupMessages = GroupChat::whereGroup_id($group_id)
            ->orderBy('id' , 'desc')
            ->get();
        $count= $groupMessages->count();
        if ($count > 0)
        {
            $currentPage =$request->page;
            $perPage=10;
            $currentPageItems1 = $groupMessages->slice(($currentPage - 1) * $perPage, $perPage);
            $new =[];
            foreach ($groupMessages as $message)
            {
                 if($message->user_photo == "null")
                {
                    $photo = 'http://yafuzsport.com/uploads/defaults/default_photo.jpeg';
                }else{
                    $photo = $message->user_photo;
                }
                array_push($new,
                    [
                        'id'              =>intval($message->id),
                        'message'         =>$message->message,
                        'name'            =>$message->name,
                        'group_id'        =>intval($message->group_id),
                        'user_id'         =>intval($message->user_id),
                        'user_photo'      =>$photo,
                        'voice'           =>'http://yafuzsport.com/uploads/voice/voice.mpeg',
                        'created_at'      =>$message->created_at->format('d-m-Y')
                    ]);
            }
            $data=[];
            array_push($data , ['groupMessages'=> $new , 'count'=> $count]);
            return ApiController::respondWithSuccess($data);
        }else {
            $successPermission = ['key' => 'message',
                'value' => trans('لا توجد رسايل في هذه المجموعه')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }
    }
    public function all_user_groups(Request $request , $city_id)
    {
        $groups = GroupMember::whereUser_id($request->user()->id)
            ->where('city_id' , $city_id)
            ->where('accepted' , '1')
            ->where('is_join' , '1')
            ->get();
//        dd($groups);
        $count= $groups->count();
        if ($count > 0)
        {
            $currentPage =$request->page;
            $perPage=10;
            $currentPageItems1 = $groups->slice(($currentPage - 1) * $perPage, $perPage);
            $new =[];
            foreach ($currentPageItems1 as $group)
            {
//                dd($group);
                $ggroup = Group::whereId($group->group_id)
                    ->where('admin' ,'!=', $request->user()->id)
                    ->where('active' , '1')
                    ->first();
                if ($ggroup != null)
                {
                    array_push($new,
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
                }
            }
            foreach ($currentPageItems1 as $group)
            {
//                dd($group);
                $ggroup = Group::whereId($group->group_id)
                    ->where('admin' , $request->user()->id)
                    ->where('active' , '1')
                    ->first();
                if ($ggroup != null)
                {
                    array_push($new,
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
                }
            }
            $data=[];
            array_push($data , ['groups'=> $new]);
            return ApiController::respondWithSuccess($data);
        }else{
            $successPermission = ['key'=>'message',
                'value'=> trans('لم تقم بالانضمام الي أي مجموعه حتي  الان')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }
    }
    /**
     *  get chat voice
     * @get_chat_voice
     */
    public function get_chat_voice()
    {
        $data = [
            'voice_link' => 'http://yafuzsport.com/uploads/voice/voice.mp3',
        ];
        return ApiController::respondWithSuccess($data);
    }
    public  function get_group_by_id($id)
    {
        $group = Group::find($id);
        $new = [];
        if ($group != null)
        {
            array_push($new,
                [
                    'id'              =>intval($group->id),
                    'name'            =>$group->name,
                    'city_id'         =>intval($group->city_id),
                    'city'            =>City::find($group->city_id)->name,
                    'GroupImagesPath' =>'/uploads/groupImages',
                    'image'           =>$group->photo,
                    'activity_id'     =>intval($group->activity_id),
                    'activity'        =>Activity::find($group->activity_id)->name,
                    'admin_id'        =>intval($group->admin),
                    'admin'           =>User::find($group->admin)->name,
                    'about_me'        =>$group->about_me,
                    'created_at'      =>$group->created_at->format('d-m-Y')
                ]);
        $data=[];
        array_push($data , ['group'=> $new]);
        return ApiController::respondWithSuccess($data);
        }else{
            $successPermission = ['key'=>'message',
                'value'=> trans('عفوا !! لا توجد هذه المجموعة')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }
    }

}
