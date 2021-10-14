<?php

namespace App\Http\Controllers;

use App\Functions\Algoritms;
use App\Functions\jdf;
use App\Functions\Settings;
use App\LM_Services;
use App\LM_users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    private $algoritms;
    private $jdf;
    private $settings;
    private $zarin_merchent='aeda6178-f593-4d1a-9cdf-52287c0c37e2';
    private $schema='dordorpay';
    private $package='com.adveriran.zobs';

    function __construct(){
        $this->algoritms=new Algoritms();
        $this->jdf=new jdf();
        $this->settings=new Settings();
    }

    public function CheckParameters(){

        if(isset($_GET['s_id'])){
            $subscription = DB::table('l_m_subscription')
                ->where('id','=',intval($_GET['s_id']));
//            print_r( $subscription);
            if ($subscription->count()>0){
                $subscriptio=$subscription->first();
                return $subscriptio;
            }
        }
        return 0;
    }

    public function CheckParametersServices(){

        if(isset($_GET['s_id'])){
            $services=LM_Services::where('id',$this->algoritms->discreate_id($_GET['s_id']));
            $services=$this->algoritms->check_exist_row($services);
            return $services;

        }
        return 0;
    }

    public function CheckUser(){
        if(isset($_GET['ur_id'])){
            $user=LM_users::where('id',$this->algoritms->discreate_id($_GET['ur_id']));
            $user=$this->algoritms->check_exist_row($user);
            return $user;
        }
    }

    public function CheckTr(){
        if(isset($_GET['ur_id'])){
            $user=DB::table('l_m_payments')->where('id',$this->algoritms->discreate_id($_GET['ur_id']));
            $user=$this->algoritms->check_exist_row($user);
            return $user;
        }
    }

    public function test_jdf(){
        $time=$this->jdf->jdate('Y/m/d');
        $query=DB::select('select DATEDIFF(`s_end`,"'.$time.'") AS DateDiff from l_m_users where id='.'8');

        print_r($query[0]->DateDiff);

    }


    public function Payment(){
        ob_start();
        session_start();
        $res='';
        $back_app='<div class="contain_pay">
                        <div class="header-pay"><h1 class="matn">اپلیکیشن زوبس</h1></div>
                        <div class="confim"><h3 class="confim">مشکلی پیش آمد دوباره از اپلیکیشن وارد شوید.</h3></div>';

        $web_back_url='<a class="pay_back" href="https://zobs.ir/webapp">بازگشت به اپ</a>';
        $app_back_url='<a class="pay_back" href="intent://0#Intent;scheme='.$this->schema.';package='.$this->package.';end">بازگشت به اپ</a>';

        if ($_GET['source'] == 1){
            $back_app.=$web_back_url;
            $web=1;
        }else{
            $back_app.=$app_back_url;
            $web=0;
        }
        $back_app.='</div>';

        if (isset($_GET['type'])){
            switch ($_GET['type']){
                case 'subscription':
                    //subscription
                    //test url:
                    //https://zobs.ir/zoobs/api/payment?type=subscription&action=begin&ur_id=b9&s_id=1

                    switch ($_GET['action']){
                        case 'begin':
                            $check=$this->CheckParameters();
                            if ($check !='0'){
                                $subscription=$check;
                            }else{
                                $res=$back_app;
                                break;
                            }

                            $res='<div class="contain_pay">
                                                <div class="header-pay"><h1 class="matn">اپلیکیشن زوبس</h1></div>
                                                <div class="mony"><h1 style="font-size: 21px;">پرداخت ' . $subscription->price . 'تومان </h1></div>
                                                <div class="confim"><h3 class="confim">نسبت به پرداخت آن اطمینان دارید؟</h3></div>


                                                <a class="pay_btn" href="?source=' . $_GET['source'] .'&type=subscription&action=pay&ur_id=' . $_GET['ur_id'] .'&s_id='.$_GET['s_id'].'"><p style="margin-top: 5px;">پرداخت میکنم</p></a>';

                            if ($web){
                                $res.=$web_back_url;
                            }else{
                                $res.=$app_back_url;
                            }
                            $res.='</div>';

                            break;

                        case 'pay':
                            $check=$this->CheckParameters();
                            if ($check !='0'){
                                $subscription=$check;
                            }else{
                                $res=$back_app;
                                break;
                            }
                            $user=$this->CheckUser();

                            $payment=DB::table('l_m_payments')->insertGetId([
                                'user_id'=>$user->id,
                                'order_id'=>$subscription->id,
                                'type'=>'subscription',
                                'mount'=>$subscription->price,
                                'desc'=>' پرداختی '. $subscription->name,
                                'status'=>'pay',
                                'time'=>$this->jdf->jdate('H:i:s'),
                                'date'=>$this->jdf->jdate('Y/m/d')
                            ]);
//                            echo 'hi';

                            if ($payment){
//                                echo 'hi';

                                $client = new \SoapClient('https://zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

                                $result = $client->PaymentRequest([
                                    'MerchantID' => $this->zarin_merchent,
                                    'Amount' => $subscription->price,
                                    'Description' => 'پرداخت اشتراک',
                                    'CallbackURL' => $this->settings->get_base_url().'api/payment?source='.$web.'&type=subscription&action=end&ur_id='.strval($this->algoritms->create_id($payment))
                                ]);
//                                echo 'hi';
//echo $result->Status;
                                echo json_encode($result);
                                if ($result->Status == 100) {
                                    echo '<div class="row"><div class="col m4 s12"></div><div class="col m4 s12 center-align"><br /><div dir="ltr" class="preloader-wrapper active"><div class="spinner-layer spinner-blue-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div><br /><br />در حال انتقال به درگاه...</div><div class="col m4 s12"></div></div>';

                                    return redirect('https://zarinpal.com/pg/StartPay/'.$result->Authority);

                                }else{
                                    $res='<div class="center-align">
                                                <h5 class="m orange-text">خطا در پرداخت</h5>
                                                <p class="grey-text tt">تراکنش ناموفق : '.pay_response($result->Status).' ('.$result->Status.')</p>';
                                    if ($web){
                                        $res.=$web_back_url;
                                    }else{
                                        $res.=$app_back_url;
                                    }
                                    $res.='</div>';
                                }

                            }else{
                                $res=$back_app;
                            }

                            break;

                        case 'end':

                            $Authority=$_GET['Authority'];
                            $tr=$this->CheckTr();
                            $moment=$tr->mount;
//                            var_dump($tr);
                            if ($_GET['Status'] == 'OK') {

                                $client = new \SoapClient('https://zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);
                                $result = $client->PaymentVerification(
                                    [
                                        'MerchantID' => $this->zarin_merchent,
                                        'Authority' => $Authority,
                                        'Amount' => strval($moment),
                                    ]
                                );
//                                echo json_encode($result);
                                if ($result->Status == 100) {

                                    $update=DB::table('l_m_payments')
                                        ->where('id', $tr->id)->update([
                                            'status' => "end",
                                            'bank_response' => print_r($result,true),
                                            'RefId' => $result->RefID,
                                            'bank_detail' => $Authority,
                                            'date' => $this->jdf->jdate('Y/m/d'),
                                            'time' => $this->jdf->jdate('H:i:s')

                                        ]);

                                    if($update){
                                        //get day of subscription
                                        $subscription=DB::table('l_m_subscription')->where('id','=',$tr->order_id)->first();

                                        $timestamp='';
                                        $ts=0+(($timestamp==='')?time():tr_num($timestamp));
                                        $date=explode('_',date('H_i_j_n_O_P_s_w_Y',$ts));
                                        $today_list=$this->jdf->gregorian_to_jalalii($date[8],$date[3],$date[2]);

                                        $today_list[0]=str_pad($today_list[0], 2, '0', STR_PAD_LEFT);
                                        $today_list[1]=str_pad($today_list[1], 2, '0', STR_PAD_LEFT);
                                        $today_list[2]=str_pad($today_list[2], 2, '0', STR_PAD_LEFT);

                                        $today=implode('/',$today_list);

                                        $tomorrowMidnight = mktime(0, 0, 0, date('n'), date('j') + $subscription->long);

                                        $date=explode('_',date('H_i_j_n_O_P_s_w_Y',$tomorrowMidnight));
                                        $tomorrow_list=$this->jdf->gregorian_to_jalalii($date[8],$date[3],$date[2]);


                                        $tomorrow_list[0]=str_pad($tomorrow_list[0], 2, '0', STR_PAD_LEFT);
                                        $tomorrow_list[1]=str_pad($tomorrow_list[1], 2, '0', STR_PAD_LEFT);
                                        $tomorrow_list[2]=str_pad($tomorrow_list[2], 2, '0', STR_PAD_LEFT);

                                        $tomorrow=implode('/',$tomorrow_list);


                                        $update=DB::table('l_m_users')
                                            ->where('id', $tr->user_id)->update([
                                                's_start' => $today,
                                                's_end' => $tomorrow,
                                                'subscription'=>$tr->order_id
                                            ]);

                                        $res='<div class="center-align"><img src="https://img.icons8.com/color/96/000000/ok--v1.png" class="center-block" />
                                                <h5 class="m green-text">پرداخت موفق</h5>
                                                <p class="grey-text tt">پرداخت با موفقیت انجام شد ، کد رهگیری : <strong class="green-text ss">'.$result->RefID.'</strong></p>

                                                <a class="pay_back" href="intent://0#Intent;scheme='.$this->schema.';package='.$this->package.';end">بازگشت به اپ</a>
                                                                </div>';
                                    }else{
                                        $res='<div class="contain_pay"><img src="https://img.icons8.com/color/100/000000/error.png" class="center-block" />
                                            <h5 class="orange-text">خطا سیستمی در پرداخت</h5>
                                            <p class="grey-text tt">تراکنش ناموفق : '.$result->Status.' ('.$result->Status.')</p>';
                                        if ($web){
                                            $res.=$web_back_url;
                                        }else{
                                            $res.=$app_back_url;
                                        }
                                        $res.='</div>';
                                    }



                                }else{

                                    $res='<div class="contain_pay"><img src="https://img.icons8.com/color/100/000000/error.png" class="center-block" />
                                            <h5 class="orange-text">خطا در پرداخت</h5>
                                            <p class="grey-text tt">تراکنش ناموفق : '.$result->Status.' ('.$result->Status.')</p>';
                                    if ($web){
                                        $res.=$web_back_url;
                                    }else{
                                        $res.=$app_back_url;
                                    }
                                    $res.='</div>';

                                }

                            }else{
                                $res='<div class="center-align">
                                            <h5 class="m orange-text">خطا در پرداخت</h5>';
                                if ($web){
                                    $res.=$web_back_url;
                                }else{
                                    $res.=$app_back_url;
                                }
                                $res.='</div>';
                            }



                            break;


                    }
                    break;

                case 'service':
                    //service
                    //test url:
                    //https://zobs.ir/zoobs/api/payment?type=service&action=begin&ur_id=b9&s_id=b9


                switch ($_GET['action']){
                        case 'begin':
                            $check=$this->CheckParametersServices();
                            if ($check !='0'){
                                $subscription=$check;
                            }else{
                                $res=$back_app;
                                break;
                            }
                            $row=$subscription;
                            $price_off=$row->price_off;
                            $price_traffic=$row->price_traffic;
                            $price_part=$row->price_part;
                            $total_price = $row->price + $price_part + $price_traffic;
                            if ($row->price_off == 0){
                                $price_off_dec=0;
                            }else{
                                $price_off_dec=$total_price * ($row->price_off/100);

                            }
                            $user_price_off= $total_price * ($this->settings->get_users_price_off()/100);
                            $last_price = $total_price - $user_price_off - $price_off_dec;
                            $res='<div class="contain_pay">
                                                <div class="header-pay"><h1 class="matn">اپلیکیشن زوبس</h1></div>
                                                <div class="mony"><h1 style="font-size: 21px;">پرداخت ' . $last_price . 'تومان </h1></div>
                                                <div class="confim"><h3 class="confim">نسبت به پرداخت آن اطمینان دارید؟</h3></div>


                                                <a class="pay_btn" href="?source=' . $_GET['source'] .'&type=service&action=pay&ur_id=' . $_GET['ur_id'] .'&s_id='.$_GET['s_id'].'"><p style="margin-top: 5px;">پرداخت میکنم</p></a>';
                            if ($web){
                                $res.=$web_back_url;
                            }else{
                                $res.=$app_back_url;
                            }
                            $res.='</div>';
                            break;

                        case 'pay':
                            $check=$this->CheckParametersServices();
                            if ($check !='0'){
                                $subscription=$check;
                            }else{
                                $res=$back_app;
                                break;
                            }
                            $row=$subscription;
                            $price_off=$row->price_off;
                            $price_traffic=$row->price_traffic;
                            $price_part=$row->price_part;
                            $total_price = $row->price + $price_part + $price_traffic;
                            if ($row->price_off == 0){
                                $price_off_dec=0;
                            }else{
                                $price_off_dec=$total_price * ($row->price_off/100);

                            }
                            $user_price_off= $total_price * ($this->settings->get_users_price_off()/100);
                            $last_price = $total_price - $user_price_off ;

                            $user=$this->CheckUser();

                            $category=DB::table('l_m_categorys')->where('id','=',$subscription->cat_id)->first();

                            $payment=DB::table('l_m_payments')->insertGetId([
                                'user_id'=>$user->id,
                                'order_id'=>$subscription->id,
                                'type'=>'services',
                                'mount'=>$last_price,
                                'desc'=>' پرداختی سرویس '. $category->title,
                                'status'=>'pay',
                                'time'=>$this->jdf->jdate('H:i:s'),
                                'date'=>$this->jdf->jdate('Y/m/d')
                            ]);

                            if ($payment){
                                $client = new \SoapClient('https://zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

                                $result = $client->PaymentRequest([
                                    'MerchantID' => $this->zarin_merchent,
                                    'Amount' => $last_price,
                                    'Description' => 'پرداخت سرویس',
                                    'CallbackURL' => $this->settings->get_base_url().'api/payment?source='.$web.'&type=service&action=end&&ur_id='.strval($this->algoritms->create_id($payment))
                                ]);

                                if ($result->Status == 100) {

                                    return redirect('https://zarinpal.com/pg/StartPay/'.$result->Authority);

                                }else{
                                    $res='<div class="center-align">
                                                <h5 class="m orange-text">خطا در پرداخت</h5>
                                                <p class="grey-text tt">تراکنش ناموفق : '.pay_response($result->Status).' ('.$result->Status.')</p>';
                                    if ($web){
                                        $res.=$web_back_url;
                                    }else{
                                        $res.=$app_back_url;
                                    }
                                    $res.='</div>';
                                }

                            }else{
                                $res=$back_app;
                            }

                            break;

                        case 'end':

                            $Authority=$_GET['Authority'];
                            $tr=$this->CheckTr();
                            $moment=$tr->mount;
//                            var_dump($tr);
                            if ($_GET['Status'] == 'OK') {
                                $client = new \SoapClient('https://zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);
                                $result = $client->PaymentVerification(
                                    [
                                        'MerchantID' => $this->zarin_merchent,
                                        'Authority' => $Authority,
                                        'Amount' => strval($moment),
                                    ]
                                );
//                                echo json_encode($result);
                                if ($result->Status == 100) {

                                    $update=DB::table('l_m_payments')
                                        ->where('id', $tr->id)->update([
                                            'status' => "end",
                                            'bank_response' => print_r($result,true),
                                            'RefId' => $result->RefID,
                                            'bank_detail' => $Authority,
                                            'date' => $this->jdf->jdate('Y/m/d'),
                                            'time' => $this->jdf->jdate('H:i:s')

                                        ]);

                                    if($update){


                                        $update=DB::table('l_m__services')
                                            ->where('id', $tr->order_id)->update([
                                                'status'=>2
                                            ]);

                                        $res='<div class="center-align"><img src="https://img.icons8.com/color/96/000000/ok--v1.png" class="center-block" />
                                                <h5 class="m green-text">پرداخت موفق</h5>
                                                <p class="grey-text tt">پرداخت با موفقیت انجام شد ، کد رهگیری : <strong class="green-text ss">'.$result->RefID.'</strong></p>';
                                        if ($web){
                                            $res.=$web_back_url;
                                        }else{
                                            $res.=$app_back_url;
                                        }
                                        $res.='</div>';
                                    }else{
                                        $res='<div class="contain_pay"><img src="https://img.icons8.com/color/100/000000/error.png" class="center-block" />
                                            <h5 class="orange-text">خطا سیستمی در پرداخت</h5>
                                            <p class="grey-text tt">تراکنش ناموفق : '.$result->Status.' ('.$result->Status.')</p>';
                                        if ($web){
                                            $res.=$web_back_url;
                                        }else{
                                            $res.=$app_back_url;
                                        }
                                        $res.='</div>';
                                    }



                                }else{

                                    $res='<div class="contain_pay"><img src="https://img.icons8.com/color/100/000000/error.png" class="center-block" />
                                            <h5 class="orange-text">خطا در پرداخت</h5>
                                            <p class="grey-text tt">تراکنش ناموفق : '.$result->Status.' ('.$result->Status.')</p>';
                                    if ($web){
                                        $res.=$web_back_url;
                                    }else{
                                        $res.=$app_back_url;
                                    }
                                    $res.='</div>';

                                }

                            }else{
                                $res='<div class="center-align">
                                            <h5 class="m orange-text">خطا در پرداخت</h5>';
                                if ($web){
                                    $res.=$web_back_url;
                                }else{
                                    $res.=$app_back_url;
                                }
                                $res.='</div>';
                            }



                            break;


                    }
                    break;
            }
            return view('payment' , ['text'=> $res,'phone'=>'09379109962']);
        }

    }
}
