<?php

namespace App\Http\Controllers;

use App\Services\InstagramService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class InstagramController extends Controller
{
    /**
     * Show the welcome page
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view('welcome');
    }

    public function redirect()
    {
        return Socialite::with('instagram')->redirect();
    }

    /**
     * Get the top instagrammers' recent photo
     *
     * @return \Illuminate\Http\Response
     */
    public function topRecent()
    {
        $count = 0;
        $insta = [];
        $instaAccounts = [
            'desiperkins',
            'cookinwithmima',
            'jenna_chew',
            'peonylim',
            'justinbieber',
            'gem0816',
            'adamsenatori',
            'buzzfeedfood',
            'amandabisk',
            'sora_pppp',
            'marutaro',
            'nyanchan22',
            'abercrombie',
            'josephineskriver',
            'devinbrugman',
            'xoxotsumi',
            'hapatime',
            'mayho10',
            'zacefron',
            'landscapescapture'
        ];

        while($count < 3 && !empty($instaAccounts)){
            $randomInstaNr = array_rand($instaAccounts, 1);
            $randomInsta = $instaAccounts[$randomInstaNr];

            //remove the account from the array
            unset($instaAccounts[$randomInstaNr]);

            $result = InstagramService::getLatestMedia($randomInsta);
            if(!empty($result['media'])){
                if(!$result['media'][0]['is_video']){
                    $insta[$count]['media'] = $result['media'][0];
                    $insta[$count]['user'] = $result['user'];
                    $count++;
                }
            }
        }

        return $insta;
    }

    /**
     * View the top posts of the login user by month
     */
    public function topPosts()
    {
        $user = Auth::user();
        $userFullName = $user->name;
        $username = $user->username;
        $profilePic = $user->user_pic;
        $today = Carbon::today();
        $dates = [
            $today->copy()->format('M Y') => $today->copy()->format('M Y'),
            $today->copy()->subMonth(1)->format('M Y') => $today->copy()->subMonth(1)->format('M Y'),
            $today->copy()->subMonth(2)->format('M Y') => $today->copy()->subMonth(2)->format('M Y')
        ];

        return view('profile.top', compact('user', 'username', 'userFullName', 'profilePic', 'dates'));
    }

    public function getTopPosts(Request $request)
    {
        $result = [];
        $result['passed'] = false;
        $dateInput = $request->input('d', '');
        $date = Carbon::parse($dateInput);
        $differenceInMonth = $date->diffInMonths(Carbon::today());
        if( $differenceInMonth >= 0 && $differenceInMonth <= 2) {
            $result = InstagramService::getTopFour($date->toDateString());
            $result['passed'] = true;
        }

        return $result;
    }

}
