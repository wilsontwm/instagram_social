@extends('layouts.app')

@section('head')
    <title>Login | {{ config('app.name') }}</title>
    <meta name="description" content="Sign in to {{ config('app.name') }}. You can now write notes, send virtual gifts, view photos, earn money and do so much more. And best of all, it's free!">
    <meta name="keywords" content="instagram, community, followers, fans, rewards, notes, engagement, album, photo, pictures, likes, favourite ">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="panel col-md-10 col-md-offset-1">
            <div class="panel-body">
                <div class="col-sm-6">
                    <h3 class="text-center">There's so much that you can do here...</h3>
                    <div class="row">
                        <div class="col-sm-3 col-xs-6 text-center margin-topdown-xs">
                            <img class="img-rounded" src="/img/services/note.png" alt="Write notes"><br/>
                            <small>Write notes</small>
                        </div>
                        <div class="col-sm-3 col-xs-6 text-center margin-topdown-xs">
                            <img class="img-rounded" src="/img/services/photo.png" alt="View photo gallery"><br/>
                            <small>View photo gallery</small>
                        </div>
                        <div class="col-sm-3 col-xs-6 text-center margin-topdown-xs">
                            <img class="img-rounded" src="/img/services/gift.png" alt="Gift presents"><br/>
                            <small>Give presents</small>
                        </div>
                        <div class="col-sm-3 col-xs-6 text-center margin-topdown-xs">
                            <img class="img-rounded" src="/img/services/money.png" alt="Earn cash"><br/>
                            <small>Earn cash</small>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="margin-topdown">
                        <h4 class="text-center margin-bottom">Sign in here! It's FREE!</h4>
                        <div class="col-sm-10 col-sm-offset-1">
                            <a href="{{ url('/login/instagram') }}" class="btn btn-block btn-social btn-instagram">
                                <i class="fa fa-instagram"></i> Sign in with Instagram
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('credit')
<small class="text-center">Icons made by <a class="no-style" href="https://www.flaticon.com/authors/smashicons" title="Smashicons">Smashicons</a> from <a class="no-style" href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a class="no-style" href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></small>
@endsection