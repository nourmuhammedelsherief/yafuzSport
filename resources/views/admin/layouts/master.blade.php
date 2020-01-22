<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{ app()->getLocale() }}" dir="rtl">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <meta name="X-CSRF-TOKEN" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href='https://fonts.googleapis.com/earlyaccess/droidarabickufi.css' rel='stylesheet' type='text/css'/>

    <link rel="stylesheet" href="{{ URL::asset('admin/css/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/simple-line-icons/simple-line-icons.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-rtl.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-switch-rtl.min.css') }}">
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN THEME GLOBAL STYLES -->
    <link rel="stylesheet" href="{{ URL::asset('admin/css/components-rounded-rtl.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/plugins-rtl.min.css') }}">
    <!-- END THEME GLOBAL STYLES -->

    <!-- BEGIN THEME LAYOUT STYLES -->
    <link rel="stylesheet" href="{{ URL::asset('admin/css/layout-rtl.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/darkblue-rtl.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/custom-rtl.min.css') }}">
    <!-- END THEME LAYOUT STYLES -->

    <link rel="stylesheet" href="{{ URL::asset('admin/css/login-rtl.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/custom.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/fontawesome-iconpicker.min.css') }}">
    <link href="{{asset('admin/css/colors.css')}}" rel="stylesheet" type="text/css">

    @yield('styles')



    {{--<link rel="shortcut icon" href="favicon.ico" />--}}
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-footer-fixed page-sidebar-closed-hide-logo page-content-white">
<div class="page-wrapper">
    <!-- BEGIN HEADER -->

@include('admin.layouts.header')

<!-- END HEADER -->
    <!-- BEGIN HEADER & CONTENT DIVIDER -->
    <div class="clearfix"> </div>
    <!-- END HEADER & CONTENT DIVIDER -->
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->

        @include('admin.layouts.sidebar')

        <div class="page-content-wrapper">
            <div class="page-content">

                @yield('page_header')

{{--                @include('layouts.alerts')--}}

                @yield('content')

            </div>
        </div>

    </div>
    <!-- BEGIN FOOTER -->
    <div class="page-footer">
        <div class="page-footer-inner"> {{ date('Y') }} &copy; تطوير بواسطة
            <a target="_blank" href="https://www.ws4it.com"> شركة تقني. </a>
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>

    <!-- END FOOTER -->
</div>
{{--<script src="https://www.gstatic.com/firebasejs/5.5.8/firebase.js"></script>--}}
{{--<script type="text/javascript" src="{{ URL::asset('website/js/config.js') }}"></script>--}}
<!--[if lt IE 9]>
<script src="{{ URL::asset('admin/js/respond.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/excanvas.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/ie8.fix.min.js') }}"></script>
<![endif]-->

<!-- BEGIN CORE PLUGINS -->
<script src="{{ URL::asset('admin/js/jquery.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/js.cookie.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/jquery.blockui.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/bootstrap-switch.min.js') }}"></script>
<!-- END CORE PLUGINS -->

<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="{{ URL::asset('admin/js/app.min.js') }}"></script>
<!-- END THEME GLOBAL SCRIPTS -->

<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="{{ URL::asset('admin/js/layout.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/demo.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/quick-sidebar.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/quick-nav.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/fontawesome-iconpicker.min.js') }}"></script>
<script src="{{ URL::asset('admin/js/notifications.js') }}"></script>
<script src="{{asset('admin/js/color/jscolor.js')}}"></script>

<!-- END THEME LAYOUT SCRIPTS -->
<script>
    $(document).ready(function()
    {
        $('#clickmewow').click(function()
        {
            $('#radio1003').attr('checked', 'checked');
        });
        $('.my_icon').iconpicker();
    })
</script>
<script>
    function delete_number() {

        var abc = firebase.database().ref('notifications');

        firebase.database().ref("notifications").on('value', snapshot => {
            $("#number_notification_all").empty();
            snapshot.forEach(snap => {

                abc.child(snap.key).update({
                    watch : 1,
                });
            });
        });
        document.getElementById('mySpan_notification').innerHTML ="";
        document.getElementById('mySpan_notification1').innerHTML ="";
    }
</script>
<script type="text/javascript">
    @if(session()->has('success'))
    toastr.success('{{session('success')}}')
    @endif
    @if(session()->has('error'))
    toastr.error('{{session('error')}}')
    @endif
</script>
@yield('scripts')

</body>

</html>
