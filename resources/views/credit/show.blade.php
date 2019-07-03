@extends('layouts.app')

@section('head')
<title>{{ $userFullName }} | {{ config('app.name') }}</title>
<meta name="description" content="Top up your credit so that you can start sending virtual gifts to your favourite ones">
<meta name="keywords" content="instagram, community, followers, fans, rewards, notes, engagement, album, photo, pictures, likes, favourite ">
<meta property="og:image" content="{!! $profilePic !!}" />
<style>
    .panel {
        background-color: #fff;
        border-radius: 10px;
        padding: 15px 25px;
        position: relative;
        width: 100%;
        z-index: 10;
    }

    .pricing-table {
        box-shadow: 0px 10px 13px -6px rgba(0, 0, 0, 0.08), 0px 20px 31px 3px rgba(0, 0, 0, 0.09), 0px 8px 20px 7px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
    }

    @media (min-width: 900px) {
        .pricing-table {
            flex-direction: row;
        }
    }

    .pricing-table * {
        text-align: center;
        text-transform: uppercase;
    }

    .pricing-plan {
        border-bottom: 1px solid #e1f1ff;
        padding: 25px;
    }

    .pricing-plan:last-child {
        border-bottom: none;
    }

    @media (min-width: 900px) {
        .pricing-plan {
            border-bottom: none;
            border-right: 1px solid #e1f1ff;
            flex-basis: 100%;
            padding: 25px 35px;
        }

        .pricing-plan:last-child {
            border-right: none;
        }
    }

    .pricing-img {
        margin-bottom: 25px;
        max-width: 100%;
    }

    .pricing-header {
        color: #999;
        font-weight: 600;
        letter-spacing: 1px;
    }
    .pricing-features {
        letter-spacing: 1px;
        list-style: none;
        padding-left: 0;
    }

    .pricing-features-item {
        border-top: 1px solid #e1f1ff;
        font-size: 12px;
        padding: 15px 0;
        margin: 0;
    }

    .pricing-features-item:last-child {
        border-bottom: 1px solid #e1f1ff;
    }
    .pricing-price {
        color: #666;
        display: block;
        font-size: 32px;
        font-weight: 300;
    }
</style>

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

                    <div class="panel pricing-table">

                        <div class="pricing-plan">
                            <img src="/img/credit/handful-diamond.png" style="max-width: 180px;" alt="" class="pricing-img">
                            <h2 class="pricing-header">Small</h2>
                            <ul class="pricing-features">
                                <li class="pricing-features-item">A handful of diamonds</li>
                                <li class="pricing-features-item">50 diamonds</li>
                            </ul>
                            <span class="pricing-price">$5</span>
                            {!! Form::open(['route' => 'credit.paypal', 'class' => 'form-horizontal']) !!}
                            {!! Form::hidden('code', 'iwHE#5&GAs') !!}
                            {!! Form::submit('Get now', ['class' => 'btn btn-default btn-lg']) !!}
                            {!! Form::close() !!}
                        </div>

                        <div class="pricing-plan">
                            <img src="/img/credit/bagful-diamond.png" style="max-width: 180px;" alt="" class="pricing-img">
                            <h2 class="pricing-header">Medium</h2>
                            <ul class="pricing-features">
                                <li class="pricing-features-item">A bag of diamonds</li>
                                <li class="pricing-features-item">120 diamonds</li>
                            </ul>
                            <span class="pricing-price">$10</span>
                            {!! Form::open(['route' => 'credit.paypal', 'class' => 'form-horizontal']) !!}
                            {!! Form::hidden('code', '#$WHTgH@o3') !!}
                            {!! Form::submit('Get now', ['class' => 'btn btn-theme btn-lg']) !!}
                            {!! Form::close() !!}
                        </div>

                        <div class="pricing-plan">
                            <img src="/img/credit/chestful-diamond.png" style="max-width: 180px;" alt="" class="pricing-img">
                            <h2 class="pricing-header">Large</h2>
                            <ul class="pricing-features">
                                <li class="pricing-features-item">A chest of diamonds</li>
                                <li class="pricing-features-item">350 diamonds</li>
                            </ul>
                            <span class="pricing-price">$25</span>
                            {!! Form::open(['route' => 'credit.paypal', 'class' => 'form-horizontal']) !!}
                            {!! Form::hidden('code', 'Hrs#6eT64$') !!}
                            {!! Form::submit('Get now', ['class' => 'btn btn-default btn-lg']) !!}
                            {!! Form::close() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container -->
</section>
@endsection

@section('credit')
<small class="text-center">Icons made by <a class="no-style" href="http://www.freepik.com" title="Freepik">Freepik</a> from <a class="no-style" href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a class="no-style" href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></small>
@endsection