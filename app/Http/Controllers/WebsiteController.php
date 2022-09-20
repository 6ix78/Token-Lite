<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\User;
use mail;
class WebsiteController extends Controller
{
	
	public function index(){
		return view('Website.index');
	}
	
	public function aboutus(){
		return view('Website.about-us');
	}
	
	public function becomeapartner(){
		return view('Website.become-a-partner');
	}
    public function getOsis(){
		return view('Website.get-osis');
	}
	
	
	public function becomeaptb(){
		return view('Website.become-a-ptb');
	}
	
	public function contactus(Request $request){
		if(request()->isMethod('post')) {
			$input = $request->all();
			
			if( request()->has('myfile') ) {
    		 	$file = $request->file('myfile');
                $thumbnailPath = 'contactus/';
				$filenames = $_FILES['myfile']['name'];
				$ext = pathinfo($filenames, PATHINFO_EXTENSION);
    			$fileName = 'contact-' . time() . random_int(0, 10).'.'.$ext;
    			$myfile = $file->move($thumbnailPath,$fileName);
	    		if($myfile == '') {
                    return redirect()->back()->with('invalid file provided');
                }
                $input['myfile'] = $fileName;
    		 }else{
				  $input['myfile'] = '';
			 }
			unset($input['_token']);
			
			$getadmin = User::where('role','admin')->select('email')->get();
			if(!empty($getadmin)){
				foreach($getadmin as $value){
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers.= "Content-type:text/html;charset=UTF-8" . "\r\n";
					$headers .= 'From: OSIS<contact@osisplatform.com>'. " \r\n";
					$email = $value->email;
					$subject='Contact Request Submitted from OSIS Web';
					$mailmessage='<table width="100%" cellpadding="0" cellspacing="0" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;background-color:#eaf3fc;margin:0;padding:0;width:100%">
        <tbody><tr>
            <td align="center" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;margin:0;padding:0;width:100%">
                    <tbody><tr>
    <td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;padding:20px 0 0 0;text-align:center">
        <a href="https://www.test.osis.world" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#2b56f5;font-size:45px;font-weight:bold;text-decoration:none" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://www.test.osis.world&amp;source=gmail&amp;ust=1634982479242000&amp;usg=AFQjCNFCnENwTiqJBe_Cfw8HDVBG3VeN3Q">
            <img height="50" src="https://ci6.googleusercontent.com/proxy/lhPcTAD2yi0R02FWzxMNyeJ1WNRK7AYrQyxR90CqWZCuW-d3V5UVHt4kcHnAsk1Fx36I7OeOymGFuh0JOejA5BlZLg=s0-d-e1-ft#https://www.test.osis.world/images/logo-mail.png" alt="OSIS" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;max-width:100%;border:none;max-height:75px" class="CToWUd">
        </a>
            </td>
</tr>
                    <tr>
                        <td width="100%" cellpadding="0" cellspacing="0" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;background-color:transparent;border-bottom:1px solid #edeff2;border-top:1px solid #edeff2;margin:0;padding:0;width:100%">
                            <table align="center" width="570" cellpadding="0" cellspacing="0" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;background-color:transparent;margin:0 auto;padding:0;width:620px">
                                <tbody><tr>
                                    <td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;padding:20px 10px">
                                        <table width="100%" cellpadding="0" cellspacing="0" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;text-align:center;margin:0 0 11px">
    <tbody><tr>
        <td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;background-color:#ffffff;padding:25px 20px;border-bottom:4px solid #00d285!important;text-align:center!important">
            <table width="100%" cellpadding="0" cellspacing="0" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">
                <tbody><tr>
                    <td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;padding:0">
                        <h1 style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#2f3133;font-size:19px;font-weight:bold;margin-top:0;text-align:left"> You have got a new contact request!</h1>
<div style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;text-align:left!important">
        You have requested for query.<br><table style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;width:100%;margin:20px 0px 30px;border-bottom:1px solid rgba(0,0,0,0.15)">
<thead style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box"><tr><th colspan="3" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;padding-bottom:8px;margin:0;text-align:left;padding:0px 15px 7px 0px;border-bottom:1px solid rgba(0,0,0,0.15)">Contact details are follows:</th></tr></thead>
<tbody style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;text-align:left!important">
<tr>
<td width="150" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">First Name</td>
<td width="15" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">:</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px"><strong style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">'.$input["fname"].'</strong></td>
</tr>
<tr>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">Last Name</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">:</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px"><strong style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">'.$input["lname"].'</strong></td>
</tr>
<tr>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">Email ID</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">:</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px"><strong style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">'.$input["email"].'</strong></td>
</tr>
<tr>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">Phone Number </td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">:</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">
<strong style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">'.$input["phone"].'</strong> </td>
</tr>
<tr>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">Message</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">:</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">
<strong style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">'.$input["message"].'</strong> </td>
</tr>
<tr>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">File</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">:</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px"><strong style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box"><a href="https://osis.world/contactus/'.$input["myfile"].'" download>'.$input["myfile"].'</a></strong></td>
</tr>
</tbody>
</table>
<br>Feel free to contact us if you have any questions.<br>
    </div>
<hr style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">
<p style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;font-size:16px;line-height:1.5em;margin-top:0;text-align:left;margin-bottom:0;padding-bottom:0">Best Regards<br>OSIS Team</p>
                    </td>
                </tr>
            </tbody></table>
        </td>
    </tr>
</tbody></table>
                                        
                                    </td>
                                </tr>
                            </tbody></table>
                        </td>
                    </tr>
                    <tr>
    <td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">
        <table align="center" width="620" cellpadding="0" cellspacing="0" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;margin:0 auto;padding:0;text-align:center;width:620px">
            <tbody><tr>
                <td align="center" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;padding:20px 10px">
                    <p style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;line-height:1.5em;margin-top:0;color:#526283;font-size:12px;text-align:center"> Copyright © 2022 OSIS. All Rights Reserved.</p>
                                                                <ul style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;padding:0;text-align:center">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </ul>
                                    </td>
            </tr>
        </tbody></table>
    </td>
</tr>
                </tbody></table>
            </td>
        </tr>
    </tbody></table>';
					
					mail($email,$subject,$mailmessage,$headers,"-f contact@osisplatform.com");
				}
			}
			$id = Contact::insertGetId($input);
			$data = Contact::where('id',$id)->first();
			
			return redirect()->back()->with('success','Details Sent Successfully.');
		}
		return view('Website.contact-us');
	}
	
	
	
