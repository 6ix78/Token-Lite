<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Notifications\UserRegistered;
use Auth;
use Carbon\Carbon;
use App\Notifications\ConfirmEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VerifyController extends Controller
{
    protected $redirectTo = '/verify/success';

    public function __construct()
    {
        $this->middleware('auth')->except(['verify']);
    }

    public function index(Request $request)
    {

        // return view('auth.verify');;
        return $request->user()->hasVerifiedEmail()
            ? redirect($this->redirectTo)
            : (view('auth.verify'));
    }

    public function resend(Request $request)
    {
        $cd = Carbon::now();
        $user = Auth::user();
        $chk = $cd->copy()->addMinutes(10);

        $user->meta->email_token = str_random(65);
        $user->meta->email_expire = $cd->copy()->addMinutes(75);
        $user->meta->save();

        try {
            $user->notify(new ConfirmEmail($user));

            return back()->with('resent', true);
        } catch (\Exception $e) {
            return back()->withErrors(__('messages.email.verify',['email'=>get_setting('site_email')]));
        }
    }

    public function verify(Request $request, $id='', $token='')
    {
        if ($id != null || Auth::id() != null) {
            $id = $id != null ? $id : Auth::id();
            $user = User::find($id);

            if ($user) {
                if ($user->email_verified_at != null) {
                    return redirect()->route('login');//->with('info', __('messages.verify.verified'));
                }
                if ($user->meta->email_token == $token) {
                    if (_date($user->meta->email_expire, 'Y-m-d H:i:s') >= date('Y-m-d H:i:s')) {
						  $data = '{
							"token" : "nn$&gBY2H9fZG9Pn=gqMsE"
						}';
						$make_call = $this->callAPI('POST', env('REST_URL').'/genwallet', $data);
						$response = json_decode($make_call, true);
						print_r($response);
						if(!empty($response)){
							if($response['message'] == 'success'){
								$user->walletAddress = $response['publickey'];
								$user->private_key = base64_encode($response['privatekey']);
							}
						}
						//echo "<pre>";	print_r(base64_decode($user->private_key)); die;
                        $user->email_verified_at = now();
                        $user->meta->email_token = null;
                        $user->meta->email_expire = null;
                        $user->save();
                        $user->meta->save();
                        $user->notify(new UserRegistered($user));
                        Auth::logout();
                        return redirect($this->redirectTo);
                    } else {
                        if (!Auth::guest()) {
                            Auth::logout();
                        }
                        return redirect()->route('login')->with('info', __('messages.verify.expired'));
                    }
                } else {
                    if (!Auth::guest()) {
                        Auth::logout();
                    }
                    return redirect()->route('login')->with('error', __('messages.verify.invalid'));
                }
            } else {
                if (!Auth::guest()) {
                    Auth::logout();
                }
                return redirect()->route('login')->with('warning', __('messages.verify.not_found'));
            }
        } else {
            return redirect()->route('login')->with('warning', __('messages.verify.not_found'));
        }
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
}
