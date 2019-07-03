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

@endsection

@section('header-search')
@include('partials._profile_search')
@endsection

@section('content')
<section id="about" class="main padding-topdown">
    <div class="container">
        <h1 class="text-center"><strong>Who We Are</strong></h1>
        <div class="row">
            <div class="col-sm-5">
                <div>
                    <h2>We build <strong>community</strong> within Instagram</h2>
                    <p>
                        We believe that you can do much more with your Instagram account! {{ config('app.name') }} provides you an easy way to engage with your loyal fans in a different way. You can build your own Instagram community and bring it to a new level.
                    </p>
                </div>
            </div>
            <div class="col-sm-7">
                <div class="text-center margin">
                    <img src="img/instagram-community.jpg" alt="{{ config('app.name') }}" style="width: 100%">
                </div>
            </div>
        </div>
    </div>
</section>
<section id="services" class="main padding-topdown">
    <div class="container">
        <h1 class="text-center"><strong>Things you can do here...</strong></h1>
        <div class="card-deck">
            <div class="card text-center margin-topdown-xs">
                <div class="card-block">
                    <img class="margin-topdown-xs" src="img/services/note.png" />
                    <p>Send notes (private ones too!) to each other</p>
                </div>
            </div>
            <div class="card text-center margin-topdown-xs">
                <div class="card-block">
                    <img class="margin-topdown-xs" src="img/services/gift.png" />
                    <p>Gift virtual gifts to your loved ones</p>
                </div>
            </div>
            <div class="card text-center margin-topdown-xs">
                <div class="card-block">
                    <img class="margin-topdown-xs" src="img/services/photo.png" />
                    <p>View photos on Instagram in a different way</p>
                </div>
            </div>
            <div class="card text-center margin-topdown-xs">
                <div class="card-block">
                    <img class="margin-topdown-xs" src="img/services/money.png" />
                    <p>Earn rewards from the virtual gifts you received</p>
                </div>
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

@section('credit')
<small class="text-center">Icons made by <a class="no-style" href="https://www.flaticon.com/authors/smashicons" title="Smashicons">Smashicons</a> from <a class="no-style" href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a class="no-style" href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></small>
@endsection