@php
$pm_check = (!empty($methods) ? true : false);
$dot_1 =  '.'; $dot_2 = '';
if ($data->total_bonus > 0) {
    $dot_1 =  ''; $dot_2 = '.';
}
$activeMethods = data_get(get_defined_vars(), "activeMethods", []);
@endphp
<a href="#" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a>
<div class="popup-body">
    
    <div class="popup-content">
        <form class="validate-modern" onsubmit="setDataLayer()" action="{{ route('user.payment') }}" method="POST" id="online_payment">
            @csrf
			<?php $payamount = $data->amount; ?>
            <input type="hidden" name="pp_token" id="token_amount" value="{{ $data->token }}">
            <input type="hidden" name="pp_currency" id="pay_currency" value="{{ $data->currency }}">
			<input type="hidden" name="pp_total_token" id="total_token" value="{{$data->total_tokens}}">
			<input type="hidden" value="{{to_num($payamount, 'max')}}" name="amount"  >
			
            <h4 class="popup-title">{{ __('Payment Process')}}</h4>
            <p class="lead">{!! ($data->total_bonus > 0) ? __('Please make payment of :amount to receive :token_amount token including bonus :token_bonus token.', ['amount' => '<strong>'.to_num($payamount, 'max').' <span class="pay-currency ucap">'.$data->currency.'</span></strong>', 'token_amount'=> '<strong><span class="token-total">'.$data->total_tokens.' '.token('symbol').'</span></strong>', 'token_bonus'=> '<strong><span class="token-bonuses">'.$data->total_bonus.' '.token('symbol').'</span></strong>']) : __('Please make payment of :amount to receive :token_amount tokens.', ['amount' => '<strong>'.to_num($payamount, 'max').' <span class="pay-currency ucap">'.$data->currency.'</span></strong>', 'token_amount'=> '<strong><span class="token-total">'.$data->total_tokens.' '.token('symbol').'</span></strong>']) !!}
            </p>
            @if($pm_check)
                <p>{{__('Choose your preferred payment method.')}}</p>
                <h5 class="mgt-1-5x font-mid">{{__('Select payment method:')}}</h5>
                <ul class="pay-list guttar-12px" onclick="myFunction()">
                    @foreach($methods as $method)
                        {{ $method }}
                    @endforeach
					<li class="pay-item"><div class="input-wrap" >
                    <input type="radio" class="pay-check" value="stripe" name="pay_option" required="required" id="pay-stripe" data-msg-required="Select your payment method." aria-required="true">
                    <label class="pay-check-label" for="pay-stripe"><span class="pay-check-text" title="You can send your payment using your stripe account.">Pay with Card</span><img class="pay-check-img" src="https://osis.world/assets/images/pay-card.png" alt="stripe"></label>
                </div></li>
                </ul>
				
                <p class="text-light font-italic mgb-1-5x"><small>* {{__('WE DO NOT COLLECT YOUR PAYMENT INFORMATION. All Payments are Securely processed by Stripe or PayPal. Payment gateways may charge you additional processing fees.')}}</small></p>
                <div class="pdb-0-5x">
                    <div class="input-item text-left" id="agree-cond">
                        <input type="checkbox" data-msg-required="{{ __("You should accept our terms and policy.") }}" class="input-checkbox input-checkbox-md" id="agree-terms" name="agree" required>
                        <label for="agree-terms">{{ __('I hereby agree to the Token Purchase Agreement and Token Sale Terms.') }}</label>
                    </div>
                </div>
				<input type="hidden" name="paytype" id="paytype" value="">
                <ul class="d-flex flex-wrap align-items-center guttar-30px">
                    <li id="other"><button type="submit" class="btn btn-alt btn-primary payment-btn" > {{ __('Buy OSIS') }} <em class="ti ti-arrow-right mgl-2x"></em></button></li>
					<li id="stipe_pay" style="display:none;" > <button type="submit" onclick="callStripe()" class="btn btn-alt btn-primary payment-btn" > {{ __('Buy OSIS') }}</a></li>
			   </ul>
                <div class="gaps-3x"></div>
                <div class="note note-plane note-light">
                    <em class="fas fa-info-circle"></em>
                    <p class="text-light">{{__('OSIS Subsidiary, The Viral Marketer, LLC, handles all PayPal payments.')}}</p>
                </div>
            @else
                <div class="gaps-4x"></div>
                <div class="alert alert-danger text-center">{{ __('Sorry! There is no payment method available for this currency. Please choose another currency or contact our support team.') }}</div>
                <div class="gaps-5x"></div>
            @endif

        </form>
		<form id="TheForm" method="post" action="{{ route('user.stripe') }}">
		@csrf
            <input type="hidden" name="pp_token" id="token_amount" value="{{ $data->token }}">
            <input type="hidden" name="pp_currency" id="pay_currency" value="{{ $data->currency }}">
			<input type="hidden" name="pp_total_token" id="total_token" value="{{$data->total_tokens}}">
			<input type="hidden" value="{{to_num($payamount, 'max')}}" name="amount"  >
		</form>
    </div>
	</div>
	
<script>
function setDataLayer(){
    dataLayer.push({
        event: 'add-payment-info',
        em: localStorage.getItem('em'),
        fn: localStorage.getItem('fn'),
        ln: localStorage.getItem('ln'),
        phone: localStorage.getItem('phone')
    })
}
function myFunction() {
	var stripe = $("input[type='radio'][name='pay_option']:checked").val();
	if(stripe == 'stripe'){
  var x = document.getElementById("stipe_pay");
  var p = document.getElementById("other");
  var a = document.getElementById("agree-cond");
  if (x.style.display === "none") {
    x.style.display = "block";
	p.style.display = "none";
	a.style.display="none";
  } else {
    x.style.display = "none";
	p.style.display = "block";
	a.style.display = "block";
  }
}else{
	var x = document.getElementById("stipe_pay");
	x.style.display = "none";
}
}
function callStripe() {
	document.getElementById("paytype").value = "stripe";
	var f = document.getElementById('TheForm');
	window.open('<?php echo asset('/user/contribute/stripe'); ?>',"_self");
	f.submit();
}
</script>
 
@if(in_array("Stripe", $activeMethods))
<script src="https://js.stripe.com/v3/"></script>
@endif
<script type="text/javascript">
    (function($) {
        var $_p_form = $('form#online_payment');
        if ($_p_form.length > 0) { purchase_form_submit($_p_form); }
    })(jQuery);
</script>
