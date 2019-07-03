@extends('layouts.app')

@section('head')
<title>{{ $userFullName }} | {{ config('app.name') }}</title>
<meta name="description" content="Earn money from the virtual gifts you received from your fellow fans. Getting rewarded is just a click away.">
<meta name="keywords" content="instagram, community, followers, fans, rewards, notes, engagement, album, photo, pictures, likes, favourite ">
<meta property="og:image" content="{!! $profilePic !!}" />

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.0/css/bootstrap-slider.min.css" />
<style>
.slider.slider-horizontal {
    width: 80%;
    margin: 8px 10%;
}
.slider .slider-selection {
    background: #E08283;
}
.slider .slider-handle {
    background: #954120;
}
.slider .slider-track {
    background: #CCCCCC;
}
.gift-container {
    position: relative;
    overflow: hidden;
    border: 1px solid #DDDDDD;
}
.gifts-card {
    margin: 6px;
    border-radius: 14px;
    width: 150px;
    height: 150px;
    background-color: white;
    border: none;
    box-shadow: none;
}
</style>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.0/bootstrap-slider.min.js"></script>
<script>
var timeOut;
$(document).ready(function(){
    setGiftsSelected();
});

/* To add the gifts selected array */
function setGiftsSelected() {
    var gifts = new Array();

    $('.slider-input').each(function(){
        var giftItem = {};
        giftItem[$(this).attr('data-id')] = $(this).attr('value');
        gifts.push(giftItem);
    });
    document.getElementById('cashout_item_input').value = JSON.stringify(gifts);
    getGiftsSelectedOutput();
}

/* To dump the selected gifts to server to get the amount output */
function getGiftsSelectedOutput() {
    var gifts = document.getElementById('cashout_item_input').value;

    clearTimeout(timeOut);
    timeOut = setTimeout(function() {
        jQuery.getJSON('/cashout/amount?gifts='+ gifts, function (result) {
            $('#cashout').text(result);
            if(result >= {{ config('settings.min_cash_out_amount') }}) {
                $('#cashout').closest('#cashout-container').removeClass('text-red');
                $('#cashout').closest('#cashout-container').addClass('text-green');
                document.getElementById("submitBtn").disabled = false;
            } else {
                $('#cashout').closest('#cashout-container').removeClass('text-green');
                $('#cashout').closest('#cashout-container').addClass('text-red');
                document.getElementById("submitBtn").disabled = true;
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

            <div class="col-md-9"><!-- Main container -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Add new cash out:</h3>
                                </div>
                                <div class="box-body">
                                    <div class="callout callout-warning">
                                        <i class="fa fa-warning"></i> You are only allowed to make a cash out of minimum {{ config('settings.min_cash_out_amount' ) }} USD.
                                    </div>
                                    <div class="form-container">
                                        {!! Form::open(['route' => 'cashout.store', 'class' => 'form-horizontal']) !!}
                                        <div class="text-center well">
                                            <h3>Your cash out amount estimated to be:</h3>

                                            <h1 id="cashout-container" class=""><span id="cashout">0</span> USD</h1>
                                        </div>

                                        @if(count($gifts) == 0)
                                        <div class="text-center">
                                            <h1><i class="fa fa-frown-o text-maroon"></i></h1>
                                            <h4>You have not received any gifts yet</h4>
                                        </div>
                                        @else
                                            <h4 class="text-center">How many gifts do you want to cash out?</h4>
                                            <div class="gifts-wrapper margin-topdown-xs">
                                            @foreach($gifts as $gift)
                                                <div class="gift-container">
                                                    <div class="gifts-card">
                                                        <div class="gifts-icon"><img src="{{ $gift->getPicUrl() }}" /></div>
                                                        <h1 id="gift-count-{{ $gift->id }}" class="gifts-count">{{ $gift->pivot->count }}</h1>
                                                        <p>{{ $gift->title }}</p>

                                                    </div>
                                                    <input id="slider-{{ $gift->id }}" class="slider-input" type="text" data-id="{{ $gift->id }}" data-slider-min="0" data-slider-max="{{ $gift->pivot->count }}" data-slider-step="1" data-slider-value="{{ $gift->pivot->count }}" value="{{ $gift->pivot->count }}" />
                                                </div>

                                                <script>
                                                    $(document).ready(function(){
                                                        var $slider = $("#slider-{{ $gift->id }}");
                                                        $slider.slider();
                                                        $slider.on("change", function(slideEvt) {
                                                            $("#gift-count-{{ $gift->id }}").html(slideEvt.value.newValue);
                                                            setGiftsSelected();
                                                        });
                                                    })

                                                </script>
                                            @endforeach
                                            </div>

                                            {!! Form::hidden('cashout_items[]', '', ['id' => 'cashout_item_input']) !!}

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    {!! Form::submit('Submit', ['id' => 'submitBtn', 'class' => 'btn btn-theme pull-right']) !!}
                                                </div>
                                            </div>

                                        @endif

                                        {!! Form::Close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Main container -->
            </div>
        </div>
    </div><!-- /.container -->
</section>
@endsection