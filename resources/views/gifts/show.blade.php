@extends('layouts.app')

@section('head')
<title>{{ $userFullName }} | {{ config('app.name') }}</title>
<meta name="description" content="Send virtual gifts to {{ $userFullName }} to show your love and loyalty, sending gifts can never be this easy anymore">
<meta name="keywords" content="instagram, community, followers, fans, rewards, notes, engagement, album, photo, pictures, likes, favourite ">
<meta property="og:image" content="{!! $profilePic !!}" />
@endsection

@section('header-search')
@include('partials._profile_search')
@endsection

@section('content')
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('partials._sidebar')
            </div>

            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-body">
                                <div class="col-xs-2 col-sm-1 col-md-1 user-block">
                                    <img class="img-circle img-bordered-sm" src="{{ $gift->getSenderProfileImageUrl() }}" alt="{{ $gift->getSender() }}">
                                </div>
                                <div class="col-xs-10 col-sm-11 col-md-11">
                                    <div class="">
                                        <div class="username">
                                            <a href="{{ $gift->getSenderUrl() }}">{{ $gift->getSender() }}</a>
                                            <div class="description pull-right"><i class="fa fa-clock-o"></i> {{ $gift->getDateTime() }}</div>
                                        </div>

                                    </div>
                                    <!-- /.user-block -->
                                    <p style="">{!! $gift->message !!}</p>
                                    <div class="text-center">
                                        <img class="img-thumbnail" style="max-height: 100px; max-width: 100px;" src="{{ $gift->gift->getPicUrl()}}" alt="{{ $gift->gift->title }}" />
                                        <p>{{ $gift->gift->title }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('gifts.comments._index')
            </div>
        </div>
    </div><!-- /.container -->
</section>
@endsection