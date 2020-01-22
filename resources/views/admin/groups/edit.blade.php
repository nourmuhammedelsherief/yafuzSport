@extends('admin.layouts.master')

@section('title')
    المجموعات
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
@endsection


@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('/admin/home')}}">لوحة التحكم</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/admin/groups')}}">المجموعات</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض المجموعات</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض المجموعات
        <small>تعديل جميع المجموعات</small>
    </h1>
@endsection

@section('content')

    <div class="row">

        <div class="col-md-12">
            <!-- BEGIN TAB PORTLET-->
            <form method="post" action="{{route('updateGroup' , $group->id)}}" enctype="multipart/form-data" >
                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-anchor font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase">تعديل المجموعه</span>
                        </div>

                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered table-responsive">
                                <div class="portlet-body form">
                                    <div class="form-horizontal" role="form">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">الاسم</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="name" class="form-control" placeholder="الاسم" value="{{$group->name}}" required>
                                                    @if ($errors->has('name'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3"> صوره المجموعه </label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                                @if($group->photo !==null)
                                                                    <img   src='{{ asset("uploads/groupImages/$group->photo") }}'>
                                                                @endif
                                                            </div>
                                                            <div>
                                                            <span class="btn red btn-outline btn-file">
                                                                <span class="fileinput-new"> اختر الصورة </span>
                                                                <span class="fileinput-exists"> تغيير </span>
                                                                <input type="file" name="photo" value="{{$group->photo}}"> </span>
                                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> إزالة </a>



                                                            </div>
                                                        </div>
                                                        @if ($errors->has('photo'))
                                                            <span class="help-block">
                                                               <strong style="color: red;">{{ $errors->first('photo') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">النشاط الرياضي</label>
                                                <div class="col-md-9">
                                                    <select name="activity_id" class="form-control" required>
                                                        <option disabled selected="selected"> أختر النشاط الرياضي </option>
                                                        @foreach($activities as $activity)
                                                            <option value="{{$activity->id}}" @if($group->activity_id == $activity->id) selected @endif> {{$activity->name}} </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('activity_id'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('activity_id') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> أختر المدينه</label>
                                                <div class="col-md-9">
                                                    <select name="city_id" class="form-control" required>
                                                        <option disabled selected="selected"> أختر المدينه </option>
                                                        @foreach($cities as $city)
                                                            <option value="{{$city->id}}" @if($city->id == $group->city_id) selected  @endif> {{$city->name}} </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('city_id'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('city_id') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> أختر الادمن</label>
                                                <div class="col-md-9">
                                                    <select name="admin" class="form-control" required>
                                                        <option disabled selected="selected"> أختر الادمن </option>
                                                        @foreach($users as $user)
                                                            <option value="{{$user->id}}" @if($user->id == $group->admin) selected @endif> {{$user->name}} </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('admin'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('admin') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">عن المجموعه</label>
                                                <div class="col-md-9">
                                                    <textarea type="text" name="about_me" class="form-control" placeholder="عن المجموعه" value="{{$group->about_me}}">{{$group->about_me}}</textarea>
                                                    @if ($errors->has('about_me'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('about_me') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn green btn-lg" value="حفط المجموعه" onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();">حفظ</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END SAMPLE FORM PORTLET-->

                        </div>


                        <!-- END CONTENT BODY -->

                        <!-- END CONTENT -->


                    </div>
                </div>

            </form>
            <!-- END TAB PORTLET-->
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>

@endsection
