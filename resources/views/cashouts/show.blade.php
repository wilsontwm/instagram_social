@extends('layouts.app')

@section('head')
<title>{{ $userFullName }} | {{ config('app.name') }}</title>
<meta name="description" content="Earn money from the virtual gifts you received from your fellow fans. Getting rewarded is just a click away.">
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
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cash out</h3>
                        <div class="box-tools pull-right">
                            {!! Form::open(['route' => ['cashout.withdraw', $cashout], 'class' => '']) !!}
                            <button type="submit" class="btn btn-default text-red" data-toggle="tooltip" data-original-title="Withdraw" {{ $cashout->canWithdraw() ? '' : 'disabled' }}><i class="fa fa-minus-circle"></i> Withdraw</button>
                            {!! Form::close() !!}

                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <div class="col-md-6">
                            <h4>Detail:</h4>
                            <div class="well">
                                <div class="text-center">
                                    <h3>Your cash out amount estimated to be:</h3>

                                    <h1 class="text-green"><span id="cashout">{!! $cashout->amount !!}</span> USD</h1>
                                </div>

                                <hr>

                                <div class="sub-content">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <strong>Status</strong>
                                        </div>
                                        <div class="col-xs-9">
                                            <p class="text-muted"><span class="label {!! $cashout->getLabel() !!}">{!! $cashout->getStatus() !!}</span></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <strong>Date</strong>
                                        </div>
                                        <div class="col-xs-9">
                                            <p class="text-muted">{!! $cashout->getDateTime() !!}</p>
                                        </div>
                                    </div>
                                    @if($cashout->isProcessed())
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <strong>Remarks</strong>
                                        </div>
                                        <div class="col-xs-9">
                                            <p class="text-muted">{!! $cashout->remarks !!}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <strong>Processed</strong>
                                        </div>
                                        <div class="col-xs-9">
                                            <p class="text-muted">{!! $cashout->getProcessedDateTime() !!}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>Item breakdown:</h4>
                            <table class="table table-hover well">
                                <tr>
                                    <th></th>
                                    <th>Gift</th>
                                    <th>Quantity</th>
                                </tr>
                                @foreach ($cashout->cashoutRequestItems as $cashoutRequestItem)
                                <tr class="">
                                    <td>
                                        <img class="pull-right" src="{{ $cashoutRequestItem->getPicUrl() }}" style="max-width: 25px; max-height: 25px" />
                                    </td>
                                    <td>{!! $cashoutRequestItem->getTitle() !!}</a></td>
                                    <td>{!! $cashoutRequestItem->quantity !!}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div><!-- /.container -->
</section>

@endsection