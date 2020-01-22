@extends('admin.layouts.master')

@section('title')
    اعدادات الموقع
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/admin/home">لوحة التحكم</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="/admin/setting">اعدادات الموقع</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>تعديل اعدادات الموقع</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> اعدادات الموقع
        <small>تعديل اعدادات الموقع</small>
    </h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">

        <div class="col-md-8">
            <!-- BEGIN TAB PORTLET-->
            @if(count($errors))
                <ul class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            @endif
            <form action="{{url('admin/add/settings')}}" method="post">
                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

                                    <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
                                        <!-- BEGIN CONTENT BODY -->

            <div class="row">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-body form">
                        <div class="form-horizontal" role="form">
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label"> العنوان  </label>
                                    <div class="col-md-9">
                                         <input type="text" class="form-control" placeholder="عنوان الموقع" name="address" value="{{$settings->address}}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label"> البريد الالكتروني</label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" placeholder=" عنوان البريد الاكتروني" name="email" value="{{$settings->email}}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">رقم الهاتف</label>
                                    <div class="col-md-9">


                                            <input type="text" class="form-control" placeholder="رقم الهاتف" name="phone_number" value="{{$settings->phone_number}}" required>


                                    </div>
                                </div>





                            </div>

                        </div>
                    </div>
                </div>
                <!-- END SAMPLE FORM PORTLET-->


        </div>


            <!-- END CONTENT BODY -->
    </div>
                                    <!-- END CONTENT -->







                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green">حفظ</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END TAB PORTLET-->





        </div>
    </div>

@endsection

