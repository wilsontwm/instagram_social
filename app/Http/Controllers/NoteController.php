<?php

namespace App\Http\Controllers;

use App\Note;
use App\NoteComment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    /**
     * @var Note
     */
    private $note;

    /**
     * NoteController constructor.
     * @param Note $note
     */
    public function __construct(Note $note)
    {
        $this->note = $note;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($username, Request $request)
    {
        $user = User::where('username', $username)->first();
        if(!$user){
            return view('errors.404');
        }
        $limit = 15;

        $search = $request->input('search', '');
        $userFullName = $user->name;
        $profilePic = $user->user_pic;
        if ($search && $search !== '') {;
            $notes = Note::where(function($query) {
                $query->where('is_private', false)
                    ->orWhere('sender_id', Auth::id())
                    ->orWhere('recipient_id', Auth::id());
            })
            ->whereHas('sender', function($query) use ($search){
                return $query->where('name', 'LIKE', '%' . $search . '%')
                             ->orWhere('username', 'LIKE', '%' . $search . '%');
            })
            ->where('recipient_id', $user->id)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
        }
        else {
            $notes = Note::where(function($query) {
               $query->where('is_private', false)
                     ->orWhere('sender_id', Auth::id())
                     ->orWhere('recipient_id', Auth::id());
            })
            ->where('recipient_id', $user->id)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
        }

        return view('notes.index', compact('user', 'username', 'userFullName', 'profilePic', 'notes', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $input = $request->except('private');
        $input['sender_id'] = Auth::user() ? Auth::id() : null;
        $input['recipient_id'] = $user->id;
        $input['is_private'] = isset($request['private']) && Auth::user() ? true : false;

        $validator = Validator::make($input, [
            'content'          => 'required|string',
            'recipient_id'     => 'required'
        ]);

        if ($validator->fails()){
            flash()->error('You are not allowed to send empty note.');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $note = $this->note->create($input);

        // Notify the user
        if($user->id !== Auth::id()) {
            $user->notify(new \App\Notifications\NotePosted($note));
        }

        flash()->success('You have successfully posted a note to '.$user->name.'.');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  Note $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        // Only authenticate when it is not private
        if($note->is_private)
        {
            $this->authorize('view', $note);
        }

        $user = $note->recipient;
        $userFullName = $user->name;
        $username = $user->username;
        $profilePic = $user->user_pic;

        // Mark notification as read
        if(Auth::user())
        {
            foreach (Auth::user()->unreadNotifications as $notification) {
                if (( $notification->type == 'App\Notifications\NoteReplied' || $notification->type == 'App\Notifications\NotePosted' ) && $notification->data['note_id'] == $note->id) {
                    $notification->markAsRead();
                }
            }
        }

        return view('notes.show', compact('note', 'user', 'userFullName', 'username', 'profilePic'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Note $note
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);
        $username = $note->recipient->username;
        $note->delete();
        flash()->success("Note deleted");

        return redirect("notes/".$username);
    }

    /**
     * Toggle pin/unpin of the note
     * @param Note $note
     * @return \Illuminate\Http\RedirectResponse
     */
    public function togglePin(Note $note)
    {
        $this->authorize('pin', $note);
        $note->is_pinned = !$note->is_pinned;
        $msg = $note->is_pinned ? 'Note successfully pinned' : 'Note successfully unpinned';
        $note->save();

        flash()->success($msg);
        return redirect()->back();
    }

    /** Comment on Note
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function comment(Request $request, Note $note)
    {
        // Only authenticate when it is not private
        if($note->is_private)
        {
            $this->authorize('view', $note);
        }
        $input = $request->only(['comment', 'reply_id']);
        $input['note_id'] = $note->id;
        $input['user_id'] = Auth::id();
        $input['is_private'] = $note->is_private;
        $notifyUsers = array();
        $replyTo = $note->comments()->where('id', $input['reply_id'])->first();

        if(!$replyTo) {
            // reply to the post
            $input['reply_id'] = null;
            if($note->sender && $note->sender->id != Auth::id()) {
                array_push($notifyUsers, $note->sender);
            }
            if($note->recipient->id != Auth::id()) {
                array_push($notifyUsers, $note->recipient);
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

        $noteComment = NoteComment::create($input);

        // Notify the user
        foreach($notifyUsers as $notifyUser) {
            if($notifyUser->id !== Auth::id()) {
                $notifyUser->notify(new \App\Notifications\NoteReplied($noteComment));
            }
        }

        flash()->success('You have successfully left a comment.');
        return back();
    }

    /** Remove Note comment
     * @param Note $note
     * @param NoteComment $comment
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function removeComment(Note $note, NoteComment $comment)
    {
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
