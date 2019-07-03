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
                        <div class="pull-right">
                            {!! Form::model(null, ['method' => 'GET', 'class' => 'form-inline']) !!}
                            {!! Form::text('user', $user, ['class' => 'form-control', 'placeholder' => 'Search..']) !!}
                            <label class="control-label" style="margin-left: 10px">Status</label>
                            {!! Form::select('status', $statusFilter, $status, ['class' => 'form-control']) !!}
                            <button type="submit" class="btn btn-default"><i class="fa fa-filter"></i></button>
                            {!! Form::close() !!}
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th></th>
                                <th>User</th>
                                <th>Date</th>
                                <th>Est. Amount (USD)</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            @foreach ($cashoutRequests as $cashoutRequest)
                            <tr class="">
                                <td><img src="{!! $cashoutRequest->user->getProfileImageUrl() !!}" class="user-image-icon pull-right" alt="{!! $cashoutRequest->user->name !!}"></td>
                                <td><a href="{{ route('admin.cashout.show', [$cashoutRequest->id]) }}">{!! $cashoutRequest->user->name !!}</a></td>
                                <td>{!! $cashoutRequest->getDateTime() !!}</a></td>
                                <td>{!! $cashoutRequest->amount !!}</td>
                                <td>
                                    <span class="label {!! $cashoutRequest->getLabel() !!}">{!! $cashoutRequest->getStatus() !!}</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-default" data-toggle="tooltip" data-original-title="View" href="{{ route('admin.cashout.show', [$cashoutRequest->id]) }}"><i class="fa fa-eye"></i></i></a>
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