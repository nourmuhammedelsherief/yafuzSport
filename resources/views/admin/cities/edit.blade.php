@extends('admin.layouts.master')

@section('title')
    تعديل جميع المدن
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
                <a href="{{route('cities')}}">المدن</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض المدن</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض المدن
        <small>تعديل جميع المدن</small>
    </h1>
@endsection

@section('content')

    <div class="row">

        <div class="col-md-8">
            <!-- BEGIN TAB PORTLET-->
            <form method="post" action="{{route('updateCity' , $city->id)}}" enctype="multipart/form-data"  >
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
                                                    <label class="col-md-3 control-label">الدولة</label>
                                                    <div class="col-md-9">
                                                        <select name="country_id" class="form-control" required>
                                                            <option disabled="disabled" selected="true"> اختر الدوله </option>
                                                            @foreach($countries as $country)
                                                                <option value="{{$country->id}}" @if ($country->id == $city->country_id) selected @endif> {{$country->name}} </option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('country_id'))
                                                            <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('country_id') }}</strong>
                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END SAMPLE FORM PORTLET-->
                                <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet light bordered table-responsive">
                                    <div class="portlet-body form">
                                        <div class="form-horizontal" role="form">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">أسم المدينه </label>
                                                    <div class="col-md-9">
                                                        <input type="text" name="name" class="form-control" placeholder="الاسم" value="{{$city->name}}">
                                                        @if ($errors->has('name'))
                                                            <span class="help-block">
                                                                                   <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                                                                </span>
                                                        @endif
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

@section('scripts')
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>

@endsection
