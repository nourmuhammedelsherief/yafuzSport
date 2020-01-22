<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar navbar-collapse collapse">

        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>

            <li class="nav-item start active open" >
                <a href="/admin/home" class="nav-link nav-toggle">
                    <i class="icon-home"></i>
                    <span class="title">الرئيسية</span>
                    <span class="selected"></span>

                </a>
            </li>
            <li class="heading">
                <h3 class="uppercase">القائمة الجانبية</h3>
            </li>

            <li class="nav-item {{ strpos(URL::current(), 'admins') !== false ? 'active' : '' }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-users"></i>
                    <span class="title">المشرفين</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{ url('/admin/admins') }}" class="nav-link ">
                            <span class="title">عرض المشرفين</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/admins/create') }}" class="nav-link ">
                            <span class="title">اضافة مشرف</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/users') !== false ? 'active' : '' }}">
                <a href="/admin/users" class="nav-link ">
                    <i class="icon-user"></i>
                    <span class="title">المستخدمين</span>
                    <span class="pull-right-container">
            </span>

                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/public_notifications') !== false ? 'active' : '' }}">
                <a href="/admin/public_notifications" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">ألاشعارات العامه</span>
                    <span class="pull-right-container">
            </span>

                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/country') !== false ? 'active' : '' }}">
                <a href="/admin/country" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">الدول</span>
                    <span class="pull-right-container">
            </span>

                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/cities') !== false ? 'active' : '' }}">
                <a href="/admin/cities" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">المدن</span>
                    <span class="pull-right-container">
            </span>

                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/activities') !== false ? 'active' : '' }}">
                <a href="{{url('/admin/activities')}}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">الانشطه الرياضيه</span>
                    <span class="pull-right-container">
            </span>

                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/contact_us') !== false ? 'active' : '' }}">
                <a href="{{url('/admin/contact_us')}}" class="nav-link ">
                    <i class="icon-call-in"></i>
                    <span class="title"> أتصل بنا</span>
                    <span class="pull-right-container">
            </span>

                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/groups') !== false ? 'active' : '' }}">
                <a href="{{url('/admin/groups')}}" class="nav-link ">
                    <i class="icon-graduation"></i>
                    <span class="title">  المجموعات</span>
                    <span class="pull-right-container">
            </span>

                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/news') !== false ? 'active' : '' }}">
                <a href="{{url('/admin/news')}}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title">  الاخبار العامة</span>
                    <span class="pull-right-container">
            </span>

                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/groupNews') !== false ? 'active' : '' }}">
                <a href="{{url('/admin/groupNews')}}" class="nav-link ">
                    <i class="icon-layers"></i>
                    <span class="title"> أخبار المجموعات </span>
                    <span class="pull-right-container">
            </span>

                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/setting') !== false ? 'active' : '' }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">الاعدادات العامة</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="{{url('/admin/setting')}}" class="nav-link ">
                            <span class="title">اعدادات الموقع</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'admin/pages') !== false ? 'active' : '' }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">الصفحات</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="/admin/pages/about" class="nav-link ">
                            <span class="title">من نحن</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="/admin/pages/terms" class="nav-link ">
                            <span class="title">الشروط والاحكام</span>
                        </a>
                    </li>

                </ul>
            </li>



        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>
