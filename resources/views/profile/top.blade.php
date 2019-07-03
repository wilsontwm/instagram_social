@extends('layouts.app')

@section('head')
<title>{{ $userFullName }} | {{ config('app.name') }}</title>
<meta name="description" content="View your most liked Instagram posts of the month and share your achievement with the world.">
<meta name="keywords" content="instagram, community, followers, fans, rewards, notes, engagement, album, photo, pictures, likes, favourite ">
<meta property="og:image" content="{!! $profilePic !!}" />
<style>
    .top-post-container {
        width: 100%;
        margin: 0 auto;
    }
    #top-post-footer {
        font-size: 10px;
    }
    @media (min-width: 768px) {
        .top-post-container {
            width: 480px;
        }
        #top-post-footer {
            font-size: 14px;
        }
    }
    #top-post-canvas img{
        width: 100%;
    }
    .top-post-img {
        width: 100%;
        padding-bottom: 100%;
        background-size: 100% 100%;
        background-repeat: no-repeat;
        background-position: center;
    }
</style>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script type="text/javascript" src="js/canvas2image.js"></script>
<script>
var timeOut;
$(document).ready(function(){
    getTopPosts();
});

function getTopPosts() {
    var date = document.getElementById('datepicker').value;
    clearTimeout(timeOut);
    timeOut = setTimeout(function() {
        jQuery('#loader').show();
        jQuery('#top-post-canvas').empty();
        jQuery('#top-post-canvas').hide();
        jQuery('#no-result-container').hide();
        jQuery('#download-btn-container').empty();
        jQuery('#download-btn-container').hide();

        jQuery.getJSON('/top/posts?d='+ date, function (result) {
            var html;
            var footer;
            var maxPost = 4;
            var img;
            jQuery('#loader').hide();
            if(result['passed']) {
                if(result['image']) {
                    html = '<img src="' + result['image'] + '" /> ';
                    jQuery('#top-post-canvas').append(html);
                    jQuery('#top-post-canvas').fadeIn();
                    downloadBtn = '<a id="download-btn" class="btn btn-lg btn-success btn-block" href="' + result['image'] + '" download="mygreatfans.jpg"><i class="fa fa-download"></i> Download</a>';
                    jQuery('#download-btn-container').append(downloadBtn);
                    jQuery('#download-btn-container').fadeIn();
                } else {
                    // for zero posts from the date input
                    jQuery('.message').html('You do no have any posts in '+ date);
                    jQuery('#no-result-container').fadeIn();
                }
            } else {
                // for incorrect date input
                jQuery('.message').html('Something wrong has occurred. Please try again later.');
                jQuery('#no-result-container').fadeIn();
            }
        });
    }, 1000);
}
</script>
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
                <div class="top-post-container no-padding">
                    <div id="top-post-search" class="margin-bottom">
                        {!! Form::model(null, ['method' => 'GET', 'class' => 'form-inline']) !!}
                        <div class="input-group" style="width:100%;">
                            {!! Form::select('d', $dates, null, ['id' => 'datepicker', 'class' => 'form-control', 'onchange' => 'getTopPosts()']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div id="loader" style="">
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
                <div id="top-insta-container" class="top-post-container no-padding">
                    <div id="top-post-canvas" style="display:none"></div>
                    <div id="no-result-container" class="box" style="display:none">
                        <div class="box-body text-center">
                            <h1><i class="fa fa-frown-o text-maroon"></i></h1>
                            <h4 class="message"></h4>

                        </div>
                    </div>

                    <div id="download-btn-container" class="margin-topdown-xs" style="display:none"></div>
                </div>
            </div>
        </div>
    </div><!-- /.container -->
</section>
@endsection