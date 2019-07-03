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
                <!-- Widget container -->
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <!-- small box -->
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{ $usersTotal }}</h3>
                                <p>Users</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <!-- small box -->
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3>{{ $notesTotal }}</h3>
                                <p>Notes</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-sticky-note-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Widget container -->
                <!-- Users list container -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Users</h3>
                                <div class="pull-right">
                                    {!! Form::model(null, ['method' => 'GET', 'class' => 'form-inline']) !!}
                                    {!! Form::text('search', $search, ['class' => 'form-control', 'placeholder' => 'Search..']) !!}
                                    {!! Form::close() !!}
                                </div>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-hover">
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Credit</th>
                                        @if(Auth::user()->isSuperAdmin())
                                        <th>Role</th>
                                        @endif
                                        <th>Registered On</th>
                                        @if(Auth::user()->isSuperAdmin())
                                        <th></th>
                                        @endif
                                    </tr>
                                    @foreach ($users as $user)
                                    <tr class="">
                                        <td><img src="{!! $user->getProfileImageUrl() !!}" class="user-image-icon pull-right" alt="{!! $user->name !!}"></td>
                                        <td>
                                            <a href="{{ route('profile', [$user->username]) }}">{!! $user->name !!}</a>
                                            @if($user->isNewUser())
                                            <span class="pull-right-container">
                                                <small class="label pull-right bg-green">new</small>
                                            </span>
                                            @endif
                                        </td>
                                        <td>{!! $user->username !!}</td>
                                        <td>{!! $user->email !!}</td>
                                        <td>{!! $user->credit_balance !!}</td>
                                        @if(Auth::user()->can('edit', $user))
                                        <td>{!! $user->getRole() !!}</td>
                                        @endif
                                        <td>{!! $user->getRegisteredDate() !!}</td>
                                        @if(Auth::user()->can('edit', $user))
                                        <td><a href="{{ route('admin.users.edit', [$user->id]) }}">Edit</a></td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </table>
                            </div>

                            <div class="box-footer text-center">
                                <div class="pagination-wrapper"><?php echo $users->appends(Request::except('page'))->render(); ?></div>
                                <div class="pagination-count"><?php echo $users->total() . ' User(s)'; ?></div>
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