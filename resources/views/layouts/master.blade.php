<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Auction | @yield('title')</title>
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}" type="text/css" >
        <link rel="stylesheet" href="{{ asset('assets/css/flaticon.css') }}" type="text/css" >
        <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" type="text/css" >
        <link href="{{ asset('assets/css/bootstrap-dialog.css') }}" rel="stylesheet" type="text/css" />

        <link href="{{ URL::asset('assets/css/layout.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/components.css') }}" rel="stylesheet" type="text/css" />
        @stack('css')
    </head>
    <body>
        <header>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ url('/') }}">
                            <img class="logo pull-right" src="{{ asset('assets/img/logo.png') }}">
                        </a>
                    </div>
                    <div class="col-md-6 site-phone">
                        @if(Auth::check())
                            <div class="row">
                                <div class="col-md-8">
                                    <h4 class="text-center">8(800)123-45-67</h4>
                                    <p class="text-center">{{ Lang::get('main.site_phone_description') }}</p>
                                </div>
                                <div class="col-md-4">
                                    <div class="personal-area pull-right">
                                        <h4 class="text-center">{{ Auth::user()->name }}</h4>
                                        <span class="drop-down">{{ Lang::get('main.personal_area_header') }} <div class="arrow"></div></span>
                                        <div class="drop-down-container">
                                            <div class="divider"></div>
                                            <ul>
                                                <li>
                                                    <a>{{ trans('main.personal_area_drop_down_my_auction') }}</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('profile.index') }}">{{ trans('main.personal_area_drop_down_data') }}</a>
                                                </li>
                                                <li>
                                                    <a>{{ trans('main.personal_area_drop_down_history') }}</a>
                                                </li>
                                                <li>
                                                    <a>{{ trans('main.personal_area_drop_down_bonuses') }}</a>
                                                </li>
                                                <li>
                                                    <a>{{ trans('main.personal_area_drop_down_rates') }}</a>
                                                </li>
                                                @if(Auth::user()->admin)
                                                    <li>
                                                        <a href="{{ route('users.index') }}">{{ trans('main.personal_area_drop_down_admin_panel') }}</a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a href="{{ url('/logout') }}">{{ trans('main.personal_area_drop_down_logout') }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <h4 class="text-center">8(800)123-45-67</h4>
                            <p class="text-center">{{ Lang::get('main.site_phone_description') }}</p>
                        @endif
                    </div>
                    <div class="col-md-3 login-block">
                        <div class="row">
                            <div class="col-md-4 vertical-separate">
                                @if(Auth::check())
                                    <span>{{ Lang::get('main.you_are_betting') }} <span>10</span></span>
                                @else
                                    <button type="button" class="btn btn-login" id="login">{{ Lang::get('main.login') }}</button>
                                @endif
                            </div>
                            <div class="col-md-4 vertical-separate">
                                @if(Auth::check())
                                    <a href="/packages">{{ Lang::get('main.buy_bets') }}</a>
                                @else
                                    <span class="btn-registration" id="registration">{{ Lang::get('main.registration') }}</span>
                                @endif
                            </div>
                            <div class="col-md-3 vertical-separate"></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <nav class="main-nav">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <ul class="nav">
                            <li class="{{ url()->current() == route('auction.page') ? 'active' : '' }}">
                                <a href="{{ route('auction.page') }}">{{ trans('main.nav_link_auction') }}</a>
                            </li>
                            <li class="{{ url()->current() == route('gamezone.page') ? 'active' : '' }}">
                                <a href="{{ route('gamezone.page') }}">{{ trans('main.nav_link_game_zone') }}</a>
                            </li>
                            @if(Auth::check())
                                <li class="{{ url()->current() == route('myauction.page') ? 'active' : '' }}">
                                    <a href="{{ route('myauction.page') }}">{{ trans('main.nav_my_auction') }}</a>
                                </li>
                            @else
                                <li>
                                    <a href="#">{{ trans('main.nav_link_how_it_works') }}</a>
                                </li>
                            @endif
                            <li>
                                <a href="#">{{ Lang::get('main.nav_link_comments_and_video') }}</a>
                            </li>
                            <li>
                                <a href="#">{{ Lang::get('main.nav_link_feedback') }}</a>
                            </li>
                            <li>
                                <a href="#">{{ Lang::get('main.nav_link_bonuses') }}</a>
                            </li>
                            <li>
                                <a href="#">{{ Lang::get('main.nav_link_vk') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container-fluid">
            @yield('content')
        </div>
        <footer>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <img class="logo pull-right" src="{{ asset('assets/img/logo.png') }}">
                    </div>
                    <div class="col-md-7">
                        <div class="col-md-3 vertical-separate">
                            <ul class="list-group left-link-group">
                                <li>
                                    <a href="#">{{ Lang::get('main.home') }}</a>
                                </li>
                                <li>
                                    <a href="#">{{ Lang::get('main.auction') }}</a>
                                </li>
                                <li>
                                    <a href="#">{{ Lang::get('main.game_zone') }}</a>
                                </li>
                                <li>
                                    <a href="#">{{ Lang::get('main.buy_bets') }}</a>
                                </li>
                                <li>
                                    <a href="#">{{ Lang::get('main.personal_area') }}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 vertical-separate">
                            <ul class="list-group middle-link-group">
                                <li>
                                    <a href="#">{{ Lang::get('main.important_information') }}</a>
                                </li>
                                <li>
                                    <a href="#">{{ Lang::get('main.terms_of_use') }}</a>
                                </li>
                                <li>
                                    <a href="#">{{ Lang::get('main.processing_of_personal_data') }}</a>
                                </li>
                                <li>
                                    <a href="#">{{ Lang::get('main.terms_of_sale') }}</a>
                                </li>
                                <li>
                                    <a href="#">{{ Lang::get('main.cooperate_with_us') }}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-3 vertical-separate">
                            <ul class="list-group right-link-group">
                                <li>
                                    <a href="#">{{ Lang::get('main.good_to_know') }}</a>
                                </li>
                                <li>
                                    <a href="#">{{ Lang::get('main.how_it_works') }}</a>
                                </li>
                                <li>
                                    <a href="#">{{ Lang::get('main.questions_answers') }}</a>
                                </li>
                                <li>
                                    <a href="#">{{ Lang::get('main.reviews_and_video') }}</a>
                                </li>
                                <li>
                                    <a href="#">{{ Lang::get('main.tips_for_beginners') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-2 site_phone">
                        <h4>{{ Lang::get('main.site_phone') }}</h4>
                        <h4>8(800)123-45-67</h4>
                    </div>
                </div>
            </div>
        </footer>
        <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/bootstrap.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/bootstrap-dialog.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/underscore.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/jquery.countdown.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/moment-with-locales.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/moment-timezone-with-data.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/js/socket.io.js') }}"></script>
        @stack('scripts')
        <script>
            window.baseUrl = '{{ URL::to('/') }}/';
            window.token = '{{ csrf_token() }}';

            $(document).ready(function() {
                window.node = io('{{ URL::to('/') }}:3000');

                window.node.on('userData', function(){
                    window.node.emit('userData', {
                        @if(Auth::check())
                            token: '{{ Session::get('node') }}',
                            device: '{{ Session::get('device') }}',
                        @else
                            unauthorized: true
                        @endif
                    });
                });
            });
        </script>
        <script type="text/javascript" src="{{ asset('assets/js/index.js') }}"></script>
    </body>
</html>