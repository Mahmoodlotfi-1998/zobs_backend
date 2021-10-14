<?php

namespace App\Http\Controllers;

use App\Functions\Algoritms;
use App\Functions\jdf;
use App\Functions\Settings;
use App\Http\Controllers\Api\PhoneOtp;
use App\LM_otp;
use App\LM_tickets;
use App\LM_users;
use Illuminate\Http\Request;
use App\Classes\RemotePost;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{
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

    public function Admin(){
        return view('login');
    }

    public function Dashboard(){
        return view('dashboard');
    }
    public function Setting(){

        $about=DB::table('setting')
            ->where('key','=','about')
            ->first();
        $law=DB::table('setting')
            ->where('key','=','law')
            ->first();
        $privacy=DB::table('setting')
            ->where('key','=','privacy')
            ->first();

        return view('setting')->with(['about'=>$about->value,'law'=>$law->value,'privacy','privacy'=>$privacy->value]);
    }

    public function Users(){

        $user=DB::table('l_m_users')->get();
        $time=$this->jdf->jdate('Y/m/d');

        foreach ($user as $row){
            $query=DB::select('select DATEDIFF(`s_end`,"'.$time.'") AS DateDiff from l_m_users where id='.$row->id);

            $row->day=$query[0]->DateDiff;
        }



        return view('users')->with(['data'=>$user]);
    }

    public function Category(){

        $user=DB::table('l_m_categorys')->where('parent_id','=','0')->get();

        return view('categorys')->with(['data'=>$user]);
    }
    public function DCategory(Request $request){

        $user=DB::table('l_m_categorys')->where('parent_id','=',$request->get('id'))->get();

        return view('dcategory')->with(['data'=>$user,'cat_id'=>$request->get('id')]);
    }

    public function Services(){

        $user=DB::table('l_m__services')
            ->select('l_m__services.id','l_m_users.first_name','l_m_users.last_name','l_m_categorys.title as cat_name','l_m__services.price','l_m__services.status','l_m__services.price_off','l_m__services.price_traffic','l_m__services.price_part','day','date')
            ->join('l_m_users','l_m_users.id','=','l_m__services.user_id')
            ->join('l_m_categorys','l_m_categorys.id','=','l_m__services.cat_id')
            ->get();

        return view('services')->with(['data'=>$user]);
    }

    public function Payment(){

        $user=DB::table('l_m_payments')->get();

        return view('panelpayment')->with(['data'=>$user]);
    }

    public function Subscription(){

        $user=DB::table('l_m_subscription')->get();

        return view('subscription')->with(['data'=>$user]);
    }

    public function Ticket(){

        $user=DB::table('l_m_tickets')
            ->select(DB::raw('count(*) as user_count, user_id'))
            ->groupBy('user_id')
            ->get();


        return view('tickets')->with(['data'=>$user]);
    }

    public function Chat(Request $request){

        $user=DB::table('l_m_tickets')->where('user_id','=',$request->get('user_id'))->get();

        return view('chat')->with(['data'=>$user,'user_id'=>$request->get('user_id')]);
    }


    public function Discount(){

        $user=DB::table('l_m_discount_code')->get();


        return view('discount')->with(['data'=>$user]);
    }

    public function PhoneOtp(Request $request){
        $mobile_number=$request->post('phone');
        switch ($request->post('action')){
            case 'send_otp':
                $remotePost=new RemotePost("Advertising1389","Adver_isfahan");

                $otp = rand(10000, 99999);
                $message ="به زوبس خوش آمدید.
کد ورود شما : ";
                $message =$message.$otp;

                try {
                    $otp_row=LM_users::where('phone',$mobile_number);
                    $user_row=$otp_row->first();

                    if ($otp_row->count() == 1 && ($user_row->type=='admin' || $user_row->type=='helper' || $user_row->type=='agent')) {
                        $otp_row=LM_otp::where('phone',$mobile_number);
                        $otp_row=$otp_row->first();
                        $otp_row->otp=$otp;
                        if($otp_row->save()){
//                            $remotePost->SendCustomMessage($mobile_number,$message);

                            $response['status'] = 'ok';
                            $response['otp'] = $otp;
                            return json_encode($response);
                        }else{
                            $response['status'] = 'nook';
                            return json_encode($response);
                        }

                    } else {
                        $response['status'] = 'nook_select_user';
                        $response['error'] = true;
                        echo json_encode($response);
                    }
                }catch(Exception $e){
                    die('Error: '.$e->getMessage());
                }
                break;

            case 'verify_otp':
                $otp=$request->post('otp');

                $otp_row=LM_otp::where('phone',$mobile_number)->where('otp',$otp);

                if($otp_row->count() ==1){
                    $user_row=LM_users::where('phone',$mobile_number);
                    if($user_row->count() ==1){

                        session_start();
//                        $_SESSION['zoobs']='hisdfs';
                        $request->session()->put('zoobs', 'hisdfs');
                        $request->session()->put('user_id', $mobile_number);
//                        $_SESSION['user_id']=$mobile_number;
                        session_write_close();

                        $list=['status'=>'ok'];
                        return json_encode($list);
                    }

                }else{
                    return json_encode(array("status"=>"nook"));

                }

                break;

        }

}

    public function PanelControl(Request $request){
        switch ($request->post('action')){
            case 'change_user_rules':
                $user=LM_users::where('id',$request->post('user_id'))->first();

                $user->type=$request->post('type_edit');
                $user->phone=$request->post('phone_edite');

                if($user->save()){
                    $response['status'] = 'ok';
                    return json_encode($response);
                }else{
                    $response['status'] = 'nook';
                    return json_encode($response);
                }
                break;

            case 'del_user':
                $user=LM_users::where('id',$request->post('user_id'))->first();
                if($user->delete()){
                    $response['status'] = 'ok';
                    return json_encode($response);
                }else{
                    $response['status'] = 'nook';
                    return json_encode($response);
                }
                break;

            case 'add_user':
                $row_insert=LM_users::insertGetId(['phone'=>$request->post('phone'),
                    'first_name'=>$request->post('first_name'),
                    'last_name'=>$request->post('last_name'),
                    'address'=>'0',
                    'fix_phone'=>'0',
                    'type'=>$request->post('type')
                ]);
                if ($row_insert){
                    $response['status'] = 'ok';
                    return json_encode($response);
                }else{
                    $response['status'] = 'nook';
                    return json_encode($response);
                }
                break;

            case 'add_cat':
                $row_insert=DB::table('l_m_categorys')->insertGetId(['title'=>$request->post('title'),
                    'price'=>$request->post('price'),
                    'parent_id'=>$request->post('cat_id'),
                ]);
                if ($row_insert){
                    $response['status'] = 'ok';
                    return json_encode($response);
                }else{
                    $response['status'] = 'nook';
                    return json_encode($response);
                }
                break;

            case 'update_cat':
                $category=DB::table('l_m_categorys')
                    ->where('id','=',$request->post('cat_id'))
                    ->update(array(
                        'title' => $request->post('title_edit'),
                        'price' => $request->post('price_edit')
                        ));

                if ($category){
                    $response['status'] = 'ok';
                    return json_encode($response);
                }else{
                    $response['status'] = 'nook';
                    return json_encode($response);
                }

            case 'update_service':
                $category=DB::table('l_m__services')
                    ->where('id','=',$request->post('service_id'))
                    ->update(array(
                        'price' => $request->post('price'),
                        'price_traffic' => $request->post('price_traffic'),
                        'price_part' => $request->post('price_part'),
                        'status'=>'1'
                    ));

                if ($category){
                    $response['status'] = 'ok';
                    return json_encode($response);
                }else{
                    $response['status'] = 'nook';
                    return json_encode($response);
                }

                break;

            case 'edit_subscription':
                $category=DB::table('l_m_subscription')
                    ->where('id','=',$request->post('s_id'))
                    ->update(array(
                        'name' => $request->post('name_edit'),
                        'long' => $request->post('long_edit'),
                        'price' => $request->post('price_edit')
                    ));

                if ($category){
                    $response['status'] = 'ok';
                    return json_encode($response);
                }else{
                    $response['status'] = 'nook';
                    return json_encode($response);
                }

                break;

            case 'add_subscription':
                $row_insert=DB::table('l_m_subscription')->insertGetId([
                    'name'=>$request->post('name'),
                    'long'=>$request->post('long'),
                    'price'=>$request->post('price'),
                ]);
                if ($row_insert){
                    $response['status'] = 'ok';
                    return json_encode($response);
                }else{
                    $response['status'] = 'nook';
                    return json_encode($response);
                }
                break;

            case 'del_subscription':
                $user=DB::table('l_m_subscription')->where('id',$request->post('id'))->delete();
                if($user){
                    $response['status'] = 'ok';
                    return json_encode($response);
                }else{
                    $response['status'] = 'nook';
                    return json_encode($response);
                }

                break;

            case 'del_category':
                $user=DB::table('l_m_categorys')->where('id',$request->post('id'))->delete();
                if($user){
                    $response['status'] = 'ok';
                    return json_encode($response);
                }else{
                    $response['status'] = 'nook';
                    return json_encode($response);
                }

                break;

            case 'add_discount':
                $row_insert=DB::table('l_m_discount_code')->insertGetId(['name'=>$request->post('name'),
                    'code'=>$this->generateRandomString(6),
                    'price_off'=>$request->post('price_off'),
                ]);
                if ($row_insert){
                    $response['status'] = 'ok';
                    return json_encode($response);
                }else{
                    $response['status'] = 'nook';
                    return json_encode($response);
                }
                break;

            case 'del_discount':
                $user=DB::table('l_m_discount_code')->where('id',$request->post('id'))->delete();
                if($user){
                    $response['status'] = 'ok';
                    return json_encode($response);
                }else{
                    $response['status'] = 'nook';
                    return json_encode($response);
                }

                break;

            case 'add_ticket':
                $row_insert=LM_tickets::insertGetId([
                    'user_id'=>$request->post('user_id'),
                    'sender'=>0,
                    'description'=>$request->post('description'),
                    'created_at'=>$this->jdf->jdate('Y/m/d')
                ]);

                if ($row_insert){
                    $response['status'] = 'ok';
                    $response['date'] = $this->jdf->jdate('Y/m/d');
                    return json_encode($response);
                }else{
                    $response['status'] = 'nook';
                    return json_encode($response);
                }

            case 'update_setting':

                $about=DB::table('setting')
                    ->where('key','=','about')
                    ->update(array(
                        'value' => $request->post('about')
                    ));
                $law=DB::table('setting')
                    ->where('key','=','law')
                    ->update(array(
                        'value' => $request->post('law')
                    ));
                $privacy=DB::table('setting')
                    ->where('key','=','privacy')
                    ->update(array(
                        'value' => $request->post('privacy')
                    ));

                if ($privacy && $law && $about){
                    $response['status'] = 'ok';

                    return redirect('/setting');
                }else{
                    return redirect('/setting');
                }

                break;

            case 'del_service':
                $user=DB::table('l_m__services')->where('id',$request->post('id'))->delete();
                if($user){
                    $response['status'] = 'ok';
                    return json_encode($response);
                }else{
                    $response['status'] = 'nook';
                    return json_encode($response);
                }
                break;

        }
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }



}
