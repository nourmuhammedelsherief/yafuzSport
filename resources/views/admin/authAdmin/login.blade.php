@extends('admin.authAdmin.master')

@section('title')
    تسجيل الدخول
@endsection
@section('content')
    @if (session('An_error_occurred'))
        <div class="alert alert-success">
            {{ session('An_error_occurred') }}
        </div>
    @endif
    @if (session('warning_login'))
        <div class="alert alert-danger">
            {{ session('warning_login') }}
        </div>
    @endif
    <!-- BEGIN LOGIN FORM -->
    <form method="POST" action="{{route('admin.login.submit')}}">
        @csrf

        <h3 class="form-title font-green">تسجيل الدخول</h3>

        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">االبريد الالكتروني</label>
            <input class="form-control form-control-solid placeholder-no-fix{{ $errors->has('email') ? ' is-invalid' : '' }}" type="text" autocomplete="off" placeholder="البريد الالكتروني" name="email" value="{{ old('email') }}"  required autofocus />
            @if ($errors->has('email'))

                <div class="alert alert-danger">
                    <button class="close" data-close="alert"></button>
                    <span> {{ $errors->first('email') }}</span>
                </div>
            @endif
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">كلمة المرور</label>
            <input class="form-control form-control-solid placeholder-no-fix{{ $errors->has('email') ? ' is-invalid' : '' }}" type="password" autocomplete="off" placeholder="كلمة المرور" name="password" required  />
            @if ($errors->has('password'))
                <div class="alert alert-danger">
                    <button class="close" data-close="alert"></button>
                    <span> {{ $errors->first('password') }}</span>
                </div>
            @endif
        </div>
        {{--<div class="form-group">--}}
            {{--<div class="g-recaptcha" data-sitekey="6Ldd7IkUAAAAAPJRA62wUutPjglEGFKKRvnA2QSJ"></div>--}}
            {{--@if ($errors->has('g-recaptcha-response'))--}}

                {{--<div class="alert alert-danger">--}}
                    {{--<span> {{ $errors->first('g-recaptcha-response') }}</span>--}}
                {{--</div>--}}
            {{--@endif--}}
        {{--</div>--}}
        <div class="form-actions">
            <button type="submit" class="btn green uppercase">تسجيل الدخول</button>
            <label class="rememberme check mt-checkbox mt-checkbox-outline">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />تذكرني
                <span></span>
            </label>
            <a href="{{ route('admin.password.request') }}"  class="forget-password">هل نسيت كلمة المرور؟</a>
        </div>


    </form>
    <!-- END LOGIN FORM -->



@endsection
