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
                                    <div class="pull-right">
                                        {!! Form::model($gift, ['id' => 'upload-gift-image-form', 'url' => route('admin.gifts.picture.store', [$gift->id]), 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
                                        {!! Form::file('image', ['id' => 'gift-image-input', 'class' => 'hidden', 'accept' => "image/png,image/x-png"]) !!}
                                        <a href="javascript:void(0)" onclick="uploadImage()" class="btn btn-default"><i class="fa fa-file-photo-o"></i> Upload Photo</a>
                                        <a href="{{ route('admin.gifts.index') }}" class="btn btn-default"><i class="fa fa-home"></i> Home</a>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                                <!-- Progress of product -->
                                <div class="box-body margin">
                                    @include('admin.gifts._progress_style')
                                    <ul id="gift-progress">
                                        <li><a href="{{ route('admin.gifts.edit', [$gift->id]) }}">1. Gift Details</a></li>
                                        <li class="active"><a href="{{ route('admin.gifts.picture', [$gift->id]) }}">2. Photos</a></li>
                                    </ul>
                                </div>
                                <div class="box-body margin">
                                    @if($gift->hasPicture())
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <img src="{!! $gift->getPicUrl() !!}" class="img-thumbnail" />
                                        </div>
                                    </div>
                                    <div class="row margin-topdown-xs">
                                        <div class="col-xs-12">
                                        {!! Form::model($gift, ['url' => route('admin.gifts.picture.destroy', [$gift->id]), 'method' => 'DELETE', 'class' => 'form-horizontal']) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                        {!! Form::close() !!}
                                        </div>
                                    </div>
                                    @endif
                                </div><!-- box-body -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container -->
</section>
<script>
    function uploadImage() {
        $('#gift-image-input').click();
    }

    $(document).ready(function(){
        $("#gift-image-input").on('change',function(){
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.readAsDataURL(this.files[0]);

                $('#upload-gift-image-form').submit();
            }
        });
    });
</script>
@endsection