@extends('layouts.app')

@section('head')
<title>{{ $userFullName }} | {{ config('app.name') }}</title>
<meta name="description" content="Send virtual gifts to {{ $userFullName }} to show your love and loyalty, sending gifts can never be this easy anymore">
<meta name="keywords" content="instagram, community, followers, fans, rewards, notes, engagement, album, photo, pictures, likes, favourite ">
<meta property="og:image" content="{!! $profilePic !!}" />
<script src="/js/jquery.jscroll.js"></script>
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
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">My received gifts</h3>
                    </div>
                    <div class="box-body no-padding">
                        @if($gifts->count() == 0)
                        <div class="text-center">
                            <h1><i class="fa fa-frown-o text-maroon"></i></h1>
                            <h4>You have not received any gifts yet</h4>
                        </div>
                        @endif
                        <ul class="notifications-list">
                            <div class="infinite-scroll">
                            @foreach($gifts as $gift)
                            <li class="">
                                <div class="">
                                    <a href="{{ route('gifts.show', $gift->id) }}" style="color:inherit">
                                        <div class="notification-img">
                                            <img src="{!! $gift->getSenderProfileImageUrl() !!}" />
                                        </div>
                                        <div class="notification-content">
                                            <strong>{!! $gift->getSender() !!}</strong> has sent you a {!! $gift->gift->title !!} <br />
                                            <small style="color:#666"><i class="fa fa-clock-o"></i> {!! $gift->getDateTime() !!}</small>
                                        </div>
                                    </a>
                                </div>

                            </li>
                            @endforeach
                                <div class="pagination-wrapper"><?php echo $gifts->appends(Request::except('page'))->render(); ?></div>
                            </div>

                        </ul>
                    </div>

                    <!-- /.box-body -->

                </div>
            </div>
        </div>
    </div><!-- /.container -->
</section>

<script type="text/javascript">
    $('ul.pagination').hide();
    $(function() {
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<img class="center-block" style="max-height:15px;" src="/img/loading.gif" alt="Loading..." />',
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function() {
                $('ul.pagination').remove();
            }
        });
    });
</script>
@endsection