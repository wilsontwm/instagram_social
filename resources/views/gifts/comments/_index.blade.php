<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Comments</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="comment-form-container margin-bottom">
                    {!! Form::open(['route' => ['gifts.comments.store', $gift->id], 'class' => 'form-horizontal']) !!}
                    <div class="input-group">
                        {!! Form::input('comment', 'comment', null, ['class' => 'form-control', 'placeholder' => Auth::user() ? 'Start discussion' : 'Login first to start discussion', Auth::user() ? '' : 'disabled']) !!}
                        <span class="input-group-btn">
                            {!! Form::submit('Send', ['class' => 'btn btn-warning btn-flat',  Auth::user() ? '' : 'disabled']) !!}
                        </span>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="comment-section">
                    @foreach($gift->comments()->whereNull('reply_id')->get() as $comment)
                    <div class="outer-post">
                        <div class="main-post post-comment clearfix" data-comment="{{ $comment->id }}">
                            <div class="col-xs-2 col-sm-1 col-md-1 user-block">
                                <img class="img-circle img-bordered-sm" src="{{ $comment->user->getProfileImageUrl() }}" alt="{{ $comment->user->name }}">
                            </div>
                            <div class="col-xs-10 col-sm-11 col-md-11">
                                <div class="">
                                    <div class="username">
                                        <a href="{{ $comment->getSenderUrl() }}">{{ $comment->user->name }}</a>
                                        <ul class="main-post-tools box-tools list-inline pull-right">
                                            <li><button class="btn btn-box-tool btn-reply"><i class="fa fa-reply"></i></button></li>
                                            @if( $comment->canDelete(Auth::user()) )
                                            <li>
                                            {!! Form::open(['route' => ['gifts.comments.delete', $gift->id, $comment->id], 'class' => 'form-horizontal']) !!}
                                            <button type="submit" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
                                            {!! Form::close() !!}
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                    <small class="description">{{ $comment->getDateTime() }}</small>
                                </div>
                                <!-- /.user-block -->
                                <p style="{{ $comment->is_deleted ? 'font-style: italic;' : ''  }}">{!! $comment->comment !!}</p>
                                <ul class="list-inline">
                                    @if(count($comment->replies) > 0)
                                    <li class=""><a href="#" class="view-reply link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Replies ({{ count($comment->replies) }})</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        @foreach($comment->replies as $reply)
                        <div class="reply-comment-container clearfix " style="display: none;">
                            <div class="sub-post post-comment col-xs-offset-2 col-xs-10 col-sm-offset-1 col-sm-11 col-md-offset-1 col-md-11" data-comment="{{ $reply->id }}">
                                <div class="col-xs-2 col-sm-1 col-md-1 user-block">
                                    <img class="img-circle img-bordered-sm" src="{{ $reply->user->getProfileImageUrl() }}" alt="{{ $reply->user->name }}">
                                </div>
                                <div class="col-xs-10 col-sm-11 col-md-11">
                                    <div class="">
                                        <div class="username">
                                            <a href="{{ $reply->getSenderUrl() }}">{{ $reply->user->name }}</a>
                                            <ul class="sub-post-tools box-tools list-inline pull-right">
                                                <li><button class="btn btn-box-tool btn-reply"><i class="fa fa-reply"></i></button></li>
                                                @if( $reply->canDelete(Auth::user()) )
                                                <li>
                                                    {!! Form::open(['route' => ['gifts.comments.delete', $gift->id, $reply->id], 'class' => 'form-horizontal']) !!}
                                                    <button type="submit" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
                                                    {!! Form::close() !!}
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                        <small class="description">{{ $reply->getDateTime() }}</small> . <small><i class="fa fa-reply"></i> {{ $reply->repliedUserName() }}</small>
                                    </div>
                                    <!-- /.user-block -->
                                    <p style="{{ $reply->is_deleted ? 'font-style: italic;' : ''  }}">{!! $reply->comment !!}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach

                </div>

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        // hide/show tool for comments
        $('.main-post-tools').css('visibility', 'hidden');
        $('.main-post').mouseover(function() {
            $(this).find('.main-post-tools').css('visibility', 'visible');
        });
        $('.main-post').mouseout(function() {
            $(this).find('.main-post-tools').css('visibility', 'hidden');
        });

        // hide/show tool for reply comments
        $('.sub-post-tools').css('visibility', 'hidden');
        $('.sub-post').mouseover(function() {
            $(this).find('.sub-post-tools').css('visibility', 'visible');
        });
        $('.sub-post').mouseout(function() {
            $(this).find('.sub-post-tools').css('visibility', 'hidden');
        });

        // hide/show replies
        $('.view-reply').click(function() {
            $(this).closest('.outer-post').find('.reply-comment-container').slideToggle();
        });

        // add reply box
        var replyBox;
        $('.btn-reply').click(function() {
            // remove all reply box
            $('.reply-comment-form-container').each(function(){
                $(this).remove();
            });
            var post = $(this).closest('.post-comment');
            var commentId = post.attr('data-comment');
            replyBox =  '<div class="reply-comment-form-container margin-bottom col-xs-offset-2 col-xs-10 col-sm-offset-1 col-sm-11 col-md-offset-1 col-md-11">'
                + '{!! Form::open(["route" => ["gifts.comments.store", $gift->id], "class" => "comment-reply-form"]) !!}'
                + '<input name="reply_id" type="hidden" value="' + commentId + '" />'
                + '<input name="comment" class="form-control input-sm" placeholder="Type a comment" type="text" />'
                + '{!! Form::close() !!}'
                + '</div>';
            $(replyBox).insertAfter(post).hide().slideDown();
        });

        // reply the comment on Enter pressed
        $(this).keyup = function(e) {
            if(e.keyCode === 13){
                $('.comment-reply-form').submit();
            }
            return true;
        }
    })

</script>