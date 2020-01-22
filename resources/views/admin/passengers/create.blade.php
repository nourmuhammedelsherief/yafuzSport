@extends('admin.layouts.master')

@section('title')
    عدد الركاب
@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/admin/home">لوحة التحكم</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="/admin/passenger">عدد الركاب</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>اضافة عدد الركاب</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عدد الركاب
        <small>اضافة عدد الركاب</small>
    </h1>
@endsection

@section('content')

    <div class="row">

        <div class="col-md-8">
            <!-- BEGIN TAB PORTLET-->
            <form method="post" action="/admin/add/passenger" enctype="multipart/form-data">
                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>




                        <!-- BEGIN CONTENT -->

                            <!-- BEGIN CONTENT BODY -->
                            <div class="row">
                                <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet light bordered table-responsive">
                                    <div class="portlet-body form">
                                        <div class="form-horizontal" role="form">

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">عدد الركاب</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="number" class="form-control" placeholder="عدد الركاب" value="{{old('number')}}">
                                                    @if ($errors->has('number'))
                                                        <span class="help-block">
                                                           <strong style="color: red;">{{ $errors->first('number') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>



                                        </div>
                                    </div>
                                </div>
                                <!-- END SAMPLE FORM PORTLET-->


                            </div>


                            <!-- END CONTENT BODY -->

                        <!-- END CONTENT -->



                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green" value="حفظ" onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();">حفظ</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END TAB PORTLET-->





        </div>
    </div>

@endsection