	// ambassador contact /////
	
	
	
	
	
	
	
	
	
		public function partners(Request $request){
		if(request()->isMethod('post')) {
			$input = $request->all();
			
			if( request()->has('myfile') ) {
    		 	$file = $request->file('myfile');
                $thumbnailPath = 'contactus/';
				$filenames = $_FILES['myfile']['name'];
				$ext = pathinfo($filenames, PATHINFO_EXTENSION);
    			$fileName = 'contact-' . time() . random_int(0, 10).'.'.$ext;
    			$myfile = $file->move($thumbnailPath,$fileName);
	    		if($myfile == '') {
                    return redirect()->back()->with('invalid file provided');
                }
                $input['myfile'] = $fileName;
    		 }else{
				  $input['myfile'] = '';
			 }
			unset($input['_token']);
			
			$getadmin = User::where('role','admin')->select('email')->get();
			if(!empty($getadmin)){
				foreach($getadmin as $value){
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers.= "Content-type:text/html;charset=UTF-8" . "\r\n";
					$headers .= 'From: OSIS<contact@osisplatform.com>'. " \r\n";
					$email = $value->email;
					$subject='Contact Request for Archangel Program - OSIS Web';
					$mailmessage='<table width="100%" cellpadding="0" cellspacing="0" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;background-color:#eaf3fc;margin:0;padding:0;width:100%">
        <tbody><tr>
            <td align="center" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;margin:0;padding:0;width:100%">
                    <tbody><tr>
    <td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;padding:20px 0 0 0;text-align:center">
        <a href="https://www.test.osis.world" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#2b56f5;font-size:45px;font-weight:bold;text-decoration:none" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://www.test.osis.world&amp;source=gmail&amp;ust=1634982479242000&amp;usg=AFQjCNFCnENwTiqJBe_Cfw8HDVBG3VeN3Q">
            <img height="50" src="https://ci6.googleusercontent.com/proxy/lhPcTAD2yi0R02FWzxMNyeJ1WNRK7AYrQyxR90CqWZCuW-d3V5UVHt4kcHnAsk1Fx36I7OeOymGFuh0JOejA5BlZLg=s0-d-e1-ft#https://www.test.osis.world/images/logo-mail.png" alt="OSIS" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;max-width:100%;border:none;max-height:75px" class="CToWUd">
        </a>
            </td>
</tr>
                    <tr>
                        <td width="100%" cellpadding="0" cellspacing="0" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;background-color:transparent;border-bottom:1px solid #edeff2;border-top:1px solid #edeff2;margin:0;padding:0;width:100%">
                            <table align="center" width="570" cellpadding="0" cellspacing="0" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;background-color:transparent;margin:0 auto;padding:0;width:620px">
                                <tbody><tr>
                                    <td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;padding:20px 10px">
                                        <table width="100%" cellpadding="0" cellspacing="0" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;text-align:center;margin:0 0 11px">
    <tbody><tr>
        <td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;background-color:#ffffff;padding:25px 20px;border-bottom:4px solid #00d285!important;text-align:center!important">
            <table width="100%" cellpadding="0" cellspacing="0" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">
                <tbody><tr>
                    <td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;padding:0">
                        <h1 style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#2f3133;font-size:19px;font-weight:bold;margin-top:0;text-align:left"> You have a new ArchAngel Program Request!</h1>
<div style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;text-align:left!important">
        You have requested for query.<br><table style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;width:100%;margin:20px 0px 30px;border-bottom:1px solid rgba(0,0,0,0.15)">
<thead style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box"><tr><th colspan="3" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;padding-bottom:8px;margin:0;text-align:left;padding:0px 15px 7px 0px;border-bottom:1px solid rgba(0,0,0,0.15)">Contact details are follows:</th></tr></thead>
<tbody style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;text-align:left!important">
<tr>
<td width="150" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">First Name</td>
<td width="15" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">:</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px"><strong style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">'.$input["fname"].'</strong></td>
</tr>
<tr>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">Social Media Handle</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">:</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px"><strong style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">'.$input["lname"].'</strong></td>
</tr>
<tr>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">Email ID</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">:</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px"><strong style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">'.$input["email"].'</strong></td>
</tr>
<tr>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">Phone Number </td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">:</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">
<strong style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">'.$input["phone"].'</strong> </td>
</tr>

<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">Investment Amount </td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">:</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">
<strong style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">'.$input["budget"].'</strong> </td>
</tr>


<tr>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">Message</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">:</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">
<strong style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">'.$input["message"].'</strong> </td>
</tr>
<tr>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">File</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px">:</td>
<td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;margin:0;font-size:14px;line-height:20px;padding:5px 15px 5px 0px"><strong style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box"><a href="https://osis.world/contactus/'.$input["myfile"].'" download>'.$input["myfile"].'</a></strong></td>
</tr>
</tbody>
</table>
<br>Feel free to contact us if you have any questions.<br>
    </div>
<hr style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">
<p style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#74787e;font-size:16px;line-height:1.5em;margin-top:0;text-align:left;margin-bottom:0;padding-bottom:0">Best Regards<br>OSIS Team</p>
                    </td>
                </tr>
            </tbody></table>
        </td>
    </tr>
</tbody></table>
                                        
                                    </td>
                                </tr>
                            </tbody></table>
                        </td>
                    </tr>
                    <tr>
    <td style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box">
        <table align="center" width="620" cellpadding="0" cellspacing="0" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;margin:0 auto;padding:0;text-align:center;width:620px">
            <tbody><tr>
                <td align="center" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;padding:20px 10px">
                    <p style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;line-height:1.5em;margin-top:0;color:#526283;font-size:12px;text-align:center"> Copyright © 2022 OSIS. All Rights Reserved.</p>
                                                                <ul style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;padding:0;text-align:center">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </ul>
                                    </td>
            </tr>
        </tbody></table>
    </td>
</tr>
                </tbody></table>
            </td>
        </tr>
    </tbody></table>';
					
		mail($email,$subject,$mailmessage,$headers,"-f contact@osisplatform.com");
				}
			}
			$id = Contact::insertGetId($input);
			$data = Contact::where('id',$id)->first();
			
			return redirect()->back()->with('success','We received your contact request, please allow 3-5 business days for our team to reach out. If you are inquiring about the ArchAngel program, note that we plan to launch the initiative in Spring of 2022, so hold tight!');
		}
		return view('Website.partners');
	}
	
	
	
	
	
	
	
	
	
	public function futureinvesting(){
		return view('Website.future-investing');
	}
}

?>