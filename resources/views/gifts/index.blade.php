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
                <div class="box no-border">
                    <div class="box-header">
                        <h4>Send a gift to {{ $userFullName }}</h4>
                    </div>
                    <div class="box-body">
                        @foreach($gifts as $gift)
                        <a class="btn btn-app btn-gift" data-id="{{ $gift->id }}">
                            <span class="badge bg-gray-active"><i class="fa fa-diamond text-green"></i> {{ $gift->price }}</span>
                            <img class="fa icon" src="{{ $gift->getPicUrl() }}" alt="{{ $gift->title }}" data-price="{{ $gift->price }}" />
                            <div>{{ $gift->title }}</div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container -->
</section>

<!-- Modal -->
<div class="modal fade" id="gift-modal" tabindex="-1" role="dialog" aria-labelledby="giftModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="giftModalLabel">Send gift to {{ $userFullName }}</h5>
            </div>
            <div class="modal-body">
                <div class="gift-container text-center">
                    <img class="gift-img" src="" alt="Gift" />
                    <h4 class="gift-title">Gift</h4>
                    <div><i class="fa fa-diamond text-green"></i> <span class="gift-price"></span></div>
                </div>

                <div>

                    {!! Form::open(['route' => ['gifts.send', $user->id], 'class' => 'form-horizontal']) !!}
                    {!! Form::input('text', 'gift_id', 0, ['id' => 'gift-input', 'class' => 'hidden']) !!}
                    <div class="form-group">
                        <label class="col-xs-12">Message</label>
                        <div class="col-xs-12">
                            {!! Form::textarea('message', null, ['class' => 'form-control', 'rows' => 3]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="pull-right">
                                {!! Form::submit('Send', ['class' => 'btn btn-info']) !!}
                                <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        var giftId;
        var giftSrc;
        var giftName;
        var giftPrice;
        $('.btn-gift').click(function(){
            $img = $(this).find('.icon');
            giftSrc = $img.attr('src');
            giftName = $img.attr('alt');
            giftPrice = $img.data('price');
            giftId = $(this).data('id');
            $('#gift-modal').modal('show');
            $('#gift-input').val(giftId);
            $('.gift-img').attr('src', giftSrc);
            $('.gift-img').attr('alt', giftName);
            $('.gift-title').html(giftName);
            $('.gift-price').html(giftPrice);
        });
    })
</script>
@endsection