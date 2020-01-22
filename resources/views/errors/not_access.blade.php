@extends('admin.layouts.master')

@section('title')
    Be right back
@endsection




@section('content')




    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light bordered" >

                <div class="portlet-body" style="text-align: center;">
                    <div class="title">لا تسطيع الدخول لانك لا تملك الصلاحية</div>
                    <br>
                    <div class="text-right" style="text-align: center;">
                        <a class="btn sbold green" href="{{ url('/admin/home') }}">عودة للرئيسية
                            <i class="fa fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>

@endsection
