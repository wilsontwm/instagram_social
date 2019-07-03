@extends('layouts.app')

@section('head')
<title>{{ config('app.name') }}</title>
<meta name="description" content="We build community within your Instagram. Your followers can now engage with you in a different way! Write you notes, give you virtual gifts and so much more!">
<meta name="keywords" content="instagram, community, followers, fans, rewards, notes, engagement, album, photo, pictures, likes, favourite ">
<meta property="og:image" content="{{ config('app.url') }}/img/instagram-community.jpg" />

<style>
    footer{
        background-color: #BFBFBF;
    }
</style>
<script>
    $(document).ready(function(){
        $('.carousel').carousel();

        // get the latest posts from top instagrammers
        jQuery.getJSON('/top/recent', function(result) {
            var html;
            var caption;
            for(var i = 0; i < result.length; i++){
                caption = '';
                if(result[i]['media']['caption'] !== null){ caption = result[i]['media']['caption'] }
                html = '<div class="card margin-topdown-md">'
                       + '<img class="card-img-top" src="'+ result[i]['media']['display_src'] + '" alt="'+ result[i]['user']['full_name'] + '">'
                       + '<div class="card-block">'
                       + '<h4 class="card-title"><a class="no-style" href="https://www.instagram.com/'+ result[i]['user']['username'] + '" target="_blank">'+ result[i]['user']['full_name'] + '</a></h4>'
                       + '<p class="card-text">'+ caption + '</p>'
                       + '<p class="card-text text-muted">'
                       + '<span class="text-danger"><i class="fa fa-heart-o"></i></span> ' + result[i]['media']['likes']['count']
                       + '<span class="text-primary" style="margin-left:5px;"><i class="fa fa-commenting-o"></i></span> ' + result[i]['media']['comments']['count']
                       + '<span class="text-info" style="margin-left:5px;"><i class="fa fa-clock-o"></i></span> ' + result[i]['media']['time']
                       + '</p>'
                       + '</div>'
                       + '<div class="card-footer text-center">'
                       + '<a href="profile/' + result[i]['user']['username'] + '" class="btn btn-success margin-leftright-xxs"><i class="fa fa-eye"></i> View</a>'
                       //+ '<a href="#" class="btn btn-warning margin-leftright-xxs"><i class="fa fa-exchange"></i> Compare</a>'
                       + '</div>'
                       + '</div>';
                jQuery('#top-insta-container').append(html);
            }

            jQuery('#loader').hide();
            jQuery('#top-insta-container').fadeIn();
        });
    });
</script>

@endsection

@section('content')
<section class="main padding-topdown">
    <div class="container">

        <div class="row">
            <div class="col-md-6 col-md-offset-3 text-center">
                <h1>Get the most favourite posts on your Instagram!</h1>

                @include('partials._profile_search')
            </div>
        </div>

    </div>

</section>
<section class="padding-topdown">
    <div class="container">
        <h2 class="text-center">Get your TOP 4 pics of the month here!</h2>
        <div id="myCarousel" class="carousel slide col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 margin-bottom">
            <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1" class=""></li>
            </ol>
            <div class="carousel-inner">
                <div class="item active">
                    <img src="img/carousel-1.jpg" alt="" style="width:100%;">
                </div>
                <div class="item">
                    <img src="img/carousel-2.jpg" alt="" style="width:100%;">
                </div>
            </div>
        </div>
        <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
            <a class="btn btn-block btn-success" href="{{ route('top.view') }}"><i class="fa fa-photo"></i> Get yours here!</a>
        </div>
    </div>
</section>
<section class="insta padding-topdown">
    <div class="container">
        <h2 class="text-center">Check out the Top Instagrammers!</h2>

        <div class="row">
            <div id="loader">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="lading"></div>
            </div>
            <div id="top-insta-container" class="card-deck" style="display: none">

            </div>
        </div>
    </div>
</section>
<section class="contact bg-gray-custom padding-topdown">
    <div class="container text-center">
        <h2>We
            <i class="fa fa-heart"></i>
            new friends!</h2>
        <ul class="list-inline list-social">
            <li class="list-inline-item social-instagram">
                <a href="https://www.instagram.com/my.great.fans/" target="_blank">
                    <i class="fa fa-instagram"></i>
                </a>
            </li>
            <li class="list-inline-item social-facebook">
                <a href="https://www.facebook.com/mygreatfansofficial/" target="_blank">
                    <i class="fa fa-facebook"></i>
                </a>
            </li>
            <li class="list-inline-item social-email">
                <a href="mailto:hello@mygreatfans.com">
                    <i class="fa fa-envelope-o"></i>
                </a>
            </li>
        </ul>
    </div>

</section>
@endsection