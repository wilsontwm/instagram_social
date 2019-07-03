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
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Edit gift: {!! $gift->title !!}</h3>
                                </div>
                                <!-- Progress of product -->
                                <div class="box-body margin">
                                    @include('admin.gifts._progress_style')
                                    <ul id="gift-progress">
                                        <li class="active"><a href="{{ route('admin.gifts.edit', [$gift->id]) }}">1. Gift Details</a></li>
                                        <li><a href="{{ route('admin.gifts.picture', [$gift->id]) }}">2. Photos</a></li>
                                    </ul>
                                </div>
                                <div class="box-body">
                                    {!! Form::model($gift, ['url' => route('admin.gifts.update', ['id' => $gift->id]), 'method' => 'PATCH', 'class' => 'form-horizontal']) !!}
                                    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                        <label class="col-sm-2 control-label">Title</label>
                                        <div class="col-sm-10">
                                            {!! Form::text('title', null, ['class' => 'form-control']) !!}
                                            {!! $errors->first('title', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
                                        <label class="col-sm-2 control-label">Price</label>
                                        <div class="col-sm-10">
                                            {!! Form::number('price', null, ['class' => 'form-control', 'placeholder' => 'eg. 0']) !!}
                                            {!! $errors->first('price', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                                        <label class="col-sm-2 control-label">Status</label>
                                        <div class="col-sm-10">
                                            {!! Form::select('status', \App\Gift::STATUS_ARRAY, null, ['class' => 'form-control']) !!}
                                            {!! $errors->first('status', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            {!! Form::submit('Save', ['class' => 'btn btn-theme']) !!}
                                            <a href="{{ route('admin.gifts.index') }}" class="btn btn-default">Cancel</a>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div><!-- box-body -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container -->
</section>
@endsection