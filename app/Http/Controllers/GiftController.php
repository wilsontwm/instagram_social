<?php

namespace App\Http\Controllers;

use App\Gift;
use App\GiftComment;
use App\Services\TransactionService;
use App\User;
use App\UserGift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($username)
    {
        $user = User::where('username', $username)->first();
        if(!$user){
            return view('errors.404');
        }

        $userFullName = $user->name;
        $profilePic = $user->user_pic;

        $gifts = Gift::where('status', Gift::STATUS_AVAILABLE)->orderBy('title', 'asc')->get();
        return view('gifts.index', compact('user', 'username', 'userFullName', 'profilePic', 'gifts'));
    }

    /**
     * Display a listing of the resource the user owns.
     *
     * @return \Illuminate\Http\Response
     */
    public function myIndex()
    {
        $user = Auth::user();

        $userFullName = $user->name;
        $username = $user->username;
        $profilePic = $user->user_pic;

        $gifts = Gift::where('status', Gift::STATUS_AVAILABLE)->orderBy('title', 'asc')->get();
        $sentGifts = $user->sentGifts()->get();
        $receivedGifts = $user->receivedGifts()->get();
        return view('gifts.summary', compact('user', 'username', 'userFullName', 'profilePic', 'gifts', 'sentGifts', 'receivedGifts'));
    }

    /**
     * Display received gifts of the login user.
     *
     * @return \Illuminate\Http\Response
     */
    public function received()
    {
        $user = Auth::user();

        $userFullName = $user->name;
        $username = $user->username;
        $profilePic = $user->user_pic;

        $gifts = $user->receivedGifts()->orderBy('created_at', 'desc')->paginate(25);
        return view('gifts.received', compact('user', 'username', 'userFullName', 'profilePic', 'gifts'));
    }

    /**
     * Display sent gifts of the login user.
     *
     * @return \Illuminate\Http\Response
     */
    public function sent()
    {
        $user = Auth::user();

        $userFullName = $user->name;
        $username = $user->username;
        $profilePic = $user->user_pic;

        $gifts = $user->sentGifts()->orderBy('created_at', 'desc')->paginate(25);
        return view('gifts.sent', compact('user', 'username', 'userFullName', 'profilePic', 'gifts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  UserGift $gift
     * @return \Illuminate\Http\Response
     */
    public function show(UserGift $gift)
    {
        $this->authorize('show', $gift);
        $user = $gift->user;
        $userFullName = $user->name;
        $username = $user->username;
        $profilePic = $user->user_pic;

        // Mark notification as read
        if(Auth::user())
        {
            foreach (Auth::user()->unreadNotifications as $notification) {
                if (( $notification->type == 'App\Notifications\GiftReceived' || $notification->type == 'App\Notifications\GiftCommented' ) && $notification->data['gift_id'] == $gift->id) {
                    $notification->markAsRead();
                }
            }
        }

        return view('gifts.show', compact('gift', 'user', 'userFullName', 'username', 'profilePic'));
    }

    /**
     * Send a gift to the intended user
     * @param Request $request
     * @param $username
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request, User $user)
    {
        $input = $request->all();
        $gift = Gift::find($input['gift_id']);
        if(!$gift){
            return view('errors.500');
        }

        if(Auth::user()->credit_balance >= $gift->price){
            $userGift = TransactionService::sendGift($gift, $user, $input['message']);
            $user->notify(new \App\Notifications\GiftReceived($userGift));
            flash()->success('Successfully sent '.$gift->title.' to '.$user->name);
        } else {
            flash()->error('Ops! It seems like you do not have enough credit balance. Top up first to send the gift.');
        }

        return redirect()->back();
    }

    /** Comment on Gift
     * @param Request $request
     * @param UserGift $gift
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function comment(Request $request, UserGift $gift)
    {
        $this->authorize('show', $gift);
        $input = $request->only(['comment', 'reply_id']);
        $input['user_gift_id'] = $gift->id;
        $input['user_id'] = Auth::id();
        $notifyUsers = array();
        $replyTo = $gift->comments()->where('id', $input['reply_id'])->first();


        if(!$replyTo) {
            // reply to the post
            $input['reply_id'] = null;
            if($gift->sender && $gift->sender->id != Auth::id()) {
                array_push($notifyUsers, $gift->sender);
            }
            if($gift->user->id != Auth::id()) {
                array_push($notifyUsers, $gift->user);
            }
        }
        else {
            // reply to the comment
            $input['reply_user_id'] = $replyTo->user->id;
            if($replyTo->user->id != Auth::id()) {
                array_push($notifyUsers, $replyTo->user);
            }
            do{
                $input['reply_id'] = $replyTo->id;
            }while($replyTo = $replyTo->replyTo);
        }

        $validator = Validator::make($input, [
            'comment'       => 'required',
            'user_id'       => 'required'
        ]);

        if ($validator->fails()) {
            $flashMsg = '<ul>';
            foreach($validator->messages()->getMessages() as $field_name => $messages) {
                foreach($messages as $message) {
                    $flashMsg = $flashMsg.'<li>'.$message.'</li>';
                }

            }
            $flashMsg = $flashMsg.'</ul>';
            flash()->error($flashMsg);
            return back()->withErrors($validator)->withInput();
        }

        $giftComment = GiftComment::create($input);

        // Notify the user
        foreach($notifyUsers as $notifyUser) {
            if($notifyUser->id !== Auth::id()) {
                $notifyUser->notify(new \App\Notifications\GiftCommented($giftComment));
            }
        }

        flash()->success('You have successfully left a comment.');
        return back();
    }

    /** Remove Gift comment
     * @param UserGift $gift
     * @param GiftComment $comment
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function removeComment(UserGift $gift, GiftComment $comment)
    {
        $this->authorize('show', $gift);

        if($comment->user->id !== Auth::id()){
            flash()->error("You are not allowed to delete the comment.");
        }
        $comment->fill([
            'comment' => 'Comment has been removed',
            'is_deleted' => true
        ])->save();

        flash()->success('You have removed the comment.');

        return back();
    }
}
