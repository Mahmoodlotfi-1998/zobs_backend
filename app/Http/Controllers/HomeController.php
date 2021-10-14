<?php

namespace App\Http\Controllers;

use App\Functions\Algoritms;
use App\Functions\jdf;
use App\Functions\Settings;
use App\LM_users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $algoritms;
    private $jdf;
    private $settings;
    public function __construct()
    {
//        $this->middleware('auth');
        $this->algoritms=new Algoritms();
        $this->jdf=new jdf();
        $this->settings=new Settings();

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function getCategory(Request $request){

        if($this->algoritms->discreate_mikhay($request->post('mikhay'))) {

            switch ($request->post('action')) {
                case 'parents':
                    $main_category = DB::table('l_m_categorys')
                        ->select('id', 'title','pic')
                        ->where('parent_id', '=', '0')->get();
                    foreach ($main_category as $row){
                        $row->pic=$this->settings->get_base_url().$row->pic;
                    }
                    return json_encode($main_category);

                case 'childs':

                    $main_category = DB::table('l_m_categorys')
                        ->select('id', 'title','price')
                        ->where('parent_id', '=', $request->post('parent_id'))->get();

                    $user=LM_users::where('id',$this->algoritms->discreate_id($request->post('user_id')));
                    $user=$this->algoritms->check_exist_row($user);

                    $time=$this->jdf->jdate('Y/m/d');
                    $query=DB::select('select DATEDIFF(`s_end`,"'.$time.'") AS DateDiff from l_m_users where id='.$user->id);

                    $res['day']=$query[0]->DateDiff;
                    $res['list']=$main_category;

                    return json_encode($res);

                case 'search':
                    $main_category = DB::table('l_m_categorys')
                        ->select('id', 'title','price')
                        ->where('parent_id', '!=', 0)
                        ->where('title', 'like', '%' . $request->post('title'). '%')->get();

                    $user=LM_users::where('id',$this->algoritms->discreate_id($request->post('user_id')));
                    $user=$this->algoritms->check_exist_row($user);
                    $time=$this->jdf->jdate('Y/m/d');
                    $query=DB::select('select DATEDIFF(`s_end`,"'.$time.'") AS DateDiff from l_m_users where id='.$user->id);

                    $res['day']=$query[0]->DateDiff;
                    $res['list']=$main_category;

                    return json_encode($res);

                case 'get_price':
                    $main_category = DB::table('l_m_categorys')
                        ->select('id', 'price','pic')
                        ->where('id', '=', $_POST['id'])->first();

                    $list=['price'=>$main_category->price];
                    return json_encode($list);

            }
        }
    }

    public function getSubscription(Request $request){

        if($this->algoritms->discreate_mikhay($request->post('mikhay'))) {

            $main_category = DB::table('l_m_subscription')
                ->select('id', 'name','long','price')
                ->get();
            return json_encode($main_category);

        }
    }

    public function policyPage(){
        $law=DB::table('setting')
            ->where('key','=','privacy')
            ->first();
        return view('privacy')->with(['data'=>$law->value]);
    }
    public function aboutPage(){
        $law=DB::table('setting')
            ->where('key','=','about')
            ->first();
        return view('about')->with(['data'=>$law->value]);
    }
    public function lawPage(){
        $law=DB::table('setting')
            ->where('key','=','law')
            ->first();
        return view('law')->with(['data'=>$law->value]);
    }

    public function getVersion(){
        return json_encode(['version'=>'1.0.0']);
    }
    public function getIndexPage(){

        $about=DB::table('setting')
            ->where('key','=','about')
            ->first();
        return json_encode(['about'=>$about->value]);
    }

    public function PhoneOtp(Request $request)
    {
        $mobile_number=$request->post('phone');
//        switch ($request->post('action')){
//            case 'send_otp':
//                $remotePost=new RemotePost("Advertising1389","Adver_isfahan");
//
//                $otp = rand(10000, 99999);
//                $message ="به زوبس خوش آمدید.
//کد ورود شما : ";
//                $message =$message.$otp;
//
//                try {
//                    $otp_row=LM_users::where('phone',$mobile_number);
//
//                    if ($otp_row->count() == 1 && ($otp_row->type=='admin' || $otp_row->type=='helper' || $otp_row->type=='agent')) {
//                        $otp_row=LM_otp::where('phone',$mobile_number);
//                        $otp_row=$otp_row->first();
//                        $otp_row->otp=$otp;
//                        if($otp_row->save()){
////                            $remotePost->SendCustomMessage($mobile_number,$message);
//
//                            $response['status'] = 'ok';
//                            $response['otp'] = $otp;
//                            return json_encode($response);
//                        }else{
//                            $response['status'] = 'nook';
//                            return json_encode($response);
//                        }
//
//                    } else {
//                        $response['status'] = 'nook_select_user';
//                        $response['error'] = true;
//                        echo json_encode($response);
//                    }
//                }catch(Exception $e){
//                    die('Error: '.$e->getMessage());
//                }
//                break;
//
//            case 'verify_otp':
//                $otp=$request->post('otp');
//
//                $otp_row=LM_otp::where('phone',$mobile_number)->where('otp',$otp);
//
//                if($otp_row->count() ==1){
//                    $user_row=LM_users::where('phone',$mobile_number);
//                    if($user_row->count() ==1){
//
//                        session_start();
//                        ini_set('session.gc_maxlifetime', 90*3600);
//// each client should remember their session id for EXACTLY 1 hour
//                        session_set_cookie_params(24*90*3600);
//                        $_SESSION['zoobs']='hisdfs';
//                        $_SESSION['user_id']=$mobile_number;
//                        session_write_close();
//
//                        $list=['status'=>'ok'];
//                        return json_encode($list);
//                    }
//
//                }else{
//                    return json_encode(array("status"=>"nook"));
//
//                }
//
//                break;
//
//        }

    }

}
