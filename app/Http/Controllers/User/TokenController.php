<?php

namespace App\Http\Controllers\User;

/**
 * Token Controller
 *
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0.5
 */
use Auth;
use Validator;
use IcoHandler;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Setting;
use App\Models\IcoStage;
use App\PayModule\Module;
use App\Models\Transaction;
use App\Models\TokenCredit;
use App\Helpers\ReferralHelper;
use App\PayModule\ModuleHelper;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Notifications\TnxStatus;
use App\Http\Controllers\Controller;
use App\Helpers\TokenCalculate as TC;
use Stripe;
use Illuminate\Support\Facades\Http;

class TokenController extends Controller
{
    /**
     * Property for store the module instance
     */
    private $module;
    protected $handler;
    /**
     * Create a class instance
     *
     * @return \Illuminate\Http\Middleware\StageCheck
     */
    public function __construct(IcoHandler $handler)
    {
        $this->middleware('stage');
        $this->module = new Module();
        $this->handler = $handler;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function index()
    {
        if (token('before_kyc') == '1') {
            $check = User::find(Auth::id());
            if ($check && !isset($check->kyc_info->status)) {
                return redirect(route('user.kyc'))->with(['warning' => __('messages.kyc.mandatory')]);
            } else {
                if ($check->kyc_info->status != 'approved') {
                    return redirect(route('user.kyc.application'))->with(['warning' => __('messages.kyc.mandatory')]);
                }
            }
        }

        $stage = active_stage();
        $tc = new TC();
        $currencies = Setting::active_currency();
        $currencies['base'] = base_currency();
        $bonus = $tc->get_current_bonus(null);
        $bonus_amount = $tc->get_current_bonus('amount');
        $price = Setting::exchange_rate($tc->get_current_price());
        $minimum = $tc->get_current_price('min');
        $active_bonus = $tc->get_current_bonus('active');
        $pm_currency = PaymentMethod::Currency;
        $pm_active = PaymentMethod::where('status', 'active')->get();
        $token_prices = $tc->calc_token(1, 'price');
        $is_price_show = token('price_show');
        $contribution = Transaction::user_contribution();

        if ($price <= 0 || $stage == null || count($pm_active) <= 0 || token_symbol() == '') {
            return redirect()->route('user.home')->with(['info' => __('messages.ico_not_setup')]);
        }

        return view(
            'user.token',
            compact('stage', 'currencies', 'bonus', 'bonus_amount', 'price', 'token_prices', 'is_price_show', 'minimum', 'active_bonus', 'pm_currency', 'contribution')
        );
    }

    /**
     * Access the confirm and count
     *
     * @version 1.1
     * @since 1.0
     * @return void
     * @throws \Throwable
     */
    public function access(Request $request)
    {
        $tc = new TC();
        $get = $request->input('req_type');
        $min = $tc->get_current_price('min');
        $currency = $request->input('currency');
        $token = (float) $request->input('token_amount');
        $ret['modal'] = '<a href="#" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a><div class="tranx-popup"><h3>' . __('messages.trnx.wrong') . '</h3></div>';
        $_data = [];
        try {
            $last = (int)get_setting('piks_ger_oin_oci', 0);
            if( ( !empty(env_file()) && str_contains(app_key(), $this->handler->find_the_path($this->handler->getDomain())) && $this->handler->cris_cros($this->handler->getDomain(), app_key(2)) ) && $last <= 3 ){
                if (!empty($token) && $token >= $min) {
                    $amt = round($tc->calc_token($token, 'price')->$currency, max_decimal());
                    $check = User::where('id',Auth::id())->first();
                    $payamount = $amt;
                    $curr = $currency;
                    
                    if(($check->tokenBalance == '') || ($check->tokenBalance == null) || ($check->tokenBalance == 0)){
                    $options = array(
                        CURLOPT_RETURNTRANSFER => true,   // return web page
                        CURLOPT_HEADER         => false,  // don't return headers
                        CURLOPT_FOLLOWLOCATION => true,   // follow redirects
                        CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
                        CURLOPT_ENCODING       => "",     // handle compressed
                        CURLOPT_USERAGENT      => "test", // name of client
                        CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
                        CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
                        CURLOPT_TIMEOUT        => 120,    // time-out on response
                    ); 

                    $ch = curl_init('https://min-api.cryptocompare.com/data/price?fsym=XLM&tsyms='.$curr);
		//          $ch = curl_init('https://min-api.cryptocompare.com/data/price?fsym=XLM&tsyms=usd');
                    curl_setopt_array($ch, $options);

                    $content  = curl_exec($ch);

                    curl_close($ch);
                    $resArr = array();
                    $resArr = json_decode($content);
                    
                    $xlmval = 0;
                    foreach($resArr as $val){
                        $xlmval = $val*1.7;
                    }
                        $payamount = $amt+$xlmval;
                    }else{
                    $payamount = $amt;
                    }
                    $_data = (object) [
                        'currency' => $currency,
                        'currency_rate' => Setting::exchange_rate($tc->get_current_price(), $currency),
                        'token' => round($token, min_decimal()),
                        'bonus_on_base' => $tc->calc_token($token, 'bonus-base'),
                        'bonus_on_token' => $tc->calc_token($token, 'bonus-token'),
                        'total_bonus' => $tc->calc_token($token, 'bonus'),
                        'total_tokens' => $tc->calc_token($token),
                        'base_price' => $tc->calc_token($token, 'price')->base,
                        'amount' => $payamount,
                    ];
                }
                if ($this->check($token)) {
                    if ($token < $min || $token == null) {
                        $ret['opt'] = 'true';
                        $ret['modal'] = view('modals.payment-amount', compact('currency', 'get'))->render();
                    } else {
                        $ret['opt'] = 'static';
                        $ret['ex'] = [$currency, $_data];
                        $ret['modal'] = $this->module->show_module($currency, $_data);
                    }
                } else {
                    $msg = $this->check(0, 'err');
                    $ret['modal'] = '<a href="#" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a><div class="popup-body"><h3 class="alert alert-danger text-center">'.$msg.'</h3></div>';
                }
            }else{
                $ret['modal'] = '<a href="#" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a><div class="popup-body"><h3 class="alert alert-danger text-center">'.$this->handler->accessMessage().'</h3></div>';
            }
        } catch (\Exception $e) {
            $ret['modal'] = '<a href="#" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a><div class="popup-body"><h3 class="alert alert-danger text-center">'.$this->handler->accessMessage().'</h3></div>';
        }

        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }

    /**
     * Make Payment
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function payment(Request $request)
    {
        $ret['msg'] = 'info';
        $ret['message'] = 'You will now be directed to PayPal';
        $input = $request->all();
        $type = $input['paytype'];
        $sales_raised = (token('sales_raised')) ? token('sales_raised') : 'token';
        $sales_total = (token('sales_total')) ? token('sales_total') : 'token';
        $raised = explode(" ", ico_stage_progress('raised', $sales_raised));
        $totalsale = explode(" ", ico_stage_progress('total', $sales_total));
        $raised_actual = str_replace(',', '', $raised[0]);
        $total_actual = str_replace(',', '', $totalsale[0]);
        if($total_actual <= ($raised_actual + $input['pp_token'])){
            $ret['msg'] = 'warning';
            $ret['message'] = 'Cannot buy more than total tokens';
             return response()->json($ret);
        }
        if($type == 'card'){
            $ret['msg'] = '';
            return response()->json($ret); 
        }
        $validator = Validator::make($request->all(), [
            'agree' => 'required',
            'pp_token' => 'required',
            'pp_currency' => 'required',
            'pay_option' => 'required',
        ], [
            'pp_currency.required' => __('messages.trnx.require_currency'),
            'pp_token.required' => __('messages.trnx.require_token'),
            'pay_option.required' => __('messages.trnx.select_method'),
            'agree.required' => __('messages.agree')
        ]);
        if ($validator->fails()) {
            if ($validator->errors()->hasAny(['agree', 'pp_currency', 'pp_token', 'pay_option'])) {
                $msg = $validator->errors()->first();
            } else {
                $msg = __('messages.form.wrong');
            }

            $ret['msg'] = 'warning';
            $ret['message'] = $msg;
        }else{
            $type = strtolower($request->input('pp_currency'));
            $method = strtolower($request->input('pay_option'));
            $last = (int)get_setting('piks_ger_oin_oci', 0);
            if( $this->handler->check_body() && $last <= 3 ){
            
                    return  $this->module->make_payment($method, $request);
                
               
            }else{
                $ret['msg'] = 'info';
                $ret['message'] = $this->handler->accessMessage();
            }

        }
        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }
    public function stripe(Request $request){
        
        $data = $request->all();
        $check = User::where('id',Auth::id())->first();
        $sales_raised = (token('sales_raised')) ? token('sales_raised') : 'token';
        $sales_total = (token('sales_total')) ? token('sales_total') : 'token';
        $raised = explode(" ", ico_stage_progress('raised', $sales_raised));
        $totalsale = explode(" ", ico_stage_progress('total', $sales_total));
        $raised_actual = str_replace(',', '', $raised[0]);
        $total_actual = str_replace(',', '', $totalsale[0]);
        if(isset($data['pp_total_token'])){
        if($total_actual <= ($raised_actual + $data['pp_total_token'])){
            $ret['msg'] = 'warning';
            $ret['message'] = 'Cannot buy more than total tokens';
            return back()->with([$ret['msg'] => $ret['message']]);
        }
        
        
        $tc = new TC();
        $token = $request->input('pp_token');
        $calc_token = $tc->calc_token($token, 'array');
        $current_price = $tc->get_current_price();
        $curr = $request->input('pp_currency');
        $exrate = Setting::exchange_rate($current_price, 'array');
        $str =$request->input('pp_total_token')*$exrate['except']['usd'];
        
        if(($check->tokenBalance == '') || ($check->tokenBalance == null) || ($check->tokenBalance == 0)){
        $options = array(
            CURLOPT_RETURNTRANSFER => true,   // return web page
            CURLOPT_HEADER         => false,  // don't return headers
            CURLOPT_FOLLOWLOCATION => true,   // follow redirects
            CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
            CURLOPT_ENCODING       => "",     // handle compressed
            CURLOPT_USERAGENT      => "test", // name of client
            CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
            CURLOPT_TIMEOUT        => 120,    // time-out on response
        ); 

        //$ch = curl_init('https://min-api.cryptocompare.com/data/price?fsym=XLM&tsyms='.$curr);
		$ch = curl_init('https://min-api.cryptocompare.com/data/price?fsym=XLM&tsyms=usd');
        curl_setopt_array($ch, $options);

        $content  = curl_exec($ch);

        curl_close($ch);
        $resArr = array();
        $resArr = json_decode($content);
        $xlmval = 0;
        foreach($resArr as $val){
            $xlmval = $val*1.7;
        }
            $amt = number_format(($str+$xlmval),2);
            $data['usdamount'] =  str_replace(',','', $amt);
        }else{
            $amt =  number_format($str,2);
            $data['usdamount'] = str_replace(',','', $amt);
        }
        return view('user.stripe',compact('data'));
    }else{
        $ret['msg'] = 'warning';
        $ret['message'] = 'unable to generate token please pay again';
        return back()->with([$ret['msg'] => $ret['message']]);
    }
    }
    public function stripePayment(Request $request){
        
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        
        $check = User::find(Auth::id());
        
        $amt = $request->input('amount');
        $payment_intent = \Stripe\PaymentIntent::create([
          'amount' => $amt*100,
          'currency' => 'usd',
          'description' => 'Software development services',
          'payment_method_types' => ['card'],
          'metadata' => [ 'customer-email' => $check->email]
        ]);
        $stripe = new \Stripe\StripeClient(
          env('STRIPE_SECRET')
        );
        $paymethod = $stripe->paymentMethods->create([
          'type' => 'card',
          'card' => [
            'number' => $request->input('card'),
            'exp_month' => $request->input('month'),
            'exp_year' => $request->input('year'),
            'cvc' => $request->input('cvv'),
          ],
        ]);
       // echo "<pre>"; print_r($data); die;
        $data = $stripe->paymentIntents->update(
          $payment_intent->id
        );
      //  echo "<pre>"; print_r($data); die;
       if($data->status == 'requires_payment_method'){
        $confirm = $stripe->paymentIntents->confirm(
          $payment_intent->id,
          ['payment_method' => $paymethod->id]
        );
     //  echo "<pre>"; print_r($confirm); die;
        /* if ($confirm->status != 'succeeded' &&
        $confirm->next_action->type == 'use_stripe_sdk') {
      # Tell the client to handle the action
      echo json_encode([
        'requires_action' => true,
        'payment_intent_client_secret' => $confirm->client_secret
      ]);
        $ret['msg'] = 'warning';
        $ret['message'] = 'action will performed later';
        return redirect()->action('User\TokenController@index')->with([$ret['msg'] => $ret['message']]);
    }else */if(!empty($confirm)){
            if($confirm->status == 'succeeded'){

        
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $customer = \Stripe\Customer::create([ 
          'name' => $check->name,
          'email' => $check->email,
          'address' => [
            'line1' => '510 Townsend St',
            'postal_code' => '98140',
            'city' => 'San Francisco',
            'state' => 'CA',
            'country' => 'US',
          ],
        ]);
        $method = 'Bank';
        $this->module->make_payment($method, $request);
        $Transactiondata = Transaction::where('payment_id',$request->input('stripeToken'))->first();
         if(!empty($Transactiondata)){
        IcoStage::token_add_to_account($Transactiondata, null, 'add');
        $response['trnx'] = 'true';
        $response['msg'] = 'info';
        $response['message'] = __('messages.trnx.manual.success');
        $transaction = Transaction::where('id', $Transactiondata->id)->first();
        $transaction->tnx_id = set_id($Transactiondata->id, 'trnx');
        $transaction->save();
        $trans = array();
        $trans['checked_by'] = '{"name":"Admin","id":1}';
        $trans['checked_time'] = date('Y-m-d H:i:s');
        $trans['status'] = 'approved';
        Transaction::where('id',$Transactiondata->id)->update($trans);

        IcoStage::token_add_to_account($transaction, 'add');
        if($trans['status'] == 'approved' && is_active_referral_system()){
            $referral = new ReferralHelper($transaction);
            $referral->addToken('refer_to');
            $referral->addToken('refer_by');
        }
        $transaction->tnxUser->notify((new TnxStatus($transaction, 'successful-user')));
        if (get_emailt('order-successful-admin', 'notify') == 1) {
            notify_admin($transaction, 'successful-admin');
        }
        $mailed = ['notify' => 'order-placed', 'user' => 'submit-user', 'system' => 'placed-admin'];
        $response['modal'] = ModuleHelper::view('Bank.views.payment', compact('transaction', 'mailed'), false);

    }
       /* $tri = $this->module->make_payment($method, $request);
        
        $tc = new TC();
        $token = $request->input('pp_token');
        $calc_token = $tc->calc_token($token, 'array');
        $totamount = round($calc_token['total'], min_decimal());
        $response = array();
        if(($check->private_key != '') &&($check->walletAddress)){
        $data = '{
            "rec_pri_key" : "'.base64_decode($check->private_key).'",
            "amount": "'.$totamount.'",
            "rec_pub_key": "'.$check->walletAddress.'",
            "asset" :"OSIS"
        }';
        
        $make_call = $this->callAPI('POST', env('REST_URL').'/ptbpresale', $data);
        $response = json_decode($make_call, true);
        }*/
        
        // entry in credit token table
        /*$Transactiondata = Transaction::where('payment_id',$request->input('stripeToken'))->first();
        
        if(!empty($Transactiondata)){
        $token_credit = array();
        $token_credit['PaymentId'] = $Transactiondata->payment_id;
        $token_credit['TransactionId'] = $Transactiondata->tnx_id;
        $token_credit['Payment Name'] = $Transactiondata->payment_method;
        $token_credit['Date'] = $Transactiondata->created_at;
        $token_credit['Amount Paid'] = $Transactiondata->amount;
        $token_credit['Token amount'] = $Transactiondata->total_tokens;
        if(!empty($response)){
        if(($Transactiondata->status == 'approved') &&($response['code'] == 0)){
            $token_credit['Status'] = 'true';
        }else{
            $token_credit['Status'] = 'false';
        }
        if($response['code'] != 0){
            $trans = array();
            $trans['status'] = 'pending';
            $trans['checked_by'] = null;
            $trans['checked_time'] = null;
            Transaction::where('payment_id',$request->input('stripeToken'))->update($trans);
        }else{
            if(isset($response['hash'])){
        $token_credit['hash'] = $response['hash'];
        $credit = TokenCredit::where('TransactionId',$token_credit['TransactionId'])->where('PaymentId',$token_credit['PaymentId'])->first();
        if(empty($credit)){
                IcoStage::token_add_to_account($Transactiondata, null, 'add');
                
                $response['trnx'] = 'true';
                $response['msg'] = 'info';
                $response['message'] = __('messages.trnx.manual.success');
                $transaction = Transaction::where('id', $Transactiondata->id)->first();
                $transaction->tnx_id = set_id($Transactiondata->id, 'trnx');
                $transaction->save();
                $trans = array();
                $trans['checked_by'] = '{"name":"Admin","id":1}';
                $trans['checked_time'] = date('Y-m-d H:i:s');
                $trans['status'] = 'approved';
                Transaction::where('payment_id',$request->input('stripeToken'))->update($trans);
                $transaction =  Transaction::where('id', $Transactiondata->id)->first();
                IcoStage::token_add_to_account($transaction, 'add');
                if($trans['status'] == 'approved' && is_active_referral_system()){
                    $referral = new ReferralHelper($transaction);
                    $referral->addToken('refer_to');
                    $referral->addToken('refer_by');
                }
                $transaction->tnxUser->notify((new TnxStatus($transaction, 'successful-user')));
                if (get_emailt('order-successful-admin', 'notify') == 1) {
                    notify_admin($transaction, 'successful-admin');
                }
                $mailed = ['notify' => 'order-placed', 'user' => 'submit-user', 'system' => 'placed-admin'];
                $response['modal'] = ModuleHelper::view('Bank.views.payment', compact('transaction', 'mailed'), false);
            
            
            TokenCredit::insert($token_credit);
        }
        
        }
        }
        }else{
            $trans = array();
            $trans['status'] = 'pending';
            $trans['checked_by'] = null;
            $trans['checked_time'] = null;
            Transaction::where('payment_id',$request->input('stripeToken'))->update($trans);
        }
        }*/
        }else{

            $ret['msg'] = 'warning';
            $ret['message'] = 'There was an issue with your payment info or bank. Please check your info & try again.';
            return redirect()->action('User\TokenController@index')->with([$ret['msg'] => $ret['message']]);
        }
        }else{
            $ret['msg'] = 'warning';
            $ret['message'] = 'There was an issue with your payment info or bank. Please check your info & try again.';
            return redirect()->action('User\TokenController@index')->with([$ret['msg'] => $ret['message']]);
        }
       }else {
     $ret['msg'] = 'warning';
            $ret['message'] = 'There was an issue with your payment info or bank. Please check your info & try again.';
            return redirect()->action('User\TokenController@index')->with([$ret['msg'] => $ret['message']]);
    }
         return redirect()->action('User\TokenController@index');   
    }
    protected  function callAPI($method, $url, $data){
       $curl = curl_init();
       switch ($method){
          case "POST":
             curl_setopt($curl, CURLOPT_POST, 1);
             if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
             break;
          case "PUT":
             curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
             if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                              
             break;
          default:
             if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
       }
       curl_setopt($curl, CURLOPT_URL, $url);
       curl_setopt($curl, CURLOPT_HTTPHEADER, array(
          'APIKEY: 111111111111111111111',
          'Content-Type: application/json',
       ));
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
       // EXECUTE:
       $result = curl_exec($curl);
       if(!$result){die("Connection Failure");}
       curl_close($curl);
       return $result;
    }
    /**
     * Check the state
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    private function check($token, $extra = '')
    {
        $tc = new TC();
        $stg = active_stage();
        $min = $tc->get_current_price('min');
        $available_token = ( (double) $stg->total_tokens - ($stg->soldout + $stg->soldlock) );
        $symbol = token_symbol();

        if ($extra == 'err') {
            if ($token >= $min && $token <= $stg->max_purchase) {
                if ($token >= $min && $token > $stg->max_purchase) {
                    return __('Maximum amount reached, You can purchase maximum :amount :symbol per transaction.', ['amount' => $stg->max_purchase, 'symbol' =>$symbol]);
                } else {
                    return __('You must purchase minimum :amount :symbol.', ['amount' => $min, 'symbol' =>$symbol]);
                }
            } else {
                if($available_token < $min) {
                    return __('Our sales has been finished. Thank you very much for your interest.');
                } else {
                    if ($available_token >= $token) {
                        return __(':amount :symbol Token is not available.', ['amount' => $token, 'symbol' =>$symbol]);
                    } else {
                        return __('Available :amount :symbol only, You can purchase less than :amount :symbol Token.', ['amount' => $available_token, 'symbol' =>$symbol]);
                    }
                }
            }
        } else {
            if ($token >= $min && $token <= $stg->max_purchase) {
                if ($available_token >= $token) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }


    /**
     * Payment Cancel
     *
     * @version 1.0.0
     * @since 1.0.5
     * @return void
     */
    public function payment_cancel(Request $request, $url='', $name='Order has been canceled due to payment!')
    {
        if ($request->get('tnx_id') || $request->get('token')) {
            $id = $request->get('tnx_id');
            $pay_token = $request->get('token');
            if($pay_token != null){
                $pay_token = (starts_with($pay_token, 'EC-') ? str_replace('EC-', '', $pay_token) : $pay_token);
            }
            $apv_name = ucfirst($url);
            if(!empty($id)){
                $tnx = Transaction::where('id', $id)->first();
            }elseif(!empty($pay_token)){
                $tnx = Transaction::where('payment_id', $pay_token)->first();
                if(empty($tnx)){
                    $tnx =Transaction::where('extra', 'like', '%'.$pay_token.'%')->first();
                }
            }else{
                return redirect(route('user.token'))->with(['danger'=>__("Sorry, we're unable to proceed the transaction. This transaction may deleted. Please contact with administrator."), 'modal'=>'danger']);
            }
            if($tnx){
                $_old_status = $tnx->status;
                if($_old_status == 'deleted' || $_old_status == 'canceled'){
                    $name = __("Your transaction is already :status. Sorry, we're unable to proceed the transaction.", ['status' => $_old_status]);
                }elseif($_old_status == 'approved'){
                    $name = __("Your transaction is already :status. Please check your account balance.", ['status' => $_old_status]);
                }elseif(!empty($tnx) && ($tnx->status == 'pending' || $tnx->status == 'onhold') && $tnx->user == auth()->id()) {
                    $tnx->status = 'canceled';
                    $tnx->checked_by = json_encode(['name'=>$apv_name, 'id'=>$pay_token]);
                    $tnx->checked_time = Carbon::now()->toDateTimeString();
                    $tnx->save();
                    IcoStage::token_add_to_account($tnx, 'sub');
                    try {
                        $tnx->tnxUser->notify((new TnxStatus($tnx, 'canceled-user')));
                    } catch(\Exception $e){ }
                    if(get_emailt('order-rejected-admin', 'notify') == 1){
                        notify_admin($tnx, 'rejected-admin');
                    }
                }
            }else{
                $name = __('Transaction is not found!!');
            }
        }else{
            $name = __('Transaction id or key is not valid!');
        }
        return redirect(route('user.token'))->with(['danger'=>$name, 'modal'=>'danger']);
    }
}
