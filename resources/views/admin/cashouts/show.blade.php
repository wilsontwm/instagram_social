@extends('layouts.app')

@section('head')
<title>{{ $contentTitle }} | {{ config('app.name') }}</title>
<meta name="description" content="">
<meta name="keywords" content="">
@endsection

@section('content')
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('admin.partials._sidebar')
            </div>

            <div class="col-md-9">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cash out</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <div class="col-md-6">
                            <h4>Detail:</h4>
                            <div class="well">
                                <div class="text-center">
                                    <h3>The cash out amount estimated to be:</h3>

                                    <h1 class="text-green"><span id="cashout">{!! $cashout->amount !!}</span> USD</h1>
                                </div>

                                <hr>

                                <div class="sub-content">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <strong>User</strong>
                                        </div>
                                        <div class="col-xs-9">
                                            <p class="text-muted"><a href="{{ route('profile', [$cashout->user->username]) }}">{!! $cashout->user->name !!}</a></p>
                                        </div>
                                    </div>

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
                                            <strong>Processed</strong>
                                        </div>
                                        <div class="col-xs-9">
                                            <p class="text-muted">{!! $cashout->getProcessedDateTime() !!}</p>
                                        </div>
                                    </div>
                                    @endif

                                    @php
                                        $disabled = $cashout->isProcessed() ? 'disabled' : '';
                                    @endphp

                                    {!! Form::model($cashout, ['url' => route('admin.cashout.process', [$cashout->id]), 'method' => 'PATCH', 'class' => 'form-horizontal']) !!}
                                        <div class="row margin-bottom">
                                            <div class="col-xs-3">
                                                <strong>Status</strong>
                                            </div>
                                            <div class="col-xs-9">
                                                {!! Form::select('status', $status, null, ['class' => 'form-control', $disabled]) !!}
                                            </div>
                                        </div>
                                        <div class="row margin-bottom {{ $errors->has('amount') ? 'has-error' : '' }}">
                                            <div class="col-xs-3">
                                                <strong>Amount</strong>
                                            </div>
                                            <div class="col-xs-9">
                                                {!! Form::text('amount', null, ['class' => 'form-control', $disabled]) !!}
                                                {!! $errors->first('amount', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="row margin-bottom">
                                            <div class="col-xs-3">
                                                <strong>Remarks</strong>
                                            </div>
                                            <div class="col-xs-9">
                                                {!! Form::textarea('remarks', null, ['class' => 'form-control', $disabled]) !!}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <a href="{{ route('admin.cashout.index') }}" class="btn btn-default pull-right">Cancel</a>
                                                {!! Form::submit('Save', ['class' => 'btn btn-theme margin-leftright-xs pull-right', $disabled]) !!}
                                            </div>

                                        </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>Item breakdown:</h4>
                            <table class="table table-hover well">
                                <tr>
                                    <th></th>
                                    <th>Gift</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                                @foreach ($cashout->cashoutRequestItems as $cashoutRequestItem)
                                <tr class="">
                                    <td>
                                        <img class="pull-right" src="{{ $cashoutRequestItem->getPicUrl() }}" style="max-width: 25px; max-height: 25px" />
                                    </td>
                                    <td>{!! $cashoutRequestItem->getTitle() !!}</a></td>
                                    <td>{!! $cashoutRequestItem->quantity !!}</td>
                                    <td>{!! $cashoutRequestItem->getCashoutPrice() !!}</td>
                                    <td>{!! $cashoutRequestItem->getTotalCashoutPrice() !!}</td>
                                    <td></td>
                                </tr>
                                @endforeach
                            </table>
                            @if(!$cashout->getSufficiencyResult()['isSufficient'])
                            <div class="margin-topdown-xs">
                                <div class="callout callout-warning">
                                    <div><i class="fa fa-warning"></i> User does not have enough gifts to cash out:</div>
                                    <div>
                                        <ul>
                                            @foreach($cashout->getSufficiencyResult()['insufficientGift'] as $insufficientItem)
                                                <li>{!! $insufficientItem !!}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div><!-- /.container -->
</section>

@endsection