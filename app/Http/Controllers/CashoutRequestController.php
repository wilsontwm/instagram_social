<?php

namespace App\Http\Controllers;

use App\CashoutRequest;
use App\CashoutRequestItem;
use App\Gift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CashoutRequestController extends Controller
{
    /**
     * @var CashoutRequest
     */
    private $cashoutRequest;

    /**
     * CashoutRequestController constructor.
     * @param CashoutRequest $cashoutRequest
     */
    public function __construct(CashoutRequest $cashoutRequest)
    {
        $this->cashoutRequest = $cashoutRequest;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = 15;
        $user = Auth::user();
        $username = $user->username;
        $userFullName = $user->name;
        $profilePic = $user->user_pic;
        $cashoutRequests = $user->cashoutRequests()->orderBy('created_at', 'desc')->paginate($limit);

        return view('cashouts.index', compact('user', 'username', 'userFullName', 'profilePic', 'cashoutRequests'));
    }

    /** Show the form for creating a new response
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();
        $username = $user->username;
        $userFullName = $user->name;
        $profilePic = $user->user_pic;
        $gifts = $user->gifts()->wherePivot('count', '>', 0)->get();

        return view('cashouts.create', compact('user', 'username', 'userFullName', 'profilePic', 'gifts'));
    }

    /** Store a newly created resource in storage.
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cashout_items'          => 'required'
        ]);

        if ($validator->fails()){
            flash()->error('Some error has occurred. Please try again later.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $input['user_id'] = $user->id;
        $cashoutRequest = $this->cashoutRequest->create($input);

        $rate = config('settings.cashout_conversion_rate');
        $datas = json_decode($request['cashout_items'][0], true);
        $total = 0;
        foreach($datas as $data){
            foreach($data as $key => $value) {
                $qty = $value >= 0 ? $value : 0;
                $gift = Gift::find($key);
                if($gift && $qty > 0) {
                    $total += $gift->price * $rate * $qty;
                    $cashoutItem = $cashoutRequest->cashoutRequestItems()->where('gift_id', $gift->id)->first();

                    if($cashoutItem) {
                        $cashoutItem->quantity += $qty;
                        $cashoutItem->save();
                    } else {
                        CashoutRequestItem::create([
                            'cashout_request_id' => $cashoutRequest->id,
                            'gift_id' => $gift->id,
                            'quantity' => $qty
                        ]);
                    }
                }
            }
        }

        $cashoutRequest->amount = $total;
        $cashoutRequest->save();

        flash()->success('You have successfully made a cash out. Your cash out will be processed in 3 to 5 working days.');
        return redirect()->route('cashout.index');
    }

    /** Display the specified resource.
     *
     * @param CashoutRequest $cashoutRequest
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(CashoutRequest $cashout)
    {
        $this->authorize('view', $cashout);
        $user = Auth::user();
        $username = $user->username;
        $userFullName = $user->name;
        $profilePic = $user->user_pic;

        foreach (Auth::user()->unreadNotifications as $notification) {
            if ( $notification->type == 'App\Notifications\CashoutProcessed' && $notification->data['cashout_id'] == $cashout->id) {
                $notification->markAsRead();
            }
        }

        return view('cashouts.show', compact('user', 'username', 'userFullName', 'profilePic', 'cashout'));
    }

    /** Withdraw the specified cash out request
     * @param Request $request
     * @param CashoutRequest $cashoutRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function withdraw(CashoutRequest $cashout)
    {
        $this->authorize('withdraw', $cashout);
        if(!$cashout->canWithdraw())
        {
            flash()->error('You are not allowed to withdraw '.$cashout->getStatus().' cash out');
            return redirect()->back();
        }

        $cashout->update([
            'status' => CashoutRequest::STATUS_WITHDRAWN
        ]);

        flash()->success('You have successfully withdrawn the cash out request');
        return redirect()->back();
    }

    /** Return the amount of gifts based on the quantity and the rate by performing the calculation
     * @param Request $request
     * @return array|string
     */
    public function getAmount(Request $request)
    {
        $result = 0.0;
        $rate = config('settings.cashout_conversion_rate');
        $giftsInput = $request->input('gifts', '');
        $datas = json_decode($giftsInput, true);
        foreach($datas as $data){
            foreach($data as $key => $value) {
                $qty = $value >= 0 ? $value : 0;
                $gift = Gift::find($key);
                if($gift && $qty > 0) {
                    $result += $gift->price * $rate * $qty;
                }
            }
        }

        return $result;
    }
}
