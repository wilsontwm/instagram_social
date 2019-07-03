<?php

namespace App\Policies;

use App\User;
use App\Note;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the note.
     *
     * @param  \App\User  $user
     * @param  \App\Note  $note
     * @return mixed
     */
    public function view(User $user, Note $note)
    {
        return !$note->is_private || ( $note->sender !== null && $note->sender->id === $user->id ) || $note->recipient->id === $user->id;
    }

    /**
     * Determine whether the user can create notes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the note.
     *
     * @param  \App\User  $user
     * @param  \App\Note  $note
     * @return mixed
     */
    public function update(User $user, Note $note)
    {
        //
    }

    /**
     * Determine whether the user can delete the note.
     *
     * @param  \App\User  $user
     * @param  \App\Note  $note
     * @return mixed
     */
    public function delete(User $user, Note $note)
    {
        return ( $note->sender !== null && $note->sender->id === $user->id ) || $note->recipient->id === $user->id;
    }

    /**
     * Determine whether the user can pin/unpin notes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function pin(User $user, Note $note)
    {
        return $note->recipient->id === $user->id;
    }
}
