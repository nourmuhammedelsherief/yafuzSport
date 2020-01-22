<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="">
                <img src="{{ URL::asset('images/logo-tq.png') }}" style="width: 104px;" alt="logo" class="logo-default" /> </a>
            <div class="menu-toggler sidebar-toggler">
                <span></span>
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <!-- BEGIN NOTIFICATION DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after "dropdown-extended" to change the dropdown styte -->
                <!-- DOC: Apply "dropdown-hoverable" class after below "dropdown" and remove data-toggle="dropdown" data-hover="dropdown" data-close-others="true" attributes to enable hover dropdown mode -->
                <!-- DOC: Remove "dropdown-hoverable" and add data-toggle="dropdown" data-hover="dropdown" data-close-others="true" attributes to the below A element with dropdown-toggle class -->
                <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar" onclick="delete_number();">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <i class="icon-bell"></i>
                        <span class="badge badge-default" id="mySpan_notification1"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="external">
                            <h3>
                                <span class="bold" id="mySpan_notification"></span> إشعارات جديدة
                            </h3>
                            <a href="{{url('admin/notification')}}">مشاهدة الكل</a>
                        </li>
                        <li>
                            <ul id="number_notification" class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">

                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- END NOTIFICATION DROPDOWN -->
                <!-- BEGIN INBOX DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                {{--<li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">--}}
                    {{--<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">--}}
                        {{--<i class="icon-envelope-open"></i>--}}
                        {{--<span class="badge badge-default"> 4 </span>--}}
                    {{--</a>--}}
                    {{--<ul class="dropdown-menu">--}}
                        {{--<li class="external">--}}
                            {{--<h3>لديك--}}
                                {{--<span class="bold">4 رسائل جديدة</span></h3>--}}
                            {{--<a href="#">مشاهدة الكل</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">--}}
                                {{--<li>--}}
                                    {{--<a href="#">--}}
                                        {{--<span class="photo">--}}
                                            {{--<img src="{{ URL::asset('admin/img/avatar3.jpg') }}" class="img-circle" alt="">--}}
                                        {{--</span>--}}
                                        {{--<span class="subject">--}}
                                            {{--<span class="from"> محمد احمد </span>--}}
                                            {{--<span class="time">16 دقيقة </span>--}}
                                        {{--</span>--}}
                                        {{--<span class="message"> محتوى الرسالة محتوى الرسالة محتوى الرسالة محتوى الرسالة ... </span>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#">--}}
                                        {{--<span class="photo">--}}
                                            {{--<img src="{{ URL::asset('admin/img/avatar1.jpg') }}" class="img-circle" alt="">--}}
                                        {{--</span>--}}
                                        {{--<span class="subject">--}}
                                            {{--<span class="from"> عبد الله محمود </span>--}}
                                            {{--<span class="time">2 ساعة  </span>--}}
                                        {{--</span>--}}
                                        {{--<span class="message"> محتوى الرسالة محتوى الرسالة محتوى الرسالة محتوى الرسالة .. </span>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#">--}}
                                        {{--<span class="photo">--}}
                                            {{--<img src="{{ URL::asset('admin/img/avatar3.jpg') }}" class="img-circle" alt="">--}}
                                        {{--</span>--}}
                                        {{--<span class="subject">--}}
                                            {{--<span class="from"> سامي علي </span>--}}
                                            {{--<span class="time">3 ايام</span>--}}
                                        {{--</span>--}}
                                        {{--<span class="message"> محتوى الرسالة محتوى الرسالة محتوى الرسالة محتوى الرسالة ... </span>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#">--}}
                                        {{--<span class="photo">--}}
                                            {{--<img src="{{ URL::asset('admin/img/avatar1.jpg') }}" class="img-circle" alt="">--}}
                                        {{--</span>--}}
                                        {{--<span class="subject">--}}
                                            {{--<span class="from"> محمد علي </span>--}}
                                            {{--<span class="time">5 ايام  </span>--}}
                                        {{--</span>--}}
                                        {{--<span class="message"> محتوى الرسالة محتوى الرسالة محتوى الرسالة محتوى الرسالة .. </span>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                <!-- END INBOX DROPDOWN -->
                <!-- BEGIN TODO DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                {{--<li class="dropdown dropdown-extended dropdown-tasks" id="header_task_bar">--}}
                    {{--<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">--}}
                        {{--<i class="icon-calendar"></i>--}}
                        {{--<span class="badge badge-default"> 3 </span>--}}
                    {{--</a>--}}
                    {{--<ul class="dropdown-menu extended tasks">--}}
                        {{--<li class="external">--}}
                            {{--<h3>لديك--}}
                                {{--<span class="bold">3 إحصائيات</span></h3>--}}
                            {{--<a href="app_todo.html">مشاهدة الكل</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">--}}
                                {{--<li>--}}
                                    {{--<a href="javascript:;">--}}
                                        {{--<span class="task">--}}
                                            {{--<span class="desc">عدد طلبات الحجز المقبولة </span>--}}
                                            {{--<span class="percent">70%</span>--}}
                                        {{--</span>--}}
                                        {{--<span class="progress">--}}
                                            {{--<span style="width: 70%;" class="progress-bar progress-bar-success" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">--}}
                                                {{--<span class="sr-only">70% مقبول</span>--}}
                                            {{--</span>--}}
                                        {{--</span>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="javascript:;">--}}
                                        {{--<span class="task">--}}
                                            {{--<span class="desc">عدد الطلبات قيد الانتظار</span>--}}
                                            {{--<span class="percent">25%</span>--}}
                                        {{--</span>--}}
                                        {{--<span class="progress">--}}
                                            {{--<span style="width: 25%;" class="progress-bar progress-bar-danger" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">--}}
                                                {{--<span class="sr-only">25% قيد الانتظار</span>--}}
                                            {{--</span>--}}
                                        {{--</span>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="javascript:;">--}}
                                        {{--<span class="task">--}}
                                            {{--<span class="desc">عدد الطلبات المرفوضة</span>--}}
                                            {{--<span class="percent">5%</span>--}}
                                        {{--</span>--}}
                                        {{--<span class="progress">--}}
                                            {{--<span style="width: 5%;" class="progress-bar progress-bar-warning" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100">--}}
                                                {{--<span class="sr-only">5% مرفوضة</span>--}}
                                            {{--</span>--}}
                                        {{--</span>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                <!-- END TODO DROPDOWN -->
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <img alt="" class="img-circle" src="" />
                        <span class="username username-hide-on-mobile"> <?php if(Auth::guard('admin')->check()) { echo Auth::guard('admin')->user()->name; } ?> </span>

                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="/admin/profile">
                                <i class="icon-user"></i> صفحتي الشخصية </a>
                        </li>
                        <li>
                            <a href="/admin/profileChangePass">
                                <i class="icon-user"></i> تغيير كلمة المرور </a>
                        </li>
                        {{--<li>--}}
                        {{--<a href="app_calendar.html">--}}
                        {{--<i class="icon-calendar"></i> My Calendar </a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                        {{--<a href="app_inbox.html">--}}
                        {{--<i class="icon-envelope-open"></i> My Inbox--}}
                        {{--<span class="badge badge-danger"> 3 </span>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                        {{--<a href="app_todo.html">--}}
                        {{--<i class="icon-rocket"></i> My Tasks--}}
                        {{--<span class="badge badge-success"> 7 </span>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        {{--<li class="divider"> </li>--}}
                        {{--<li>--}}
                        {{--<a href="page_user_lock_1.html">--}}
                        {{--<i class="icon-lock"></i> Lock Screen </a>--}}
                        {{--</li>--}}
                        <li>
                            <a onclick="document.getElementById('logout_form').submit()">
                                <i class="icon-key"></i> تسجيل الخروج
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
            {{--<li class="dropdown dropdown-quick-sidebar-toggler">--}}
            {{--<a href="javascript:;" class="dropdown-toggle">--}}
            {{--<i class="icon-logout"></i>--}}
            {{--</a>--}}
            {{--</li>--}}
            <!-- END QUICK SIDEBAR TOGGLER -->
            </ul>
        </div>

        <form style="display: none;" id="logout_form" action="{{ route('admin.logout') }}" method="post">
            {!! csrf_field() !!}
        </form>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>