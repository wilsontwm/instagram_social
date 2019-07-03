<?php

namespace App\Http\Controllers\Admin;

use App\Note;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->contentTitle = 'Users';

    }

    /**
     * Display a list of users
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $contentTitle = $this->contentTitle;
        $search = $request->input('search', '');
        $limit = 25;
        $usersTotal = User::count();
        $notesTotal = Note::count();
        if($search !== '') {
            $users = User::where('name', 'like', '%' . $search . '%')
                           ->orWhere('username', 'like', '%' . $search . '%')
                           ->orderBy('created_at', 'desc')
                           ->paginate($limit);
        }
        else{
            $users = User::orderBy('created_at', 'desc')->paginate($limit);
        }

        return view('admin.users.index', compact('contentTitle', 'users', 'usersTotal', 'notesTotal', 'search'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        $this->authorize('edit', $user);
        $contentTitle = $this->contentTitle;
        $role = User::ROLE_ARRAY;

        return view('admin.users.edit', compact('contentTitle', 'user', 'role'));
    }

    /**
     * Update the specified resource
     * @param Request $request
     * @param User $user
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('edit', $user);
        $resetPassword = $request['reset_password'] == 1 ? true : false;
        $messages = ['password.regex' => "Your password must contain at least a lower case character, an upper case character and a number."];
        $rules = ['email' => 'nullable|email|unique:users,email,'.$user->id];

        if($resetPassword) {
            $rules['password'] = 'required|string|min:6|regex:/^(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/'; //Minimum password length - 6
            $rules['password_confirmation'] = 'required|string|min:6|same:password';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->role = $request->input('role');
        $user->email = $request->input('email');
        if($resetPassword) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();

        flash()->success('User details successfully updated');
        return redirect()->back();
    }
}
