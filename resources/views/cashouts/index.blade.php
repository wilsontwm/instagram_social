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
                            <a href="{{ route('cashout.create' ) }}" class="btn btn-default text-green"><i class="fa fa-money"></i> Cash out</a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>Date</th>
                                <th>Est. Amount (USD)</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            @foreach ($cashoutRequests as $cashoutRequest)
                            <tr class="">
                                <td><a href="{{ route('cashout.show', [$cashoutRequest->id]) }}">{!! $cashoutRequest->getDateTime() !!}</a></td>
                                <td>{!! $cashoutRequest->amount !!}</td>
                                <td>
                                    <span class="label {!! $cashoutRequest->getLabel() !!}">{!! $cashoutRequest->getStatus() !!}</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-default" data-toggle="tooltip" data-original-title="View" href="{{ route('cashout.show', [$cashoutRequest->id]) }}"><i class="fa fa-eye"></i></i></a>
                                        {!! Form::open(['route' => ['cashout.withdraw', $cashoutRequest], 'class' => '', 'style'=>'display:inline-block']) !!}
                                        <button type="submit" class="btn btn-default" data-toggle="tooltip" data-original-title="Withdraw" {{ $cashoutRequest->canWithdraw() ? '' : 'disabled' }}><i class="fa fa-minus-circle text-red"></i></button>
                                        {!! Form::close() !!}
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>

                    <div class="box-footer text-center">
                        <div class="pagination-wrapper"><?php echo $cashoutRequests->appends(Request::except('page'))->render(); ?></div>
                        <div class="pagination-count"><?php echo $cashoutRequests->total() . ' cash out(s)'; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container -->
</section>

@endsection