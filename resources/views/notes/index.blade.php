@extends('layouts.app')

@section('head')
<title>{{ $userFullName }} | {{ config('app.name') }}</title>
<meta name="description" content="Write notes to {{ $userFullName }}, {{ $userFullName }} is waiting to hear from you">
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
                <div class="box-body">
                    <div class="pull-right margin-bottom">
                        {!! Form::model(null, ['method' => 'GET', 'class' => 'form-inline']) !!}
                        <div class="input-group">
                            <input name="search" class="form-control" type="text" value="{{ $search }}" placeholder="Search by sender">
                            <span class="input-group-btn">
                              <button type="submit" class="btn btn-theme btn-flat"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    </div>
                <div class="row">
                <aside class="new-note">
                    <h2><a href="#" class="no-style" data-toggle="modal" data-target="#note-modal"><i class="fa fa-plus-square-o"></i> Add note</a></h2>
                </aside>
                @foreach($notes as $note)
                    <aside class="note-wrap note-{{ $note->color }}">
                        <span class="note-toolbar">
                            @if ($note->is_private)
                            <i title="Private (Only sender/recipient can view this)" class="fa fa-eye-slash"></i>
                            @endif
                            @if ($note->is_pinned)
                            <i title="Pinned" class="fa fa-bookmark-o"></i>
                            @endif
                        </span>
                        <p class="note-content">
                            <a href="{{ route('notes.show', [$note->id]) }}">{{ substr_with_ellipsis( $note->content, 30 ) }}</a>
                        </p>
                        <div class="note-sender">
                            <div class="note-sender-detail">
                                <img src="{{ $note->getSenderPicUrl() }}" class="user-image" alt="{{ $note->getSender() }}">
                                <a href="{{ $note->getSenderUrl() }}">{{ $note->getSender() }}</a>
                            </div>
                            <div class="note-timestamps"><i class="fa fa-clock-o"></i> {{ $note->getDateTime() }}</div>
                        </div>
                    </aside>
                @endforeach

                <div class="note-container-footer text-center">
                    <div class="pagination-wrapper"><?php echo $notes->appends(Request::except('page'))->render(); ?></div>
                </div>
                </div>
            </div>
        </div>
    </div><!-- /.container -->
</section>

<!-- Modal -->
<div class="modal fade" id="note-modal" tabindex="-1" role="dialog" aria-labelledby="noteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {!! Form::open(['route' => ['notes.store', $user->id], 'class' => 'form-horizontal']) !!}
                <div class="form-group">
                    <label class="col-xs-12">Notes</label>
                    <div class="col-xs-12">
                        {!! Form::textarea('content', null, ['class' => 'form-control', 'rows' => 3]) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <div class="pull-left">
                            <input id="yellow-color-picker" class="color-picker" type="radio" name="color" value="yellow" checked />
                            <label class="color-picker-label" for="yellow-color-picker"><span class="yellow"></span></label>

                            <input id="blue-color-picker" class="color-picker" type="radio" name="color" value="blue" />
                            <label class="color-picker-label" for="blue-color-picker"><span class="blue"></span></label>

                            <input id="green-color-picker" class="color-picker" type="radio" name="color" value="green" />
                            <label class="color-picker-label" for="green-color-picker"><span class="green"></span></label>

                            <input id="pink-color-picker" class="color-picker" type="radio" name="color" value="pink" />
                            <label class="color-picker-label" for="pink-color-picker"><span class="pink"></span></label>
                        </div>
                        @if (Auth::user())
                        <div class="pull-right">
                            <input name="private" value="true" type="checkbox">
                            <span>Private mode</span>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group ">
                    <div class="col-xs-12">
                        <div class="pull-right">
                            {!! Form::submit('Submit', ['class' => 'btn btn-info']) !!}
                            <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </div>
</div>
@endsection