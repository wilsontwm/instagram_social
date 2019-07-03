@extends('layouts.app')

@section('head')
<title>{{ $userFullName }} | {{ config('app.name') }}</title>
<meta name="description" content="View {{ $userFullName }} profile on {{ config('app.name') }}. Engage & connect with {{ $userFullName }}">
<meta name="keywords" content="instagram, community, followers, fans, rewards, notes, engagement, album, photo, pictures, likes, favourite ">
<meta property="og:image" content="{!! $profilePic !!}" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>
<script>
    $(document).ready(function(){
        if (Modernizr.touch) {
            // show the close overlay button
            $(".close-overlay").removeClass("hidden");
            // handle the adding of hover class when clicked
            $(".img").click(function(e){
                if (!$(this).hasClass("hover")) {
                    $(this).addClass("hover");
                }
            });
            // handle the closing of the overlay
            $(".close-overlay").click(function(e){
                e.preventDefault();
                e.stopPropagation();
                if ($(this).closest(".img").hasClass("hover")) {
                    $(this).closest(".img").removeClass("hover");
                }
            });
        } else {
            // handle the mouseenter functionality
            $(".img").mouseenter(function(){
                $(this).addClass("hover");
            })
                // handle the mouseleave functionality
                .mouseleave(function(){
                    $(this).removeClass("hover");
                });
        }
    });
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
                <!--
                <div class="callout callout-warning">
                    <h4>Notice</h4>
                    <p>The top posts below are from the most recent photos (up to 20 posts only)</p>
                </div>
                -->
                @unless(count($posts) > 0)
                    <div class="text-center">
                        <h3>No result found / Profile is set to private</h3>
                    </div>
                @endunless
                @foreach($posts as $post)
                <div class="box no-border">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4 effect-1 effects clearfix">
                                <div class="img">
                                    <img class="img-rounded img-responsive" src="{!! $post['display_src'] !!}">
                                    <div class="overlay">
                                        <a href="{!! $post['link'] !!}" class="expand" target="_blank">+</a>
                                        <a class="close-overlay hidden">x</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <p class="post-comment">{!! isset($post['caption'])? $post['caption'] : '' !!}</p>
                                <p class="post-tag">
                                <h5><strong>Hashtags</strong></h5>
                                <p>
                                    @if(isset($post['tags']))
                                    @foreach($post['tags'] as $tag)
                                    <small class="label {!! \App\Services\InstagramService::LABEL_COLOR_ARRAY[rand(0, count(\App\Services\InstagramService::LABEL_COLOR_ARRAY) - 1)] !!}">{!! $tag !!}</small>
                                    @endforeach
                                    @endif
                                </p>
                                <p class="post-info text-muted">
                                    <span class="text-danger"><i class="fa fa-heart-o"></i></span> {!! $post['likes']['count'] !!}
                                    <span class="text-primary" style="margin-left:5px;"><i class="fa fa-commenting-o"></i></span> {!! $post['comments']['count'] !!}
                                    <span class="text-info" style="margin-left:5px;"><i class="fa fa-clock-o"></i></span> {!! $post['time'] !!}
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                @endforeach

            </div>
        </div>
    </div><!-- /.container -->
</section>
@endsection