<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="js">
<head>
    <meta charset="utf-8">
    <meta name="apps" content="{{ app_info() }}">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Internal Error</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor.bundle.css').css_js_ver() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css').css_js_ver() }}">
</head>
@php 
$bg_img = "";
@endphp

<body class="page-error error-500 theme-modern"{!! $bg_img !!}>

    <div class="vh100 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-6 text-center">
                    <div class="error-content">
                        <span class="error-text-large">500</span>
                        <h4 class="text-dark">There seems to be an issue with your payment method.</h4>
                        <p>Your card was not charged. OSIS processes payments using Stripe & PayPal, converting orders to USD. Sometimes your bank will decline the transaction until you manually approve it.  
                        
                        Please check with your bank, approve the transaction and try it again. We've resolved this issue with dozens of investors. Thank you for your attention.</p>
                        <p>Alternatively, please contact us{!! (site_info('email')) ? ' at <a href="mailto:'.site_info('email').'">'.site_info('email').'</a>' : '' !!}, for any help.</p>
                        <a href="{{ url('/') }}" class="btn btn-primary">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery.bundle.js').css_js_ver() }}"></script>
    <script src="{{ asset('assets/js/script.js').css_js_ver() }}"></script>
</body>
</html>