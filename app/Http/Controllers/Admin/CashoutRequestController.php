<?php

namespace App\Http\Controllers\Admin;

use App\CashoutRequest;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $this->contentTitle = 'Cashout management';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('admin', CashoutRequest::class);
        $contentTitle = $this->contentTitle;
        $limit = 15;
        $statusFilter = ['all' => 'All'] + CashoutRequest::STATUS_ARRAY;
        $user = $request->input('user', '');
        $status = $request->input('status', 'all');

        $cashoutRequests = CashoutRequest::where(function($query) use ($status) {
            if($status !== 'all') {
                $query->where('status', $status);
            }
        })
        ->whereHas('user', function($query) use ($user){
            if($user !== '') {
                $query->where('name', 'LIKE', '%' . $user . '%')
                    ->orWhere('username', 'LIKE', '%' . $user . '%');
            }
        })
        ->orderBy('created_at', 'desc')
        ->paginate($limit);

        return view('admin.cashouts.index', compact('contentTitle', 'cashoutRequests', 'statusFilter', 'status', 'user'));
    }

    /** Display the specified resource.
     *
     * @param CashoutRequest $cashoutRequest
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(CashoutRequest $cashout)
    {
        $this->authorize('admin', CashoutRequest::class);
        $contentTitle = $this->contentTitle;
        $status = CashoutRequest::STATUS_ARRAY;
        return view('admin.cashouts.show', compact('contentTitle', 'cashout', 'status'));
    }

    /** Process the specified cash out request
     * @param Request $request
     * @param CashoutRequest $cashoutRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process(Request $request, CashoutRequest $cashout)
    {
        $this->authorize('admin', CashoutRequest::class);

        if($cashout->isProcessed()) {
            flash()->error('The cash out has already been processed');
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'amount'          => 'required|numeric'
        ]);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $input = $request->all();

        $cashout->update([
            'status' => $input['status'],
            'amount' => $input['amount'],
            'remarks' => $input['remarks']
        ]);

        // deduct from the user's gift count
        if($input['status'] == CashoutRequest::STATUS_APPROVED) {
            foreach($cashout->cashoutRequestItems as $cashoutRequestItem) {
                TransactionService::updateGiftCount($cashout->user, $cashoutRequestItem->gift, -$cashoutRequestItem->quantity);
            }
        }

        $cashout->user->notify(new \App\Notifications\CashoutProcessed($cashout));

        flash()->success('You have successfully processed the cash out request');
        return redirect()->back();
    }
}
