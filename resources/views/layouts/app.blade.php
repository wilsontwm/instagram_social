<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-53960165-4"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-53960165-4');
    </script>

    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link rel="shortcut icon" href="/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Snippet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/css/adminlte.min.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/css/easy-autocomplete.min.css">
    <link rel="stylesheet" href="/css/easy-autocomplete.themes.min.css">
    <link rel="stylesheet" type="text/css" href="/css/custom.css" />

    <!-- Javascript -->
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="/js/jquery-ui.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="/js/jquery.easy-autocomplete.min.js"></script>
    <script type="text/javascript" src="/js/custom.js"></script>

    @yield('head')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top bg-color">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <i class="fa fa-navicon"></i>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <span><img class="logo header-logo" src="/img/logo.png" alt="{{ config('app.name') }}" /></span>
                        <span class="text-color hidden-sm hidden-xs">{{ config('app.name') }}</span>
                    </a>

                </div>
                <div class="navbar-header-search">
                    @yield('header-search')
                </div>
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                   Login / Sign up<span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ url('/login') }}"><i class="fa fa-instagram"></i> Sign in with
                                            Instagram</a>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <!-- Notification dropdown menu -->
                            <li class="dropdown user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="fa fa-gift"></i>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li class="user-body no-padding">
                                        <ul class="dropdown-user-menu">
                                            <li><a href="{{ route('gifts.index') }}"><i class="fa fa-gamepad"></i> Summary</a></li>
                                            <li><a href="{{ route('gifts.received') }}"><i class="fa fa-gift"></i> Received Gifts</a></li>
                                            <li><a href="{{ route('gifts.sent') }}"><i class="fa fa-cart-arrow-down"></i> Sent Gifts</a></li>
                                            <li><a href="{{ route('credit') }}"><i class="fa fa-diamond"></i> Top Up Credit</a></li>
                                            <li><a href="{{ route('cashout.index') }}"><i class="fa fa-money"></i> Cash Out</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown notifications-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="fa fa-bell-o"></i>
                                    @if(Auth::user()->unreadNotifications->count() > 0)
                                    <span class="label label-warning">{{ cappedNumber(Auth::user()->unreadNotifications->count(), 99) }}</span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li class="user-body no-padding">
                                        <ul class="dropdown-user-menu">
                                            @foreach(Auth::user()->notifications->slice(0, 5) as $notification)
                                            <li class="{!! $notification->unread() ? 'unread-notifications' : '' !!}">
                                                <a href="{{ $notification->data['action'] }}" style="color:inherit">
                                                    <div class="row">
                                                        @if(isset($notification->data['pic_url']))
                                                        <div class="col-xs-2">
                                                            <img src="{{ $notification->data['pic_url'] }}" style="width:30px; height: 30px; border-radius:50%">
                                                        </div>
                                                        @endif
                                                        <div class="{{ isset($notification->data['pic_url']) ? 'col-xs-10' : 'col-xs-12' }}">
                                                            {!! $notification->data['message'] !!} <br />
                                                            <small style="color:#666"><i class="fa fa-clock-o"></i> {!! timeDiff($notification->created_at) !!}</small>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            @endforeach

                                        </ul>
                                    </li>
                                    <li class="text-center"><a href="{{ route('notifications.index') }}">View all</a>
                                    </li>
                                </ul>
                            </li>
                            <!-- User dropdown menu -->
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <img src="{{ Auth::User()->getProfileImageUrl() }}" class="user-image" alt="{{ Auth::User()->name }}">
                                    <span class="hidden-xs">{{ Auth::user()->name }}</span> <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <!-- User image -->
                                    <li class="user-header hidden-xs">
                                        <img src="{{ Auth::User()->getProfileImageUrl() }}" class="img-circle" alt="{{ Auth::User()->name }}">

                                        <p>
                                            {{ Auth::user()->name }} <br />
                                            <i class="fa fa-diamond text-green"></i>
                                            <span class="">{{ number_format(Auth::user()->credit_balance, 0) }}</span>
                                        </p>
                                    </li>
                                    <li class="text-center visible-xs">
                                        You have {{ number_format(Auth::user()->credit_balance, 0) }}
                                        <i class="fa fa-diamond text-green"></i>
                                    </li>
                                    <!-- Menu Body -->
                                    <li class="user-body no-padding">
                                        <ul class="dropdown-user-menu">
                                            @if(Auth::user()->isAdmin())
                                            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Admin Dashboard</a></li>
                                            @endif
                                            <li><a href="{{ route('top.view') }}"><i class="fa fa-photo"></i> Top Posts</a></li>
                                            <li><a href="{{ route('profile', [Auth::user()->username]) }}"><i class="fa fa-user-circle"></i> Profile</a></li>
                                            <li><a href="{{ route('notes', [Auth::user()->username]) }}"><i class="fa fa-sticky-note-o"></i> Notes</a></li>
                                            <li>
                                                <a href="{{ route('logout') }}"
                                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                    <i class="fa fa-sign-out"></i> Logout
                                                </a>

                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    {{ csrf_field() }}
                                                </form>
                                            </li>
                                        </ul>
                                    </li>

                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <section class="flash-message">
            <div class="container">
                @include('flash::message')
            </div>
        </section>

        @yield('content')
    </div>
    <footer class="padding-topdown-xs">
        <div class="container text-center">
            <div class="table">
                <ul class="footer-list">
                    <li class=""><a class="no-style" href="/about">About</a></li>
                    <li class=""><a class="no-style" href="/about#services">Services</a></li>
                    <li class=""><a class="no-style" href="/privacy">Privacy Policy</a></li>
                </ul>
            </div>
            <div>Copyright &copy; {{ date('Y') }} <a class="no-style" href="{{ url('/') }}" title="{{ config('app.name') }}" target="_blank">{{ config('app.name') }}</a>. All rights reserved.</div>
            @yield('credit')
        </div>
    </footer>

    <!-- Scripts -->


    <script>
        $('div.alert').not('.alert-important').delay(5000).slideUp(300);

    </script>
</body>
</html>
