<?php

namespace App\Http\Controllers;

use App\Functions\Algoritms;
use App\Functions\Settings;
use App\Functions\jdf;
use App\LM_tickets;
use App\LM_users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LMTicketsController extends Controller
{
    private $algoritms;
    private $settings;
    private $jdf;
    function __construct(){
        $this->algoritms=new Algoritms();
        $this->settings=new Settings();
        $this->jdf=new jdf();
    }

    public function AddTicket(Request $request){
        if ($this->algoritms->discreate_mikhay($request->post('mikhay'))) {

            $user=LM_users::where('id',$this->algoritms->discreate_id($request->post('user_id')));
            $user=$this->algoritms->check_exist_row($user);

            $row_insert=LM_tickets::insertGetId([
                'user_id'=>$user->id,
                'sender'=>1,
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
        }
    }

    public function GetAllTickets(Request $request){
        if ($this->algoritms->discreate_mikhay($request->post('mikhay'))) {
            if (isset($_POST['page']) && !empty($_POST['page'])){
                $page=$_POST['page'];
            }else{
                $page=0;
            }

            $user=LM_users::where('id',$this->algoritms->discreate_id($request->post('user_id')));
            $user=$this->algoritms->check_exist_row($user);

            $list = DB::table('l_m_tickets')
                ->select('description','created_at as time_insert','sender')
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'desc');

//            $res['list'] = $list->items();
            return json_encode($list->get());


        }
    }

    public function GetAllTicketsOrderWeb(Request $request){
//        header("Access-Control-Allow-Origin: '*, *'");
        if ($this->algoritms->discreate_mikhay($request->post('mikhay'))) {
//            if (isset($_POST['page']) && !empty($_POST['page'])){
//                $page=$_POST['page'];
//            }else{
//                $page=0;
//            }

            $user=LM_users::where('id',$this->algoritms->discreate_id($request->post('user_id')));
            $user=$this->algoritms->check_exist_row($user);

            $list = DB::table('l_m_tickets')
                ->select('description','created_at as time_insert','sender')
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'asc');

//            $res['list'] = $list->items();
            return json_encode($list->get());


        }
    }

}
