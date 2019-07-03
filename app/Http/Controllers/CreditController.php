<?php

namespace App\Http\Controllers;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use PayPal\Rest\ApiContext;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;


class CreditController extends Controller
{
    private $_api_context;

    /**
     *  Create a new controller instance
     *
     * @return void
     */
    public function __construct()
    {
        /* Setup Paypal API context */
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function show()
    {
        $user = Auth::user();
        $username = $user->username;
        $userFullName = $user->name;
        $profilePic = $user->user_pic;

        return view('credit.show', compact('user', 'username', 'userFullName', 'profilePic'));
    }

    /**
     * Process the payment via paypal
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPaypalPayment(Request $request)
    {
        $currency = 'USD';
        $code = $request->get('code');
        $result = self::getTopUpDetails($code);

        if(empty($result)) {
            flash()->error('Some error has occurred. Please try again later.');
            return redirect()->back();
        }

        $price = $result['price'];
        $name = $result['name'];
        $description = $result['description'];
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName($name)
            ->setCurrency($currency)
            ->setQuantity(1)
            ->setPrice($price);

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency($currency)
            ->setTotal($price);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($description);

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('credit.paypal.status'))
            ->setCancelUrl(URL::route('credit.paypal.status'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->_api_context);
        } catch (PayPalConnectionException $ex) {
            if(Config::get('app.debug')) {
                flash()->error('Connection timeout');
                return redirect()->route('credit');
            } else {
                flash()->error('Some error has occurred. Please try again later.');
                return redirect()->route('credit');
            }
        }

        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        if(isset($redirect_url)) {
            /** Add payment ID to session */
            Session::put('paypal_payment_id', $payment->getId());
            Session::put('credit', $result['credit']);
            Session::put('item', $result['name']);
            Session::put('price', $result['price']);
            return Redirect::away($redirect_url);
        }

        flash()->error('Unknown error occurred. Please try again later.');
        return redirect()->route('credit');
    }

    public function getPaypalPaymentStatus()
    {
        /** Get the payment ID before clearing the session */
        $payment_id = Session::get('paypal_payment_id');
        $credit = Session::get('credit');
        $item = Session::get('item');
        $price = Session::get('price');
        Session::forget('paypal_payment_id');
        Session::forget('credit');
        Session::forget('item');
        Session::forget('price');

        if(empty(Input::get('PayerID')) || empty(Input::get('token'))) {
            flash()->error('Payment transaction has failed. Please try again later.');
            return redirect()->route('credit');
        }

        $payment = Payment::get($payment_id, $this->_api_context);

        /**
         * PaymentExecution object includes information necessary to execute a Paypal account payment
         * The payer_id is added to the request query parameters
         * when the user is redirected from paypal back to here
         * */

        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));
        $result = $payment->execute($execution, $this->_api_context);

        if($result->getState() == 'approved') {
            $user = Auth::user();
            $user->credit_balance = $user->credit_balance + $credit;
            $user->save();

            $description = $user->name . ' has topped up credit of ' . $item . ' at the price of ' . $price;
            TransactionService::recordTransaction(-$credit, $description, null, true);

            flash()->success('You have successfully top up your credit!');
            return redirect()->route('credit');
        }

        flash()->error('Payment transaction has failed. Please try again later.');
        return redirect()->route('credit');

    }

    protected function getTopUpDetails($orderCode = '')
    {
        $result = [];

        if($orderCode == 'iwHE#5&GAs') {
            // for order of 50 diamonds
            $result['price'] = 5;
            $result['description'] = 'Credit Top Up ('.config('app.name').')';
            $result['name'] = '50 units of diamonds';
            $result['credit'] = 50;
        } else if($orderCode == '#$WHTgH@o3') {
            // for order of 50 diamonds
            $result['price'] = 10;
            $result['description'] = 'Credit Top Up ('.config('app.name').')';
            $result['name'] = '120 units of diamonds';
            $result['credit'] = 120;
        } else if($orderCode == 'Hrs#6eT64$') {
            // for order of 50 diamonds
            $result['price'] = 25;
            $result['description'] = 'Credit Top Up ('.config('app.name').')';
            $result['name'] = '350 units of diamonds';
            $result['credit'] = 350;
        }

        return $result;

    }
}
