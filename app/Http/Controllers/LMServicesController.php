<?php

namespace App\Http\Controllers;

use App\Functions\Algoritms;
use App\Functions\jdf;
use App\Functions\Settings;
use App\LM_Services;
use App\LM_users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LMServicesController extends Controller
{

    private $algoritms;
    private $jdf;
    private $settings;

    function __construct(){
        $this->algoritms=new Algoritms();
        $this->jdf=new jdf();
        $this->settings=new Settings();
    }

    public function AddServices(Request $request){
        if ($this->algoritms->discreate_mikhay($request->post('mikhay'))) {

            $user=LM_users::where('id',$this->algoritms->discreate_id($request->post('user_id')));
            $user=$this->algoritms->check_exist_row($user);

            //check price and discount code for price
            $category = DB::table('l_m_categorys')
                ->where('id', '=', $request->post('cat_id'));
            $category_fech = $category->first();
            if($request->has('code') && !empty($request->post('code'))){
                $code = DB::table('l_m_discount_code')
                    ->where('code', '=', $request->post('code'));

                if ($code->count() ==1){
                    $code=$code->first();
                    $table_code=$code->price_off;
                    if ($category->count() ==1) {

                        $last_price=$category_fech->price-($category_fech->price * ( $code->price_off/ 100));
                    }else{
                        $response['status'] = 'nook_cat_id';
                        return json_encode($response);
                    }
                }else{
                    $response['status'] = 'nook_code';
                    return json_encode($response);
                }
            }else{
                $last_price=$category_fech->price;
                $table_code=0;
            }

            $row_insert=LM_Services::insertGetId(['user_id'=>$user->id,
                'cat_id'=>$request->post('cat_id'),
                'price'=>$last_price,
                'address'=>$request->post('address'),
                'status'=>0,
                'price_off'=>$table_code,
                'day'=>$this->jdf->get_day(),
                'date'=>$this->jdf->jdate('Y/m/d')
            ]);

            if ($row_insert){
                $response['status'] = 'ok';
                $response['service_id'] = $this->algoritms->create_id($row_insert);
                return json_encode($response);
            }else{
                $response['status'] = 'nook';
                return json_encode($response);
            }
        }
    }

    public function CheckCode(Request $request){
        if($this->algoritms->discreate_mikhay($request->post('mikhay'))) {
            $code = DB::table('l_m_discount_code')
                ->where('code', '=', $request->post('code'));
            if ($code->count() ==1){
                $code=$code->first();
                $response['status'] = 'ok';
                $response['price_off'] =$code->price_off;
                return json_encode($response);
            }else{
                $response['status'] = 'nook';
                return json_encode($response);
            }

        }
    }

    public function GetAllServices(Request $request){
        if ($this->algoritms->discreate_mikhay($request->post('mikhay'))) {
            if (isset($_POST['page']) && !empty($_POST['page'])){
                $page=$_POST['page'];
            }else{
                $page=0;
            }

            $user=LM_users::where('id',$this->algoritms->discreate_id($request->post('user_id')));
            $user=$this->algoritms->check_exist_row($user);

            $code = DB::table('l_m__services')
                ->select('l_m__services.id','l_m_categorys.title as cat_name','day','date','l_m__services.price','l_m__services.status')
                ->join('l_m_categorys','l_m__services.cat_id','l_m_categorys.id')
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'DESC')
                ->paginate($this->settings->get_limit(), ['*'], 'page', $page + 1);

            $res['pages'] = $code->lastPage()-1;

            $list=$code->items();
            foreach ($list as $row){
                $row->id=$this->algoritms->create_id($row->id);
                switch ($row->status) {
                    case 0:
                        $row->status_str='جاری';
                        break;
                    case 1:
                        $row->status_str='پرداخت نشده';
                        break;
                    case 2:
                        $row->status_str='تکمیل شده';
                        break;
                    case 3:
                        $row->status_str='لغو شده';
                        break;
                }
            }
            $res['list'] = $list;
            return json_encode($res);


        }
    }

    public function GetServices(Request $request){

        if ($this->algoritms->discreate_mikhay($request->post('mikhay'))) {

            $user=LM_users::where('id',$this->algoritms->discreate_id($request->post('user_id')));
            $user=$this->algoritms->check_exist_row($user);

            $services=LM_Services::where('id',$this->algoritms->discreate_id($request->post('id')));
            $services=$this->algoritms->check_exist_row($services);

            $row = DB::table('l_m__services')
                ->select('l_m_categorys.title as cat_name','l_m_categorys.price as cat_price','day','date','l_m__services.price','l_m__services.price_traffic','l_m__services.price_off','l_m__services.price_part','l_m__services.status')
                ->join('l_m_categorys','l_m__services.cat_id','l_m_categorys.id')
                ->where('l_m__services.id', '=', $services->id);

            if ($row->count() ==1){

                $row=$row->first();
                $lists=[];
                if ($row->price=='طبق تعرفه' || $row->price=='توافقی'){

                    $list['title']=$row->cat_name;
                    $list['value']=0;
                    $list['type']='inc';
                    array_push($lists,$list);

                    $list['value']=0;
                    $list['title']='تخفیف ویژه مشترکین زوبس '.$this->settings->get_users_price_off().'%';
                    $list['type']='dec';
                    array_push($lists,$list);

                    $list['title']='هزینه ایاب و ذهاب تکنسین';
                    $list['value']=$row->price_traffic;
                    $list['type']='inc';
                    array_push($lists,$list);

                    $list['title']='هزینه قطعات مورد استفاده';
                    $list['value']=$row->price_part;
                    $list['type']='inc';
                    array_push($lists,$list);

                    $res=['price'=>0,'list'=>$lists,'status'=>$row->status];
                    return json_encode($res);

                }

                $price_off=$row->price_off;
                $price_traffic=$row->price_traffic;
                $price_part=$row->price_part;

                $total_price = $row->price + $price_part + $price_traffic;
//                echo $total_price;
                $list['title']=$row->cat_name;
                $list['value']=$row->price;
                $list['type']='inc';
                array_push($lists,$list);


                $list['title']='تخفیف ویژه مشترکین زوبس '.$this->settings->get_users_price_off().'%';
                $user_price_off= $total_price * ($this->settings->get_users_price_off()/100);
                $list['value']= $user_price_off;
                $list['type']='dec';
                array_push($lists,$list);

//                if($row->price=='طبق تعرفه' || $row->price=='توافقی'){
//                    $list['value']='مشخص نشده';
//                    $user_price_off=0;
//                    $list['type']='dec';
//                    array_push($lists,$list);
//
//                }
//                else{
//
//                }

                $list['title']='کد تخفیف';

                if ($row->price_off == 0){
                    $price_off_dec=0;
                }else{
                    $price_off_dec=$total_price * ($row->price_off/100);

                }
                $list['value']=$price_off_dec;

                $list['type']='dec';
                array_push($lists,$list);

                $list['title']='هزینه ایاب و ذهاب تکنسین';
                $list['value']=$row->price_traffic;
                $list['type']='inc';
                array_push($lists,$list);


                $list['title']='هزینه قطعات مورد استفاده';
                $list['value']=$row->price_part;
                $list['type']='inc';
                array_push($lists,$list);

                $last_price = $total_price - $user_price_off ;
//                echo $total_price;
//                echo '<br>';
//                echo $user_price_off;
//                echo '<br>';
//                echo $price_off_dec;
//                echo '<br>';

                $res=['price'=>$last_price,'list'=>$lists,'status'=>$row->status];
                return json_encode($res);

            }else{
                return json_encode([]);
            }

        }
    }
}
