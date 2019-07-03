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

            <div class="col-md-9"><!-- Users list container -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Gifts</h3>
                                <div class="box-tools pull-right">
                                    {!! Form::model(null, ['method' => 'GET', 'class' => '', 'style' => 'display:inline']) !!}
                                    {!! Form::checkbox('status', 'all', null, ['onchange' => '$(this).closest("form").submit()']) !!} Show all
                                    {!! Form::close() !!}
                                    <a href="{{ route('admin.gifts.create' ) }}" class="btn btn-default"><i class="fa fa-plus-circle"></i> Add new gift</a>
                                </div>
                            </div><!-- /.box-header -->
                            <div class="box-body card-columns">
                                @foreach($gifts as $gift)
                                <div class="card text-center">
                                    <img class="card-img-top" style="max-width: 100%" src="{{ $gift->getPicUrl() }}" alt="{{ $gift->title }}">
                                    <div class="card-block">
                                        <h4 class="card-title">
                                            @if( $gift->isDisabled() )
                                            <i class="fa fa-minus-circle text-red"></i></span>
                                            @elseif( $gift->isArchived() )
                                            <i class="fa fa-trash-o text-blue"></i></span>
                                            @endif
                                            {{ $gift->title }}
                                        </h4>
                                        <div class="gift-price"><i class="fa fa-diamond text-green"></i> {{ $gift->price }}</div>
                                        <a href="{{ route('admin.gifts.edit', [$gift->id]) }}" class="btn btn-theme"><i class="fa fa-edit"></i> Edit</a>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="box-footer text-center">
                                <div class="pagination-wrapper"><?php echo $gifts->appends(Request::except('page'))->render(); ?></div>
                                <div class="pagination-count"><?php echo $gifts->total() . ' Gift(s)'; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Users list container -->
            </div>
        </div>
    </div><!-- /.container -->
</section>
@endsection