<?php

namespace App\Services;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class InstagramService
{
    const LABEL_COLOR_ARRAY = [
        'bg-red',
        'bg-yellow',
        'bg-aqua',
        'bg-blue',
        'bg-light-blue',
        'bg-green',
        'bg-navy',
        'bg-teal',
        'bg-olive',
        'bg-lime',
        'bg-orange',
        'bg-fuchsia',
        'bg-purple',
        'bg-maroon',
    ];

    /*
     * Get the latest posts from an user
     * @param $user
     * @return array The media information
     */
    public static function getLatestMedia($user)
    {
        $items = [];
        $instagramPostPrefix = 'https://www.instagram.com/p/';
        if($user !== ''){
            $client = new \GuzzleHttp\Client;
            $url = sprintf('https://www.instagram.com/%s/?__a=1', $user);
            try{
                $response = $client->get($url);
                $user = json_decode( (string) $response->getBody(), true)['user'];
                $items['user'] = $user;
                $items['media'] = $user['media']['nodes'];
                $key = 0;
                while($key < count($items['media'])){
                    if(isset($items['media'][$key]['date'])){
                        $time = Carbon::createFromTimestampUTC($items['media'][$key]['date']);
                        $items['media'][$key]['time'] = $time->diffForHumans();
                    }
                    $items['media'][$key]['link'] = $instagramPostPrefix . $items['media'][$key]['code'];
                    $key++;
                }
            }
            catch (\Exception $e){

            }

        }

        return $items;
    }

    /**
     * get top four posts of the month
     * @param string $date
     * @return array
     */
    public static function getTopFour($date = '')
    {
        $data = [];
        $isGeneratePicture = false;
        $data['count'] = 0;
        $data['likes'] = 0;
        $maxPost = 4;
        $result = self::getSelfMediaByMonth($date);
        if(isset($result['media'])) {
            $maxResult = count($result['media']) > $maxPost ? $maxPost : count($result['media']);
            foreach ($result['media'] as $key => $row) {
                $like[$key]  = $row['likes']['count'];
                $data['count'] = $data['count'] + 1;
                $data['likes'] = $data['likes'] + $row['likes']['count'];
            }

            // Sort the data with volume descending, edition ascending
            // Add $data as the last parameter, to sort by the common key
            array_multisort($like, SORT_DESC, $result['media']);

            // Retrieve the data and add tags to the array
            for($i = 0; $i < $maxPost; $i++) {
                $data['media'][$i] = $result['media'][$i];
            }
            $isGeneratePicture = true;
        }

        $data['count'] = number_format($data['count']);
        $data['likes'] = number_format($data['likes']);

        // generate the picture
        if($isGeneratePicture) {
            $imageURL = self::generatePicture($maxPost, $data['media'], $data['likes'], $data['count'], $date);
            $data['image'] = url(config('settings.post_image_url_path') . $imageURL);
        }

        return $data;
    }

    /**
     * Get the media posted by signed in user
     * @param string $date
     * @return array
     */
    public static function getSelfMediaByMonth($date = '')
    {
        $selectedStart = Carbon::parse($date)->startOfMonth();
        $selectedEnd = Carbon::parse($date)->endOfMonth();
        $maxId = '';
        $items = [];
        if($accessToken = session()->get('access_token')) {
            $client = new \GuzzleHttp\Client;

            try{
                $loopBreaker = 0;
                $key = 0;
                $break = false;
                while(!$break && $loopBreaker < 5) {
                    $refNumber = 0;
                    $url = sprintf('https://api.instagram.com/v1/users/self/media/recent/?access_token=%s&max_id=%s&count=35', $accessToken, $maxId);
                    $response = $client->get($url);
                    $result = json_decode( (string) $response->getBody(), true);

                    while(!$break && ( $refNumber < count($result['data']) ) ) {
                        if(isset($result['data'][$refNumber]['created_time'])) {
                            $date = Carbon::createFromTimestampUTC($result['data'][$refNumber]['created_time']);
                            // only add the media to the array if the date is between our selected month
                            // and only add those which is of image type
                            if($date->between($selectedStart, $selectedEnd) && $result['data'][$refNumber]['type'] == 'image') {
                                $items['media'][$key] = $result['data'][$refNumber];
                                $items['media'][$key]['date'] = $date;
                                $key++;
                            }

                            // break out of the loop once the start if before the selected start
                            if($date->lt($selectedStart)) {
                                $break = true;
                            }
                        }
                        $refNumber++;
                    }

                    if(isset($result['pagination']) && isset($result['pagination']['next_max_id'])) {
                        $maxId = $result['pagination']['next_max_id'];
                    }
                    else {
                        $break = true;
                    }
                    $loopBreaker++;
                }


            }
            catch (\Exception $e){

            }
        }
        return $items;
    }

