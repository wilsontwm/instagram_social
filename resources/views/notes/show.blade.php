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
                <div class="text-center">
                    <aside class="note-wrap note-{{ $note->color }} note-wrap-individual">

                        <span class="note-toolbar">
                            @if ($note->is_private)
                            <i title="Private (Only sender/recipient can view this)" class="fa fa-eye-slash"></i>
                            @endif
                            @if ($note->is_pinned)
                            <i title="Pinned" class="fa fa-bookmark-o"></i>
                            @endif
                            <a class="no-style" href="" data-toggle="modal" data-target="#note-modal"><i class="fa fa-ellipsis-v"></i></a>
                        </span>
                        <p class="note-content">
                            {{ $note->content }}
                        </p>
                        <div class="note-sender">
                            <div class="note-sender-detail">
                                <img src="{{ $note->getSenderPicUrl() }}" class="user-image" alt="{{ $note->getSender() }}">
                                <a href="{{ $note->getSenderUrl() }}">{{ $note->getSender() }}</a>
                            </div>
                            <div class="note-timestamps"><i class="fa fa-clock-o"></i> {{ $note->getDateTime() }}</div>
                        </div>
                    </aside>
                </div>
                @include('notes.comments._index')
            </div>
        </div>
    </div><!-- /.container -->
</section>

<!-- Modal -->
<div class="modal fade" id="note-modal" tabindex="-1" role="dialog" aria-labelledby="noteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body no-padding">
                <ul class="note-popup-menu text-center">
                    @if($note->isOwned())
                    @if(!$note->is_pinned)
                    <li><a href="{{ route('notes.pin', [$note->id]) }}" class="text-green"><i class="fa fa-bookmark-o"></i> Pin</a></li>
                    @else
                    <li><a href="{{ route('notes.pin', [$note->id]) }}" class="text-yellow"><i class="fa fa-bookmark"></i> Unpin</a></li>
                    @endif
                    @endif
                    @if($note->canDelete())
                    <li><a href="{{ route('notes.destroy', [$note->id]) }}" class="text-red"><i class="fa fa-trash-o"></i> Delete</a></li>
                    @endif
                    <li><a href="" data-dismiss="modal" ><i class="fa fa-close"></i> Cancel</a></li>
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection