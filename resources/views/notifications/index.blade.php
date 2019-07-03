@extends('layouts.app')

@section('head')
<title>{{ $userFullName }} | {{ config('app.name') }}</title>
<meta name="description" content="Get notified when your followers write you notes, send you virtual gifts, comment on your notes etc">
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
                <div class="row">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Notifications</h3>
                        </div>
                        <div class="box-body no-padding">
                            <div class="mailbox-controls">
                                <!-- Check all button -->
                                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                                </button>
                                <div class="btn-group">
                                    {!! Form::open(['route' => ['notifications.delete'], 'class' => '', 'style' => 'display:inline']) !!}
                                    <input type="hidden" class="selected-notifications" name="notifications" value="" />
                                    <button type="submit" class="btn btn-default btn-sm text-red"><i class="fa fa-trash-o"></i></button>
                                    {!! Form::close() !!}
                                    {!! Form::open(['route' => ['notifications.read'], 'class' => '', 'style' => 'display:inline']) !!}
                                    <input type="hidden" class="selected-notifications" name="notifications" value="" />
                                    <button type="submit" class="btn btn-default btn-sm text-blue"><i class="fa fa-eye"></i></button>
                                    {!! Form::close() !!}
                                </div>

                            </div>
                            @if($notifications->count() == 0)
                            <h4 class="text-center">No notifications found</h4>
                            @endif
                            <ul class="notifications-list">
                                @foreach( $notifications as $notification )
                                <li class="{!! $notification->unread() ? 'unread-notifications' : '' !!}">

                                        <div class="">
                                            <div class="notification-checkbox"><input type="checkbox" class="notifications" name="notifications[]" value="{!! $notification->id !!}" onchange="getSelectedNotifications()" /></div>
                                            <a href="{{ $notification->data['action'] }}" style="color:inherit">
                                                @if(isset($notification->data['pic_url']))
                                                <div class="notification-img">
                                                    <img src="{{ $notification->data['pic_url'] }}" />
                                                </div>
                                                @endif
                                                <div class="notification-content">
                                                    {!! $notification->data['message'] !!} <br />
                                                    <small style="color:#666"><i class="fa fa-clock-o"></i> {!! timeDiff($notification->created_at) !!}</small>
                                                </div>
                                            </a>
                                        </div>

                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-center">
                            <div class="pagination-wrapper"><?php echo $notifications->appends(Request::except('page'))->render(); ?></div>
                            <div class="pagination-count"><?php echo $notifications->total() . ' notification(s)'; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container -->
</section>

<script>
    $(document).ready(function () {

        //Enable check and uncheck all functionality
        $(".checkbox-toggle").click(function () {
            var clicks = $(this).data('clicks');
            if (clicks) {
                //Uncheck all checkboxes
                $(".notifications-list input[type='checkbox']").prop('checked', false);
                $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
            } else {
                //Check all checkboxes
                $(".notifications-list input[type='checkbox']").prop('checked', true);
                $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
            }
            $(this).data("clicks", !clicks);
            getSelectedNotifications();
        });
    });

    function getSelectedNotifications() {
        var checkboxes = document.getElementsByClassName('notifications');
        var checkboxesChecked = [];
        for(var i = 0; i < checkboxes.length; i++){
            if (checkboxes[i].checked) {
                checkboxesChecked.push(checkboxes[i].value);
            }
        }
        $('.selected-notifications').val( JSON.stringify(checkboxesChecked) );
    }
</script>
@endsection