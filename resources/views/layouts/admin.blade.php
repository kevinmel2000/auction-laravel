<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>Auction | @yield('title')</title>

        <link href="{{ URL::asset('assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/uniform.default.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/bootstrap-dialog.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/docs.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/bootstrap-colorpicker.css') }}" rel="stylesheet" type="text/css" />

        <link href="{{ URL::asset('assets/css/layout.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/darkblue.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/components.css') }}" rel="stylesheet" type="text/css" />

        <link href="{{ URL::asset('assets/css/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css" />

        <link href="{{ URL::asset('assets/css/flaticon.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />

        @stack('css')
    </head>
    <body class="page-header-fixed page-quick-sidebar-over-content page-sidebar-closed-hide-logo page-container-bg-solid">
        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="{{ url('/') }}">
                        <img src="{{ URL::asset('assets/img/logo.png') }}" alt="logo" class="logo-default"/>
                    </a>
                    <div class="menu-toggler sidebar-toggler hide"></div>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"></a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN TOP NAVIGATION MENU -->
                <div class="top-menu">
                    {{--<ul class="nav navbar-nav pull-right">--}}
                        {{--<li class="dropdown dropdown-user">--}}
                            {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">--}}
                                {{--<span class="username username-hide-on-mobile">{{ Auth::user()->firstname . ' ' .  Auth::user()->lastname}}</span>--}}
                                {{--<i class="fa fa-angle-down"></i>--}}
                            {{--</a>--}}
                            {{--<ul class="dropdown-menu dropdown-menu-default">--}}
                                {{--<li>--}}
                                    {{--<a href="{{ URL::route('/') }}">--}}
                                        {{--<i class="fa fa-home"></i>{{ T::get('Home link') }}--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li class="divider"></li>--}}
                                {{--<li>--}}
                                    {{--<a href="{{ URL::route('logout') }}">--}}
                                        {{--<i class="icon-key"></i>{{ T::get('Logout link') }}--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                </div>
                <!-- END TOP NAVIGATION MENU -->
            </div>
            <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <div class="clearfix"></div>
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            @include('layouts.leftSidebar')
            <div class="page-content-wrapper">
                <div class="page-content">
                    @yield('content')
                </div>
            </div>
        </div>
        <script src="{{ URL::asset('assets/js/jquery.min.js') }}" type="text/javascript"></script>
        <script src="{{ URL::asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ URL::asset('assets/js/jquery.uniform.min.js') }}" type="text/javascript"></script>

        <script src="{{ URL::asset('assets/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
        <script src="{{ URL::asset('assets/js/dataTables.bootstrap.js') }}" type="text/javascript"></script>
        <script src="{{ URL::asset('assets/js/moment-with-locales.js') }}" type="text/javascript"></script>
        <script src="{{ URL::asset('assets/js/moment-timezone-with-data.js') }}" type="text/javascript"></script>
        <script src="{{ URL::asset('assets/js/underscore.js') }}" type="text/javascript"></script>
        <script src="{{ URL::asset('assets/js/bootstrap-dialog.js') }}" type="text/javascript"></script>
        <script src="{{ URL::asset('assets/js/bootstrap-colorpicker.js') }}" type="text/javascript"></script>
        <script src="{{ URL::asset('assets/js/docs.js') }}" type="text/javascript"></script>
        <script src="{{ URL::asset('assets/js/jquery.md5.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/js/Sortable.js') }}" type="text/javascript"></script>

        @stack('scripts')

        <script src="{{ URL::asset('assets/js/metronic.js') }}" type="text/javascript"></script>
        <script src="{{ URL::asset('assets/js/layout.js') }}" type="text/javascript"></script>
        <script src="{{ URL::asset('assets/js/quick-sidebar.js') }}" type="text/javascript"></script>
        <script src="{{ URL::asset('assets/js/demo.js') }}" type="text/javascript"></script>

        <script src="{{ URL::asset('assets/js/index.js') }}" type="text/javascript"></script>
        <script>
            window.baseUrl = '{{ URL::to('/') }}/';
            window.token = '{{ csrf_token() }}';

            $(document).ready(function() {
                Metronic.init(); // init metronic core componets
                Layout.init(); // init layout
                QuickSidebar.init();
                Demo.init();
            });
        </script>
    </body>
</html>