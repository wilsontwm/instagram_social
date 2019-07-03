<?php

namespace App\Http\Controllers\Admin;

use App\Gift;
use App\Http\Requests\CreateUpdateGiftRequest;
use App\Services\GiftService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class GiftController extends Controller
{
    private $gift;

    /**
     * Constructor for the controller
     * @param Gift $gift
     */
    public function __construct(Gift $gift)
    {
        $this->contentTitle = 'Gift management';
        $this->gift = $gift;
    }

    /**
     * Display a list of resource
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('indexAdmin', Gift::class);

        $contentTitle = $this->contentTitle;
        $status = $request['status'];
        $gifts = [];
        if($status && $status == 'all')
        {
            $gifts = Gift::orderBy('title', 'asc')->paginate(25);
        }
        else
        {
            $gifts = Gift::where('status', '<>', Gift::STATUS_ARCHIVED)->orderBy('title', 'asc')->paginate(25);
        }

        return view('admin.gifts.index', compact('gifts', 'contentTitle'));
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', Gift::class);

        $contentTitle = $this->contentTitle;

        return view('admin.gifts.create', compact('contentTitle'));
    }

    /**
     * Store a newly created resource in storage
     * @param CreateUpdateGiftRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateUpdateGiftRequest $request)
    {
        $this->authorize('create', Gift::class);
        $input = $request->all();
        $gift = $this->gift->create($input);

        return redirect()->route('admin.gifts.picture', [$gift]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param Gift $gift
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Gift $gift)
    {
        $this->authorize('update', $gift);
        $contentTitle = $this->contentTitle;

        return view('admin.gifts.edit', compact('gift', 'contentTitle'));
    }

    /**
     * Update the specified resource.
     * @param CreateUpdateGiftRequest $request
     * @param Gift $gift
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CreateUpdateGiftRequest $request, Gift $gift)
    {
        $this->authorize('update', $gift);
        $input = $request->all();
        $gift->fill($input)->save();

        return redirect()->route('admin.gifts.picture', [$gift]);
    }

    /**
     * Show the form for upload picture for the specified resource.
     * @param Gift $gift
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function picture(Gift $gift)
    {
        $this->authorize('update', $gift);
        $contentTitle = $this->contentTitle;

        return view('admin.gifts.picture', compact('gift', 'contentTitle'));
    }

    public function storePicture(Request $request, Gift $gift)
    {
        $this->authorize('update', $gift);
        if($request->hasFile('image')) {
            $allowed = array('image/x-png', 'image/png');
            if (!in_array($request->file('image')->getMimeType(), $allowed)) {
                // File type note allowed
                flash()->error('Only PNG images are allowed.');
                return redirect()->back();
            } else {
                try {
                    $file = $request->file('image');
                    $fileName = md5(microtime().$file->getClientOriginalName()).'.'.$file->getClientOriginalExtension();
                    if (isset($_SERVER['HTTP_ORIGIN'])) {
                        // same-origin requests won't set an origin. If the origin is set, it must be valid.
                        if ($_SERVER['HTTP_ORIGIN'] == url('/')) {
                            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
                        } else {
                            header("HTTP/1.0 403 Origin Denied");
                            return;
                        }
                    }

                    // Remove existing photo
                    $storagePath = config('settings.gift_image_storage_path');
                    $imagePath = $storagePath . DIRECTORY_SEPARATOR . $gift->id;
                    Storage::deleteDirectory($imagePath);

                    // Accept upload if there was no origin, or if it is an accepted origin
                    $imageArr = GiftService::storeImage($file, $gift->id, $fileName);
                    $gift->pic_url = $imageArr['path'];
                    $gift->save();

                    flash()->success('Successfully uploaded the gift image');
                    return redirect()->route('admin.gifts.index');
                } catch (FileException $e) {
                    flash()->error('Unable to upload photo to server');
                    return redirect()->back();
                }
            }
        } else {
            flash()->error('No image was selected');
            return redirect()->back();
        }

    }

    /**
     * Remove the picture for the specified resource
     * @param Gift $gift
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyPicture(Gift $gift)
    {
        $this->authorize('update', $gift);
        $storagePath = config('settings.gift_image_storage_path');
        $imagePath = $storagePath . DIRECTORY_SEPARATOR . $gift->id;
        Storage::deleteDirectory($imagePath);
        $gift->pic_url = null;
        $gift->save();

        flash()->success('Successfully removed the gift image');
        return redirect()->back();
    }

}
