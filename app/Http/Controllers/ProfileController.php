<?php

namespace App\Http\Controllers;

use App\Services\InstagramService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Process the search for profile
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'insta_account' => 'required|string',
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

        return redirect()->route('profile', ['username' => $request['insta_account']]);
    }

    /**
     * Show the latest media of the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function view($username)
    {
        // Initialize the attributes
        $maxPost = 5;
        $posts = [];
        $userID = 0;
        $profilePic = '';
        $userFullName = '';

        // Get the recent posts
        $result = InstagramService::getLatestMedia($username);

        if(empty($result['media'])){
            $existedUser = User::where('username', $username)->first();
            if(!$existedUser)
            {
                flash()->warning( "'<i>".$username."</i>' profile is either set to private mode or it is not available" )->important();
                return view('errors.404');
            }

            $userID = $existedUser->instagram_id;
            $profilePic = $existedUser->user_pic;
            $userFullName = $existedUser->name;
        }
        else{
            $userID = $result['user']['id'];
            $userFullName = $result['user']['full_name'];
            $profilePic = $result['user']['profile_pic_url'];
            $maxResult = count($result['media']) > $maxPost ? $maxPost : count($result['media']);
            foreach ($result['media'] as $key => $row) {
                $like[$key]  = $row['likes']['count'];
            }

            // Sort the data with volume descending, edition ascending
            // Add $data as the last parameter, to sort by the common key
            array_multisort($like, SORT_DESC, $result['media']);

            // Retrieve the data and add tags to the array
            for($i = 0; $i < $maxResult; $i++) {
                $posts[$i] = $result['media'][$i];
                if(isset($posts[$i]['caption'])) {
                    $posts[$i]['tags'] = InstagramService::getTags($posts[$i]['caption'])[0];
                }
            }


        }
        // Get the user if already signed up
        $user = InstagramService::getUser($userID, $username, $profilePic, $userFullName);

        return view('profile.view', compact('user', 'posts', 'username', 'userFullName', 'profilePic'));
    }

    /**
     * Autocomplete script
     * @return \Illuminate\Http\JsonResponse
     */
    public function autocomplete()
    {
        $results = array();
        $users = User::all();

        foreach($users as $user) {
            $results[] = [
                'username'  => $user->username,
                'name'      => $user->name,
                'icon'      => $user->user_pic
            ];
        }

        return response()->json($results);
    }
}
