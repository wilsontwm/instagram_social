<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $username = $user->username;
        $userFullName = $user->name;
        $profilePic = $user->user_pic;
        $notifications = $user->notifications()->paginate(10);

        return view('notifications.index', compact('user', 'username', 'userFullName', 'profilePic', 'notifications'));
    }

    public function read(Request $request)
    {
        $user = Auth::user();
        $notificationIds = json_decode($request['notifications']);

        foreach($notificationIds as $notificationId) {
            $notification = $user->unreadNotifications()->where('id', $notificationId)->first();
            if($notification) {
                $notification->markAsRead();
            }
        }

        flash()->success('You have successfully marked selected notification(s) as read');
        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $user = Auth::user();
        $notificationIds = json_decode($request['notifications']);

        foreach($notificationIds as $notificationId) {
            $notification = $user->notifications()->where('id', $notificationId)->first();
            if($notification) {
                $notification->delete();
            }
        }

        flash()->success('You have successfully deleted selected notification(s)');
        return redirect()->back();
    }
}
