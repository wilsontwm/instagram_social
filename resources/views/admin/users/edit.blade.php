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
                        <span><img src="{!! $user->getProfileImageUrl() !!}" class="user-image-icon" alt="{!! $user->name !!}"></span>
                        <h3 class="box-title">{{ $user->name }}</h3>
                    </div>
                    <div class="box-body">
                        {!! Form::model($user, ['url' => route('admin.users.update', ['id' => $user->id]), 'method' => 'PATCH', 'class' => 'form-horizontal']) !!}
                        <div class="form-group {{ $errors->has('role') ? 'has-error' : '' }}">
                            <label class="col-sm-2 control-label">Role</label>
                            <div class="col-sm-10">
                                {!! Form::select('role', $role, null, ['class' => 'form-control']) !!}
                                {!! $errors->first('role', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                {!! Form::text('email', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"> </label>
                            <div class="col-sm-10">
                                {!! Form::checkbox('reset_password', true, false, ['onchange' => 'togglePasswordContainer()', 'id' => 'password-checkbox']) !!} Reset password?
                                {!! $errors->first('reset_password', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div id="password-edit-container" hidden>
                            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                <label class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10">
                                    {!! Form::password('password', ['class' => 'form-control']) !!}
                                    {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                <label class="col-sm-2 control-label">Confirm Password</label>
                                <div class="col-sm-10">
                                    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                                    {!! $errors->first('password_confirmation', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                {!! Form::submit('Update', ['class' => 'btn btn-default']) !!}
                            </div>
                        </div>
                        {!! Form::Close() !!}
                    </div>
                </div>

            </div>
        </div>
    </div><!-- /.container -->
</section>
<script>
    $(document).ready(function(){
        togglePasswordContainer();
    })

    function togglePasswordContainer(){
        var isChecked = jQuery('#password-checkbox').is(':checked');
        if(!isChecked){
            jQuery('#password-edit-container').slideUp();
        }
        else{
            jQuery('#password-edit-container').slideDown().show();
        }
    }
</script>
@endsection