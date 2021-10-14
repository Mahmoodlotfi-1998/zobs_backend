<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Functions\Algoritms;
use App\Functions\jdf;
use App\LM_users;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{

    private $algoritms;
    private $jdf;
    function __construct(){
        $this->algoritms=new Algoritms();
        $this->jdf=new jdf();
    }

    public function Register(Request $request)
    {

        if ($this->algoritms->discreate_mikhay($request->post('mikhay'))) {
            $row_insert=LM_users::insertGetId(['phone'=>$request->post('phone'),
                'first_name'=>$request->post('first_name'),
                'last_name'=>$request->post('last_name'),
//                'subscription'=>$request->post('subscription'),
                'address'=>$request->post('address'),
                'fix_phone'=>$request->post('fix_phone')
            ]);
            if ($row_insert){
                $response['status'] = 'ok';
                $response['user_id'] = $this->algoritms->create_id($row_insert);
                return json_encode($response);
            }else{
                $response['status'] = 'nook';
                return json_encode($response);
            }
        }
    }

    public function Update(Request $request){
        if ($this->algoritms->discreate_mikhay($request->post('mikhay'))) {

            $user=LM_users::where('id',$this->algoritms->discreate_id($request->post('user_id')));
            $user=$this->algoritms->check_exist_row($user);

//            $user=$this->algoritms->discreate_id($request->post('user_id'),'l_m_users');
//            if(isset($_POST['first_name'])){
//                $user->first_name=$_POST['first_name'];
//            }
//            if(isset($_POST['last_name'])){
//                $user->last_name=$_POST['last_name'];
//            }
//            if(isset($_POST['fix_phone'])){
//                $user->fix_phone=$_POST['fix_phone'];
//            }
//            if(isset($_POST['address'])){
//                $user->address=$_POST['address'];
//            }
            if($request->post('first_name')){
                $user->first_name=$request->post('first_name');
            }
            if($request->post('last_name')){
                $user->last_name=$request->post('last_name');
            }
            if($request->post('fix_phone')){
                $user->fix_phone=$request->post('fix_phone');
            }
            if($request->post('address')){
                $user->address=$request->post('address');
            }

            if($user->save()){
                $response['status'] = 'ok';
                return json_encode($response);
            }else{
                $response['status'] = 'nook';
                return json_encode($response);
            }

        }
    }

    public function GetSubscription(Request $request){
        if ($this->algoritms->discreate_mikhay($request->post('mikhay'))) {
            $user=DB::table('l_m_subscription')->select('id','name','long as long_time','price')->get();
            return json_encode($user);
        }

    }

    public function GetUserSubscription(Request $request){
        if ($this->algoritms->discreate_mikhay($request->post('mikhay'))) {
            $user=LM_users::where('id',$this->algoritms->discreate_id($request->post('user_id')));
            $user=$this->algoritms->check_exist_row($user);
            $time=$this->jdf->jdate('Y/m/d');
            $query=DB::select('select DATEDIFF(`s_end`,"'.$time.'") AS DateDiff from l_m_users where id='.$user->id);

            $res['status']='ok';
            $res['day']=$query[0]->DateDiff;
            return json_encode($res);
        }

    }

    public function GetUserInfo(Request $request){
        if ($this->algoritms->discreate_mikhay($request->post('mikhay'))) {
            $user=LM_users::where('id',$this->algoritms->discreate_id($request->post('user_id')));
            $user=$this->algoritms->check_exist_row($user);
            $user_info = DB::table('l_m_users')
                ->select('first_name', 'last_name','phone','fix_phone','address')
                ->where('id', '=', $user->id)->first();

            $time=$this->jdf->jdate('Y/m/d');
            $query=DB::select('select DATEDIFF(`s_end`,"'.$time.'") AS DateDiff from l_m_users where id='.$user->id);


            $user_info->day=$query[0]->DateDiff;

            return json_encode($user_info);
        }
    }

}
