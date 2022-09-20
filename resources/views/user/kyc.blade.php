@extends('layouts.user')
@section('title', __('KYC Verification'))
@php
$has_sidebar = false;

$kyc_title = ($user_kyc !== NULL && isset($_GET['thank_you'])) ? __('Begin your ID-Verification') : __('ID Verification');
$kyc_desc = ($user_kyc !== NULL && isset($_GET['thank_you'])) ? __('Verify your identity to participate in OSIS sale.') : __('You may purchase OSIS & get your MetaBadge before verifying ID. Verification will be required in order to participate in OSIS public launch this summer.');
@endphp

@section('content')
<div class="page-header page-header-kyc">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7 text-center">
            <h2 class="page-title">{{ $kyc_title }}</h2>
            <p class="large">{{ $kyc_desc }}</p>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        <div class="content-area card user-account-pages page-kyc">
            <div class="card-innr">
                @include('layouts.messages')
                <div class="kyc-status card mx-lg-4">
                    <div class="card-innr">
                        {{-- IF NOT SUBMITED --}}
                        @if($user_kyc == NULL)
                        <div class="status status-empty">
                            <div class="status-icon">
                                <em class="ti ti-files"></em>
                            </div>
                            <span class="status-text text-dark">{{__('You have not submitted the required documents to verify your identity.')}}{{ (token('before_kyc')=='1') ? __('In order to purchase OSIS, please verify your identity.') : ''}}</span>
                            <p class="px-md-5">{{__('If you have any questions, feel free to contact our support team.')}}</p>
                            <a href="{{ route('user.kyc.application') }}?state=new" class="btn btn-primary">{{__('Start ID Verification')}}</a>
                        </div> 
                        @endif
                        {{-- IF SUBMITED @Thanks --}}
                        @if($user_kyc !== NULL && isset($_GET['thank_you']))
                        <div class="status status-thank px-md-5">
                            <div class="status-icon">
                                <em class="ti ti-check"></em>
                            </div>
                            <span class="status-text large text-dark">{{__('You have completed ID Verification.')}}</span>
                            <p class="px-md-5">{{__('Once our team reviews your documents, you will be notified by email. You can also check your verification status from your profile.')}}</p>
                            <a href="{{ route('user.account') }}" class="btn btn-primary">{{__('Back to Profile')}}</a>
                        </div>
                        @endif

                        {{-- IF PENDING --}}
                        @if($user_kyc !== NULL && $user_kyc->status == 'pending' && !isset($_GET['thank_you']))
                        <div class="status status-process">
                            <div class="status-icon">
                                <em class="ti ti-infinite"></em>
                            </div>
                            <span class="status-text text-dark">{{__('Your application verification under process.')}}</span>
                            <p class="px-md-5">{{__('We are still working on your identity verification. Once our team verified your identity, you will be notified by email.')}}</p>
                        </div>
                        @endif

                        {{-- IF REJECTED/MISSING --}}
                        @if($user_kyc !== NULL && ($user_kyc->status == 'missing' || $user_kyc->status == 'rejected') && !isset($_GET['thank_you']))
                        <div class="status status{{ ($user_kyc->status == 'missing') ? '-warnning' : '-canceled' }}">
                            <div class="status-icon">
                                <em class="ti ti-na"></em>
                            </div>
                            <span class="status-text text-dark">
                                {{ $user_kyc->status == 'missing' ? __('We found some information to be missing.') : __('Sorry! Your application was rejected.') }}
                            </span>
                            <p class="px-md-5">{{__('We found information was is incorrect or missing. Please resubmit the form.')}}</p>
                            <a href="{{ route('user.kyc.application') }}?state={{ $user_kyc->status == 'missing' ? 'missing' : 'resubmit' }}" class="btn btn-primary">{{__('Submit Again')}}</a>
                        </div>
                        @endif

                        {{-- IF VERIFIED --}}
                        @if($user_kyc !== NULL && $user_kyc->status == 'approved' && !isset($_GET['thank_you']))
                        <div class="status status-verified">
                            <div class="status-icon">
                                <em class="ti ti-files"></em>
                            </div>
                            <span class="status-text text-dark">{{__('Your identity verified successfully.')}}</span>
                            <p class="px-md-5">{{__('Our team has verified your identity. Now you can participate in our token sale. Thank you.')}}</p>
                            <div class="gaps-2x"></div>
                            <a href="{{ route('user.token') }}" class="btn btn-primary">{{__('Purchase Token')}}</a>
                        </div>
                        @endif

                    </div>
                </div>{{-- .card --}}
            </div>
        </div>
        {!! UserPanel::kyc_footer_info() !!}
    </div>
</div>
@endsection
