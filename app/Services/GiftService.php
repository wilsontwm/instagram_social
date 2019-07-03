<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class GiftService
{
    /**
     * Creates an image with the specified dimensions and aspect ratio
     *
     * @param $image
     * @param $width
     * @param $height
     * @param bool $aspectRatio
     * @return mixed
     */
    public static function createImage($image, $width, $height, $aspectRatio=true) {
        $img = Image::make($image);
        if (!$aspectRatio) {
            $img = $img->fit($width, $height);
        } else {
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

        }
        return $img;
    }

    /**
     * Stores an image for the gift
     *
     * @param $image
     * @param $folderName
     * @param $fileName
     * @param Company $company
     * @return string
     */
    public static function storeImage($image, $folderName, $fileName)
    {
        $storagePath = config('settings.gift_image_storage_path');
        $imageFolderPath = self::getFolderPath($folderName);
        $saveFilePath = $storagePath . $imageFolderPath . $fileName;
        $saveFileName = $imageFolderPath . $fileName;

        //Retrieving image size
        $imageSize = config('settings.gift_image_size');
        //Creating and saving the image
        $image = self::createImage($image, $imageSize[0], $imageSize[1], true);
        Storage::put($saveFilePath, $image->stream()->__toString());

        return ['width' => $image->width(), 'height' => $image->height(), 'path' => $saveFileName];
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