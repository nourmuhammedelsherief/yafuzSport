@extends('admin.layouts.master')

@section('title')
    أخبار المجموعات
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
                <a href="{{url('/admin/groupNews')}}">أخبار المجموعات</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض أخبار المجموعات</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض أخبار المجموعات
        <small>اضافة جميع أخبار المجموعات</small>
    </h1>
@endsection

@section('content')

    <div class="row">

        <div class="col-md-12">
            <!-- BEGIN TAB PORTLET-->
            <form method="post" action="{{route('storeGroupNews')}}" enctype="multipart/form-data" >
                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-anchor font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase">إضافة الاخبار العامه</span>
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
                                                <label class="col-md-3 control-label">عنوان الخبر</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="title" class="form-control" placeholder="عنوان الخبر" value="{{old('title')}}" required>
                                                    @if ($errors->has('title'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('title') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3"> صوره الخبر </label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                            </div>
                                                            <div>
                                                            <span class="btn red btn-outline btn-file">
                                                                <span class="fileinput-new"> اختر الصورة </span>
                                                                <span class="fileinput-exists"> تغيير </span>
                                                                <input type="file" name="cover_image"> </span>
                                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> إزالة </a>



                                                            </div>
                                                        </div>
                                                        @if ($errors->has('cover_image'))
                                                            <span class="help-block">
                                                               <strong style="color: red;">{{ $errors->first('cover_image') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> أختر المجموعه</label>
                                                <div class="col-md-9">
                                                    <select name="group_id" class="form-control" required>
                                                        <option disabled selected="selected"> أختر المجموعه </option>
                                                        @foreach($groups as $group)
                                                            <option value="{{$group->id}}"> {{$group->name}} </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('group_id'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('group_id') }}</strong>
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
                                                            <option value="{{$city->id}}"> {{$city->name}} </option>
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
                                                <label class="col-md-3 control-label"> تفاصيل الخبر</label>
                                                <div class="col-md-9">
                                                    <textarea type="text" name="details" class="form-control" placeholder=" تفاصيل الخبر" value="{{old('details')}}"></textarea>
                                                    @if ($errors->has('details'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('details') }}</strong>
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
                                            <button type="submit" class="btn green btn-lg" value="حفط الخبر" onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();">حفظ</button>
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
