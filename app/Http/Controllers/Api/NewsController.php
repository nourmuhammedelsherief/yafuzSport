<?php

namespace App\Http\Controllers\Api;

use App\City;
use App\Group;
use App\GroupMember;
use App\GroupNews;
use App\News;
use App\User;
use App\UserDevice;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    public function create_public_news(Request $request)
    {
        $user = User::find($request->user()->id);
        if($user->posts == 1){
            $rules = [
                'city_id'           => 'required|exists:cities,id',
                'title'             => 'required|max:255',
                'cover_image'       => 'required|mimes:jpeg,bmp,png,jpg|max:5000',
                'details'           => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
            $all=[];

                $news = News::create([
                    'cover_image'       => $request->file('cover_image') == null ? null : UploadImage($request->file('cover_image'), 'cover_image', '/uploads/cover_images'),
                    'title'             => $request->title,
                    'details'           =>$request->details,
                    'city_id'           =>$request->city_id,
                    'user_id'           =>$request->user()->id,
                ]);

                $userss = User::all();
                foreach ($userss as $userr)
            {
                $devicesTokens = UserDevice::where('user_id', $userr->id)
                    ->get()
                    ->pluck('device_token')
                    ->toArray();
                if ($devicesTokens) {
                    sendMultiNotification('الاخبار', "تم أنشاء خبر عام" ,$devicesTokens);
                }

                saveNotification( $userr->id, 'الاخبار' , '2', "تم أنشاء خبر عام" , $news->id, $news->city_id , null);
            }

                $newsss = News::whereUser_id($request->user()->id)->get();
                if ($newsss->count() == 30)
                {
                    User::find($request->user()->id)->update(['posts' => '0']);

                }else if ($newsss->count() == 60)
                {
                    User::find($request->user()->id)->update(['posts' => '0']);

                }else if ($newsss->count() == 90)
                {
                    User::find($request->user()->id)->update(['posts' => '0']);

                }else if ($newsss->count() == 120)
                {
                    User::find($request->user()->id)->update(['posts' => '0']);

                }else if ($newsss->count() == 150)
                {
                    User::find($request->user()->id)->update(['posts' => '0']);

                }
                array_push($all,[
                    'id'              =>intval($news->id),
                    'title'           =>$news->title,
                    'city_id'         =>intval($news->city_id),
                    'city'            =>City::find($news->city_id)->name,
                    'user_id'         =>intval($news->user_id),
                    'user'         =>User::find($news->user_id)->name,
                    'cover_imagePath' =>"/uploads/cover_images/",
                    'cover_image'     => $news->cover_image,
                    'details'         =>$news->details,
                    'created_at'      =>$news->created_at->format('Y-m-d'),
                ]);

            return $news
                ? ApiController::respondWithSuccess($all)
                : ApiController::respondWithServerErrorArray();
        }
        else{
            $errorsPermission = ['key'=>'message',
                'value'=> trans('عفوا ليس لديك صلاحيه للنشر برجاء التواصل مع الاداره حتي يمكنك النشر')
            ];
            return ApiController::respondWithErrorClient(array($errorsPermission));
        }
    }
    public function create_group_news(Request $request , $group_id)
    {
        $rules = [
            'city_id'           => 'required|exists:cities,id',
            'title'             => 'required|max:255',
            'cover_image'       => 'required|mimes:jpeg,bmp,png,jpg|max:5000',
            'details'           => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
        $all=[];
        $news = GroupNews::create([
            'cover_image'       => $request->file('cover_image') == null ? null : UploadImage($request->file('cover_image'), 'cover_image', '/uploads/cover_images'),
            'title'             => $request->title,
            'details'           =>$request->details,
            'city_id'           =>$request->city_id,
            'group_id'          =>$group_id,
            'user_id'           =>$request->user()->id,
        ]);
        $userss = GroupMember::whereGroup_id($group_id)->get();
        $group = Group::find($group_id);
        foreach ($userss as $userr)
        {
            $devicesTokens = UserDevice::where('user_id', $userr->user_id)
                ->get()
                ->pluck('device_token')
                ->toArray();
            if ($devicesTokens) {
                sendMultiNotification('الاخبار', "تم أنشاء خبر في  المجموعة $group->name" ,$devicesTokens);
            }

            saveNotification( $userr->user_id, 'الاخبار' , '1', "تم أنشاء خبر في  المجموعة $group->name" , $news->id , $group->city_id , $group->id);
        }
        array_push($all,[
            'id'              =>intval($news->id),
            'title'           =>$news->title,
            'group_id'         =>intval($news->group_id),
            'group'            =>Group::find($news->group_id)->name,
            'city_id'         =>intval($news->city_id),
            'city'            =>City::find($news->city_id)->name,
            'user_id'         =>intval($news->user_id),
            'user'         =>User::find($news->user_id)->name,
            'cover_imagePath' =>"/uploads/cover_images/",
            'cover_image'     => $news->cover_image,
            'details'         =>$news->details,
            'created_at'      =>$news->created_at->format('Y-m-d'),
        ]);
        return $news
            ? ApiController::respondWithSuccess($all)
            : ApiController::respondWithServerErrorArray();
    }

    public function get_public_news(Request $request , $id)
    {
        $news = News::whereCity_id($id)
            ->orderBy('created_at' , 'desc')
            ->get();
        $count= $news->count();
        $currentPage =$request->page;
        $perPage=10;
        $currentPageItems1 = $news->slice(($currentPage - 1) * $perPage, $perPage);
        $new =[];
        foreach ($news as $newss)
        {
            array_push($new,
                [
                    'id'              =>intval($newss->id),
                    'public_news'     =>intval($newss->public_news),
                    'group_news'      =>intval($newss->group_news),
                    'title'           =>$newss->title,
                    'city_id'         =>intval($newss->city_id),
                    'city'            =>City::find($newss->city_id)->name,
                    'user_id'         =>intval($newss->user_id),
                    'user'            =>User::find($newss->user_id)->name,
                    'userPhoto'       =>User::find($newss->user_id)->image,
                    'image'           =>'/uploads/images',
                    'CoverImagesPath' =>'/uploads/cover_images',
                    'cover_image'     =>$newss->cover_image,
                    'details'         =>$newss->details,
                    'created_at'      =>$newss->created_at->format('Y-m-d')
                ]);
        }
        $data=[];
        array_push($data , ['news'=> $new , 'count'=> $count]);
        return ApiController::respondWithSuccess($data);
    }
    public function get_public_news_by_id(Request $request , $id)
    {
        $newss = News::find($id);
        if($newss != null)
        {
            $new =[];
            array_push($new,
                [
                    'id'              =>intval($newss->id),
                    'public_news'     =>intval($newss->public_news),
                    'group_news'      =>intval($newss->group_news),
                    'title'           =>$newss->title,
                    'city_id'         =>intval($newss->city_id),
                    'city'            =>City::find($newss->city_id)->name,
                    'user_id'         =>intval($newss->user_id),
                    'user'            =>User::find($newss->user_id)->name,
                    'userPhoto'       =>User::find($newss->user_id)->image,
                    'image'           =>'/uploads/images',
                    'CoverImagesPath' =>'/uploads/cover_images',
                    'cover_image'     =>$newss->cover_image,
                    'details'         =>$newss->details,
                    'created_at'      =>$newss->created_at->format('Y-m-d')
                ]);
            $data=[];
            array_push($data , ['news'=> $new]);
            return ApiController::respondWithSuccess($data);
        }else{
            $successPermission = ['key'=>'message',
                'value'=> trans('عفوا !! لا يوجد هذا الخبر')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }

    }
    public function get_group_news(Request $request , $id)
    {
        $news = GroupNews::whereGroup_id($id)
            ->orderBy('created_at' , 'desc')
            ->get();
        $count= $news->count();
        $currentPage =$request->page;
        $perPage=10;
        $currentPageItems1 = $news->slice(($currentPage - 1) * $perPage, $perPage);
        $new =[];
        foreach ($currentPageItems1 as $newss)
        {
            array_push($new,
                [
                    'id'              =>intval($newss->id),
                    'title'           =>$newss->title,
                    'city_id'         =>intval($newss->city_id),
                    'user_id'         =>intval($newss->user_id),
                    'user'            =>User::find($newss->user_id)->name,
                    'userPhoto'       =>User::find($newss->user_id)->image,
                    'image'           =>'/uploads/images',
                    'city'            =>City::find($newss->city_id)->name,
                    'CoverImagesPath' =>'/uploads/cover_images',
                    'cover_image'     =>$newss->cover_image,
                    'details'         =>$newss->details,
                    'created_at'      =>$newss->created_at->format('Y-m-d')
                ]);
        }
        $data=[];
        array_push($data , ['news'=> $new , 'count'=> $count]);
        return ApiController::respondWithSuccess($data);
    }
    public function get_group_news_by_id(Request $request , $id)
    {
        $newss = GroupNews::find($id);
        $new = [];
        if($newss != null)
        {
            array_push($new,
                [
                    'id'              =>intval($newss->id),
                    'title'           =>$newss->title,
                    'city_id'         =>intval($newss->city_id),
                    'user_id'         =>intval($newss->user_id),
                    'user'            =>User::find($newss->user_id)->name,
                    'userPhoto'       =>User::find($newss->user_id)->image,
                    'image'           =>'/uploads/images',
                    'city'            =>City::find($newss->city_id)->name,
                    'CoverImagesPath' =>'/uploads/cover_images',
                    'cover_image'     =>$newss->cover_image,
                    'details'         =>$newss->details,
                    'created_at'      =>$newss->created_at->format('Y-m-d')
                ]);

              $data=[];
              array_push($data , ['news'=> $new]);
              return ApiController::respondWithSuccess($data);
        }else{
            $successPermission = ['key'=>'message',
                'value'=> trans('عفوا !! لا يوجد هذا الخبر')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }
    }
    public function check_group_users(Request $request ,$id)
    {
        $group = Group::find($id);
        $superVisor = GroupMember::whereGroup_id($group->id)
            ->where('user_id' , $request->user()->id)
            ->where('accepted' , '1')
            ->first();
        /**
         * @chech the @User Permission At this @Group
        */
        if ($group->admin == $request->user()->id)
        {
            $successPermission = ['key'=>'message',
                'value'=> trans('هذا المستخدم هو ادمن الجروب')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }elseif ($superVisor->super_visor == '1')
        {
            $successPermission = ['key'=>'message',
                'value'=> trans('هذا المستخدم هو مشرف في الجروب')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }else{
            $successPermission = ['key'=>'message',
                'value'=> trans('هذا المستخدم هو عضو فقط الجروب أخفيله الزر ي  ابراهيم ')
            ];
            return ApiController::respondWithErrorClient(array($successPermission));
        }
    }

    /**
     *   Edit Public News
    */
    public function edit_public_news(Request $request , $news_id){

        $news = News::find($news_id);
        if ($news != null)
        {
            if ($news->user_id == $request->user()->id)
            {
                $rules = [
                    'city_id'           => 'nullable|exists:cities,id',
                    'title'             => 'nullable|max:255',
                    'cover_image'       => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
                    'details'           => 'nullable',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
                $all=[];

                $news->update([
                    'cover_image'       => $request->file('cover_image') == null ? $news->cover_image : UploadImage($request->file('cover_image'), 'cover_image', '/uploads/cover_images'),
                    'title'             => $request->title == null ? $news->title :$request->title,
                    'details'           => $request->details == null ? $news->details : $request->details,
                    'city_id'           => $request->city_id == null ? $news->city_id : $request->city_id,
                    'user_id'           => $request->user()->id,
                ]);
                array_push($all,[
                    'id'              =>intval($news->id),
                    'title'           =>$news->title,
                    'city_id'         =>intval($news->city_id),
                    'city'            =>City::find($news->city_id)->name,
                    'user_id'         =>intval($news->user_id),
                    'user'         =>User::find($news->user_id)->name,
                    'cover_imagePath' =>"/uploads/cover_images/",
                    'cover_image'     => $news->cover_image,
                    'details'         =>$news->details,
                    'created_at'      =>$news->created_at->format('Y-m-d'),
                ]);

                return $news
                    ? ApiController::respondWithSuccess($all)
                    : ApiController::respondWithServerErrorArray();
            }
            else{
                $errorsPermission = ['key'=>'message',
                    'value'=> trans('!!!! عفوا ليس لديك صلاحيه لتعديل هذا الخبر ')
                ];
                return ApiController::respondWithErrorClient(array($errorsPermission));
            }
        }
        else{
            $errorsPermission = ['key'=>'message',
                'value'=> trans('!!!! عفوا لا يوجد هذا الخبر ')
            ];
            return ApiController::respondWithErrorClient(array($errorsPermission));
        }

    }

    /**
     *   Edit Group  NEWS
    */

    public function edit_group_news(Request $request , $news_id)
    {
        $group_news = GroupNews::find($news_id);
        if ($group_news != null)
        {
            if ($group_news->user_id == $request->user()->id)
            {
                $rules = [
                    'city_id'           => 'nullable|exists:cities,id',
                    'title'             => 'nullable|max:255',
                    'cover_image'       => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
                    'details'           => 'nullable',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
                $all=[];
                $group_news->update([
                    'cover_image'       => $request->file('cover_image') == null ? $group_news->cover_image : UploadImage($request->file('cover_image'), 'cover_image', '/uploads/cover_images'),
                    'title'             => $request->title == null ? $group_news->title : $request->title,
                    'details'           =>$request->details == null ? $group_news->details : $request->details,
                    'city_id'           =>$request->city_id == null ? $group_news->city_id : $request->city_id,
                    'group_id'          =>$group_news->group_id,
                    'user_id'           =>$request->user()->id,
                ]);
                array_push($all,[
                    'id'              =>intval($group_news->id),
                    'title'           =>$group_news->title,
                    'group_id'         =>intval($group_news->group_id),
                    'group'            =>Group::find($group_news->group_id)->name,
                    'city_id'         =>intval($group_news->city_id),
                    'city'            =>City::find($group_news->city_id)->name,
                    'user_id'         =>intval($group_news->user_id),
                    'user'         =>User::find($group_news->user_id)->name,
                    'cover_imagePath' =>"/uploads/cover_images/",
                    'cover_image'     => $group_news->cover_image,
                    'details'         =>$group_news->details,
                    'created_at'      =>$group_news->created_at->format('Y-m-d'),
                ]);
                return $group_news
                    ? ApiController::respondWithSuccess($all)
                    : ApiController::respondWithServerErrorArray();
            }else
            {
                $errorsPermission = ['key'=>'message',
                    'value'=> trans('!!!! عفوا ليس لديك صلاحيه لتعديل هذا الخبر ')
                ];
                return ApiController::respondWithErrorClient(array($errorsPermission));
            }
        }else{
            $errorsPermission = ['key'=>'message',
                'value'=> trans('!!!! عفوا لا يوجد هذا الخبر ')
            ];
            return ApiController::respondWithErrorClient(array($errorsPermission));
        }
    }
    public function delete_public_news(Request $request , $news_id)
    {
        $news = News::find($news_id);
        if ($news != null)
        {
            if ($news->user_id == $request->user()->id)
            {
                $news->delete();
                return $news
                    ? ApiController::respondWithSuccess('تم حذف  الخبر بنجاح')
                    : ApiController::respondWithServerErrorArray();
            }
            else{
                $errorsPermission = ['key'=>'message',
                    'value'=> trans('!!!! عفوا ليس لديك صلاحيه لحذف هذا الخبر ')
                ];
                return ApiController::respondWithErrorClient(array($errorsPermission));
            }
        }
        else{
            $errorsPermission = ['key'=>'message',
                'value'=> trans('!!!! عفوا لا يوجد هذا الخبر ')
            ];
            return ApiController::respondWithErrorClient(array($errorsPermission));
        }

    }
    public function delete_group_news(Request $request , $news_id)
    {
        $group_news = GroupNews::find($news_id);
        if ($group_news != null)
        {
            if ($group_news->user_id == $request->user()->id)
            {
                $group_news->delete();
                return $group_news
                    ? ApiController::respondWithSuccess('تم حذف  الخبر  من مجموعتك  بنجاح')
                    : ApiController::respondWithServerErrorArray();
            }else
            {
                $errorsPermission = ['key'=>'message',
                    'value'=> trans('!!!! عفوا ليس لديك صلاحيه لحذف هذا الخبر ')
                ];
                return ApiController::respondWithErrorClient(array($errorsPermission));
            }
        }else{
            $errorsPermission = ['key'=>'message',
                'value'=> trans('!!!! عفوا لا يوجد هذا الخبر ')
            ];
            return ApiController::respondWithErrorClient(array($errorsPermission));
        }
    }
    // get all  user group news
    public  function get_user_group_news(Request $request , $city_id)
    {
        $groupM = GroupMember::whereCity_id($city_id)
            ->where('accepted' , '1')
            ->where('user_id' , $request->user()->id)
            ->where('is_join' , '1')
            ->get();
        $count= $groupM->count();
        $currentPage =$request->page;
        $perPage=10;
        $currentPageItems1 = $groupM->slice(($currentPage - 1) * $perPage, $perPage);
        $new =[];
        foreach ($currentPageItems1 as $newss)
        {
            $group_news = GroupNews::whereGroup_id($newss->group_id)
                ->where('city_id' , $city_id)
                ->first();
            if ($group_news != null)
            {
                $group = Group::whereCity_id($city_id)
                    ->where('admin' , $request->user()->id)
                    ->where('active' , '1')
                    ->where('id' , $newss->group_id)
                    ->first();
                if($group == null)
                {
                    array_push($new,
                        [
                            'id'              =>intval($group_news->id),
                            'title'           =>$group_news->title,
                            'city_id'         =>intval($group_news->city_id),
                            'user_id'         =>intval($group_news->user_id),
                            'user'            =>User::find($group_news->user_id)->name,
                            'userPhoto'       =>User::find($group_news->user_id)->image,
                            'image'           =>'/uploads/images',
                            'city'            =>City::find($group_news->city_id)->name,
                            'CoverImagesPath' =>'/uploads/cover_images',
                            'cover_image'     =>$group_news->cover_image,
                            'details'         =>$group_news->details,
                            'created_at'      =>$group_news->created_at->format('Y-m-d')
                        ]);
                }
            }
        }
        foreach ($currentPageItems1 as $newss)
        {
            $group_news = GroupNews::whereGroup_id($newss->group_id)
                ->where('city_id' , $city_id)
                ->first();
            if ($group_news != null)
            {
                $group = Group::whereCity_id($city_id)
                    ->where('admin' , $request->user()->id)
                    ->where('active' , '1')
                    ->where('id' , $newss->group_id)
                    ->first();
                if($group != null)
                {
                    array_push($new,
                        [
                            'id'              =>intval($group_news->id),
                            'title'           =>$group_news->title,
                            'city_id'         =>intval($group_news->city_id),
                            'user_id'         =>intval($group_news->user_id),
                            'user'            =>User::find($group_news->user_id)->name,
                            'userPhoto'       =>User::find($group_news->user_id)->image,
                            'image'           =>'/uploads/images',
                            'city'            =>City::find($group_news->city_id)->name,
                            'CoverImagesPath' =>'/uploads/cover_images',
                            'cover_image'     =>$group_news->cover_image,
                            'details'         =>$group_news->details,
                            'created_at'      =>$group_news->created_at->format('Y-m-d')
                        ]);
                }
            }
        }

        $data=[];
        array_push($data , ['news'=> $new , 'count'=> $count]);
        return ApiController::respondWithSuccess($data);
    }
}
