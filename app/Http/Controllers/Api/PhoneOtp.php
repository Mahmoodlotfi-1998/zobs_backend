<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PhoneOtp extends Controller
{
    public function Manage_Request(Request $request)
    {
        switch ($request->post('action')){
            case 'send_otp':
                include ("../../../Classes/RemotePost.php");
                $otp = rand(10000, 99999);
                $message ="به زوبس خوش آمدید.
کد تایید شما : ";
                $remotePost=new RemotePost("Advertising1389","Adver_isfahan");
                $message =$message.$otp;
                $mobile_number=$request->post('phone');

                $comments=PhoneOtp::where('phone',$mobile_number)->get();
                echo $comments;

                break;

            case 'verify_otp':

                break;
        }
    }
}
