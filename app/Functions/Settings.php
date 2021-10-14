<?php
namespace App\Functions;

class Settings
{
    private $base_url='https://zobs.ir/zoobs/';
    private $pic_url='l_m_includes/pic';
    private $limit=10;
    private $users_price_off=20;

    function get_base_url(){

        return $this->base_url;
    }
    function get_pic_url(){

        return $this->pic_url;
    }
    function get_limit(){

        return $this->limit;
    }
    function get_users_price_off(){

        return $this->users_price_off;
    }

}

//php artisan make:model LM_table -m -c
//php artisan migrate



?>
