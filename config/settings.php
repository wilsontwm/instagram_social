<?php

return [

    /*
   |--------------------------------------------------------------------------
   | Gift Image
   |--------------------------------------------------------------------------
   |
   | The settings for all gift images
   |
   */

    /*
     * The resized product image maximum dimensions
     */
    'gift_image_size' => [160, 160],

    /*
     * Whether or not to store thumbnails for product images. To disable thumbnails, use an empty array - []
     */
    'gift_thumbnail_sizes' => [],

    'gift_image_storage_path'   => 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'gifts' . DIRECTORY_SEPARATOR,
    'gift_image_url_path'       => 'storage' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'gifts' . DIRECTORY_SEPARATOR,

    /*
   |--------------------------------------------------------------------------
   | TOP POST Image
   |--------------------------------------------------------------------------
   |
   | The settings for all top post images
   |
   */
    'post_image_storage_path'   => 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR,
    'post_image_url_path'       => 'storage' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR,

    /*
   |--------------------------------------------------------------------------
   | CASH OUT
   |--------------------------------------------------------------------------
   |
   | The settings for cash out
   |
   */

    /*
     * The min amount to make a cash out request
     */
    'min_cash_out_amount' => 10,

    /*
     * The conversion rate of USD:credit
     */
    'cashout_conversion_rate' => 0.06,
];
