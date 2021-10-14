<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\PhoneOtp;
use Illuminate\Http\Request;
use function Composer\Autoload\includeFile;
use App\Classes\RemotePost;
use App\Functions\Algoritms;
use App\LM_otp;
use App\LM_users;



class PhoneOtpController extends Controller
{
    private $algoritms;
    function __construct(){
        $this->algoritms=new Algoritms();
    }

    public function Manage_Request(Request $request)
    {
//        $algoritms=new Algoritms();
        if($this->algoritms->discreate_mikhay($request->post('mikhay'))){
            $mobile_number=$request->post('phone');
            switch ($request->post('action')){
                case 'send_otp':
                    $otp = rand(1000, 9999);
                    $message ="به زوبس خوش آمدید.
کد تایید شما : ";
                    $remotePost=new RemotePost("Advertising1389","Adver_isfahan");
                    $message =$message.$otp;
                    $otp_row=LM_otp::where('phone',$mobile_number);

                    try {

                        if($otp_row->count() ==1){
                            $otp_row=$otp_row->first();
                            $otp_row->otp=$otp;
                            if($otp_row->save()){
                                $remotePost->SendCustomMessage($mobile_number,$message);
                                $response['status'] = 'ok';
                                $response['send_type'] = 'login';
                                return json_encode($response);
                            }else{
                                $response['status'] = 'nook';
                                return json_encode($response);
                            }
                        }else{
                            if (LM_otp::insert(['phone'=>$mobile_number,'otp'=>$otp])){
                                $remotePost->SendCustomMessage($mobile_number,$message);
                                $response['status'] = 'ok';
                                $response['send_type'] = 'reg';
                                return json_encode($response);
                            }else{
                                $response['status'] = 'nook';
                                return json_encode($response);
                            }
                        }
                    }catch(Exception $e){
                        die('Error: '.$e->getMessage());
                    }

                    break;

                case 'verify_otp':
                    $otp=$request->post('otp');

                    $otp_row=LM_otp::where('phone',$mobile_number)->where('otp',$otp);

                    if($otp_row->count() ==1){
                        $user_row=LM_users::select('id','first_name','last_name','subscription','fix_phone','address')->where('phone',$mobile_number);
                        if($user_row->count() ==1){
                            $feach_row=$user_row->first();
                            $feach_row['user_id']=strval($this->algoritms->create_id($feach_row['id']));
                            unset($feach_row['id']);
                            $list=['status'=>'ok','user'=>$feach_row];
                            return json_encode($list);
                        }else{
                            $list=['status'=>'ok','user'=>['user_id'=>0]];
                            return json_encode($list);
                        }

                    }else{
                        return json_encode(array("status"=>"nook"));

                    }


                    break;
            }
        }

    }
}