    public static function generatePicture($postNr = 0, $data = [], $likes = 0, $count = 0, $date = '')
    {
        $user = Auth::user();
        $name = $user->username;

        $storagePath = config('settings.post_image_storage_path');
        $saveFileName = '';
        $width = 640;
        $height = 640;
        $textRow = 60;
        $coordinates = array(
            array('x' => 0, 'y' => 0),
            array('x' => $width/2, 'y' => 0),
            array('x' => 0, 'y' => $height/2),
            array('x' => $width/2, 'y' => $height/2)
        );
        if(isset($data)) {
            // resize the image to fit into a square of 200px width
            $image = Image::canvas($width, $height + $textRow, '#FFFFFF' );
            for($i = 0; $i < $postNr; $i++) {
                $post = null;
                if(isset($data[$i])) {
                    $post = Image::make($data[$i]['images']['standard_resolution']['url'])->resize($width/2, $height/2);
                }
                else {
                    $post = Image::make(public_path('img/white.png'))->resize($width/2, $height/2);
                }
                $image->insert($post, 'top-left', $coordinates[$i]['x'], $coordinates[$i]['y']);
            }

            // generate text, use callback to define details
            $usertext = $name;
            $image->text( $usertext, 10, $height + 15 + ( $textRow / 4 ), function($font) use ($textRow) {
                $font->file(public_path('css/font/instagram.ttf'));
                $font->size($textRow * 0.4);
                $font->color('#428bca');
                $font->align('left');
                $font->valign('bottom');
            });

            // generate text, use callback to define details
            $text = $likes . ' likes to ' . $count . ' posts in ' . Carbon::parse($date)->format('M Y') . ' #mygreatfans';
            $image->text( $text, 10, $height + 15 + ( $textRow / 4 * 3 ), function($font) use ($textRow) {
                $font->file(public_path('css/font/instagram.ttf'));
                $font->size($textRow * 0.4);
                $font->color('#999999');
                $font->align('left');
                $font->valign('bottom');
            });

            $today = Carbon::today();
            $folderName =  $today->format('Ymd') . DIRECTORY_SEPARATOR . md5(Auth::id());
            $imageFolderPath = self::getFolderPath($folderName);
            $fileName = Carbon::parse($date)->format('MY') . '.jpg';
            $saveFilePath = $storagePath . $imageFolderPath . $fileName;
            $saveFileName = $imageFolderPath . $fileName;
            $image->save(public_path('img/'.md5(Auth::id().microtime()).'.jpg'));
            Storage::put($saveFilePath, $image->stream()->__toString());

            // Destroy the image
            $image->destroy();
            unlink( $image->dirname.'/'.$image->basename);
        }

        return $saveFileName;
    }
    /*
     * Get the tags in the caption
     * @param $caption
     * @return array The tags
     */
    public static function getTags($caption)
    {
        preg_match_all("/(#\w+)/", $caption, $tags);

        return $tags;
    }

    /*
     * Get user and update user if there is any changes
     * @param $username
     * @param $username
     * @param $userPic
     * @return User $user
     */
    public static function getUser($instagram_id, $username, $userPic, $name)
    {
        $user = User::where('instagram_id', $instagram_id)->first();

        if($user){
            if($user->username !== $username
                || $user->user_pic !== $userPic
                || $user->name !== $name){
                $user->username = $username;
                $user->user_pic = $userPic;
                $user->name = $name;
                $user->save();
            }
        }

        return $user;
    }

    /**
     * Get the file storage path set in the application configuration,
     * appended with the folder name / filename folder structure
     *
     * @param string $fileName Optional filename to append
     * @return string
     */
    public static function getFolderPath($folderName='')
    {
        return $folderName !== '' ? $folderName . DIRECTORY_SEPARATOR : '';
    }
}

?>