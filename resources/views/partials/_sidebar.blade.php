<!-- Profile Image -->
<div class="box box-primary">
    <div class="box-body box-profile">
        <img class="profile-user-img img-responsive img-circle" src="{!! $profilePic !!}" alt="{!! $userFullName !!}">

        <h3 class="profile-username text-center" style="margin-bottom: 5px">{!! $userFullName !!}<br/><small>{!! '@'.$username !!}</small></h3>

        <div class="text-center"><small><a href="https://www.instagram.com/{!! $username !!}" target="_blank"><i class="fa fa-external-link"></i> View on Instagram</a></small></div>

        <a href="{{ route('profile', [$username]) }}" class="btn btn-default btn-block"><i class="fa fa-user"></i> <b>Profile</b></a>
        @if($user)
        <a href="{{ route('notes', [$username]) }}" class="btn btn-default btn-block"><i class="fa fa-sticky-note-o"></i> <b>Notes</b></a>
        <a href="{{ route('gifts', [$username]) }}" class="btn btn-default btn-block"><i class="fa fa-gift"></i> <b>Send Gift</b></a>
        @else
        <a class="btn btn-default btn-block" data-toggle="modal" data-target="#guest-user-modal"><i class="fa fa-sticky-note-o"></i> <b>Notes</b></a>
        <a class="btn btn-default btn-block" data-toggle="modal" data-target="#guest-user-modal"><i class="fa fa-gift"></i> <b>Send Gift</b></a>
        @endif
    </div>
    <!-- /.box-body -->
</div>

<!-- Modal -->
<div class="modal fade" id="guest-user-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <h1 class="text-theme" style="font-size: 96px"><i class="fa fa-user-times"></i></h1>
                <h3>Ops! <i>{!! '@'.$username !!}</i> is not signed up yet!</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>