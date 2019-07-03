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
                <!-- Widget container -->
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <!-- small box -->
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{ count($receivedGifts) }}</h3>
                                <p>Received gifts</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-gift"></i>
                            </div>
                            <a href="{{ route('gifts.received') }}" class="small-box-footer">
                                More info <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <!-- small box -->
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3>{{ count($sentGifts) }}</h3>
                                <p>Sent gifts</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-cart-arrow-down"></i>
                            </div>
                            <a href="{{ route('gifts.sent') }}" class="small-box-footer">
                                More info <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="callout callout-success">
                    Do you want to cash out your gifts?
                    <a class="btn btn-default btn-sm text-black no-style" href="{{ route('cashout.create') }}"> Click here!</a>
                </div>

                <!-- End of Widget container -->
                <div class="gifts-wrapper margin-topdown-xs">
                    @foreach($user->gifts as $gift)
                    <div class="gifts-card">
                        <div class="gifts-icon"><img src="{{ $gift->getPicUrl() }}" /></div>
                        <h1 class="gifts-count">{{ $gift->pivot->count }}</h1>
                        <p>{{ $gift->title }}</p>
                    </div>
                    @endforeach
                    @if(count($user->gifts) == 0)
                    <div class="box">
                        <div class="box-body text-center">
                            <h1><i class="fa fa-frown-o text-maroon"></i></h1>
                            <h4>You have not received any gifts yet</h4>

                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div><!-- /.container -->
</section>
<script>
$(document).ready(function(){
    $('.gifts-count').each(function () {
        $(this).prop('Counter',0).animate({
            Counter: $(this).text()
        }, {
            duration: 2000,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });
})
</script>
@endsection