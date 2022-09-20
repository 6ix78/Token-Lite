@extends('layouts.user')
@section('content')

<style>
  .loader {
  border: 3px solid #fff;
  border-radius: 50%;
  border-top: 3px solid #7668fe;
  width: 22px;
  height: 22px; 
  margin: 0 0 15px;
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<div class="row">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>	
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>		
				<script>
  var stripe = Stripe("{{env('STRIPE_KEY')}}");
</script>

<div id="loader_div" class="loader_div"></div>

				<form accept-charset="UTF-8" action="{{ route('user.stripe_payment')}}" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{$_ENV['STRIPE_PUBLISHABLE_KEY']}}" id="payment-form" method="post">
					{{ csrf_field() }}
					<input type="hidden" name="pp_token" id="token_amount" value="{{ $data['pp_token'] }}">
					<input type="hidden" name="pp_total_token" id="total_token" value="{{$data['pp_total_token']}}">
					<input type="hidden" name="pp_currency" id="pay_currency" value="{{ $data['pp_currency'] }}">
					
					<input type="hidden" name="pay_option" id="pay_option" value="bank">
					
					<div class='form-row'>
						<div class='col form-group required'>
							<label class='control-label'>Name on Card</label> <input
								class='form-control' size='4' type='text' name="name">
						</div>
					</div>
					<div class='form-row'>
						<div class='col form-group  required'>
							<label class='control-label'>Card Number</label> <input
								autocomplete='off'  name="card" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
     class='form-control card-number'  maxlength="16"
								type='number'>
						</div>
					</div>
					<div class='form-row'>
						<div class='col form-group cvc required'>
							<label class='control-label'>CVC</label> <input autocomplete='off'
								class='form-control card-cvc' name="cvv" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder='ex. 311' maxlength="3"
								type='number'>
						</div>
						<div class='col form-group expiration required'>
							<label class='control-label'>Expiration Month</label> <input
								class='form-control card-expiry-month'name="month" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder='MM' maxlength="2"
								type='number'>
						</div>
						<div class='col form-group expiration required'>
							<label class='control-label'>Expiration Year </label> <input
								class='form-control card-expiry-year' name="year" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder='YYYY' maxlength="4"
								type='number'>
						</div>
					</div>
					<div class='form-row'>
						<div class='col'> 
							<input type="hidden" value="{{$data['usdamount']}}" name="amount"  >
						</div>
					</div>
					<div class="pdb-0-5x">
                    <div class="input-item text-left">
                        <input type="checkbox"  class="input-checkbox input-checkbox-md"  name="agree" required id="agree-termss">
                        <label for="agree-termss">{{ __('I hereby agree to the Token Purchase Agreement and Token Sale Terms.') }}</label>
                    </div>
                </div>
					<div class='form-row'>
						<div class='col error form-group hide'>
							<div class='alert-danger alert'>Please correct the errors and try
								again.</div>
						</div>
					</div>
          <div class="loader" style="display:none" id="loader"></div>
					<ul class="d-flex flex-wrap align-items-center guttar-30px">
					 <li ><button type="submit" class="btn btn-alt btn-primary payment-btn" > Pay {{$data['usdamount']}} USD <em class="ti ti-arrow-right mgl-2x"></em></button></li>
					</ul>
				</form>
				</div>
				
		</div>		
			
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>		
				<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
	
				 <script>
				 
        $(function() {
			
              $('form.require-validation').bind('submit', function(e) {
                var $form         = $(e.target).closest('form'),
                    inputSelector = ['input[type=email]', 'input[type=password]',
                                     'input[type=text]', 'input[type=file]',
                                     'textarea'].join(', '),
                    $inputs       = $form.find('.required').find(inputSelector),
                    $errorMessage = $form.find('div.error'),
                    valid         = true;
                $errorMessage.addClass('hide');
                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                  var $input = $(el);
                  if ($input.val() === '') {
					   document.getElementById("loader").style.display="none";
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('hide');
                    e.preventDefault(); // cancel on first error
                  }
                });
              });
            });
            $(function() {
              var $form = $("#payment-form");
              $form.on('submit', function(e) {
				  document.getElementById("loader").style.display="block";
                if (!$form.data('cc-on-file')) {
                  e.preventDefault();
                  Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                  Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                  }, stripeResponseHandler);
                }
              });
              function stripeResponseHandler(status, response) {
			
                if (response.error) {
                  $('.error')

                    .removeClass('hide')
                    .find('.alert')
                    .text(response.error.message);
					 document.getElementById("loader").style.display="none";
                } else {
                  // token contains id, last4, and card type
                  var token = response['id'];
                  // insert the token into the form so it gets submitted to the server
                  $form.find('input[type=text]').empty();
                  $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                  $form.get(0).submit();
                }
              }
            })
        </script>
		@endsection	