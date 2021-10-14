<?php

    $admin='dashboard,users,category,dcategory,chat,services,payment,subscription,ticket,discount,categorys,setting';
    $helper='dashboard,user';
    $agent='dashboard,amazing';
    $norm='dashboard,amazing';
    $check_user='';
    $directoryURI = $_SERVER['REQUEST_URI'];

    $components = explode('/', $directoryURI);
    $first_part = $components[2];

    $first=explode('.', $components[2]);

    if(!(session()->has('zoobs') && session()->get('zoobs') == 'hisdfs')){

        echo "<script>window.open('admin','_self')</script>";

    }else{

        $phone=session()->get('user_id');
        $user=DB::table('l_m_users')->where('phone',$phone)->first();
        switch ($user->type){

            case 'admin':
                $check_user=$admin;
                break;

            case 'helper':
                $check_user=$helper;
                break;

            case 'agent':
                $check_user=$agent;
                break;

            case 'norm':
                $check_user=$norm;
                break;

        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>پنل کاربری زوبس</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link href="{{ asset('include/panel/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('include/panel/css/main2.css') }}" rel="stylesheet">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="app sidebar-mini">

<!-- Navbar-->
<header class="app-header">
    <!-- Navbar Right Menu-->
    <!--    <ul class="app-nav">-->
    <!---->
    <!--        Notification Menu-->
    <!--        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Show notifications"><i class="fa fa-bell-o fa-lg"></i></a>-->
    <!--            <ul class="app-notification dropdown-menu dropdown-menu-right">-->
    <!--                <li class="app-notification__title">You have 4 new notifications.</li>-->
    <!--                <div class="app-notification__content">-->
    <!--                    <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-primary"></i><i class="fa fa-envelope fa-stack-1x fa-inverse"></i></span></span>-->
    <!--                            <div>-->
    <!--                                <p class="app-notification__message">Lisa sent you a mail</p>-->
    <!--                                <p class="app-notification__meta">2 min ago</p>-->
    <!--                            </div></a></li>-->
    <!--                    <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-danger"></i><i class="fa fa-hdd-o fa-stack-1x fa-inverse"></i></span></span>-->
    <!--                            <div>-->
    <!--                                <p class="app-notification__message">Mail server not working</p>-->
    <!--                                <p class="app-notification__meta">5 min ago</p>-->
    <!--                            </div></a></li>-->
    <!--                    <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-success"></i><i class="fa fa-money fa-stack-1x fa-inverse"></i></span></span>-->
    <!--                            <div>-->
    <!--                                <p class="app-notification__message">Transaction complete</p>-->
    <!--                                <p class="app-notification__meta">2 days ago</p>-->
    <!--                            </div></a></li>-->
    <!--                    <div class="app-notification__content">-->
    <!--                        <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-primary"></i><i class="fa fa-envelope fa-stack-1x fa-inverse"></i></span></span>-->
    <!--                                <div>-->
    <!--                                    <p class="app-notification__message">Lisa sent you a mail</p>-->
    <!--                                    <p class="app-notification__meta">2 min ago</p>-->
    <!--                                </div></a></li>-->
    <!--                        <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-danger"></i><i class="fa fa-hdd-o fa-stack-1x fa-inverse"></i></span></span>-->
    <!--                                <div>-->
    <!--                                    <p class="app-notification__message">Mail server not working</p>-->
    <!--                                    <p class="app-notification__meta">5 min ago</p>-->
    <!--                                </div></a></li>-->
    <!--                        <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-success"></i><i class="fa fa-money fa-stack-1x fa-inverse"></i></span></span>-->
    <!--                                <div>-->
    <!--                                    <p class="app-notification__message">Transaction complete</p>-->
    <!--                                    <p class="app-notification__meta">2 days ago</p>-->
    <!--                                </div></a></li>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--                <li class="app-notification__footer"><a href="#">See all notifications.</a></li>-->
    <!--            </ul>-->
    <!--        </li>-->
    <!--         User Menu-->
    <!--        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>-->
    <!--            <ul class="dropdown-menu settings-menu dropdown-menu-right">-->
    <!--                <li><a class="dropdown-item" href="page-user.html"><i class="fa fa-cog fa-lg"></i> Settings</a></li>-->
    <!--                <li><a class="dropdown-item" href="page-user.html"><i class="fa fa-user fa-lg"></i> Profile</a></li>-->
    <!--                <li><a class="dropdown-item" href="page-login.html"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>-->
    <!--            </ul>-->
    <!--        </li>-->
    <!--    </ul>-->

    <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>

    <a class="app-header__logo white-text" href="">
        زوبس
<?php
//echo $first_part;
?>
    </a>

</header>

<?php
if(strpos($check_user,$first[0]) === false){

?>

<script src="{{ asset('include/panel/js/jquery-3.3.1.min.js')}}"></script>
<script src="{{ asset('include/panel/js/popper.min.js')}}"></script>
<script src="{{ asset('include/panel/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('include/panel/js/main.js')}}"></script>
<script src="{{ asset('include/panel/js/plugins/pace.min.js')}}"></script>
<script src="{{ asset('include/panel/js/plugins/sweetalert.min.js')}}"></script>

<script>
    swal({
        title: "مشکلی رخ داده",
        text: "شماره وارد شده در لیست کاربران موجود نمیباشد.",
        type: "warning",
        confirmButtonText: ".بله ",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function(isConfirm) {
        if (isConfirm) {
            window.open('dashboard',"_self");

        }
    });

</script>
<?php
exit();
}
?>

<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="https://advertisingisfahan.ir/wp-content/uploads/2017/07/%D8%AA%D8%A8%D9%84%D9%8A%D8%BA%D8%A7%D8%AA-%D8%AF%D8%B1-%D8%A7%D8%B5%D9%81%D9%87%D8%A7%D9%86-1.png" alt="User Image">
        <!--        <div style="text-align: center;">-->
        <!--            <p class="app-sidebar__user-name">طراحی شده توسط</p>-->
        <!--            <p class="app-sidebar__user-designation">تبلیغات ایران</p>-->
        <!--        </div>-->
    </div>
    <ul class="app-menu">
        <?php if(strpos($check_user,'dashboard') !== false){ ?>
            <li ><a class="app-menu__item <?php if(strpos($first_part, 'dashboard') !== false){ ?> active <?php }  ?>" href="dashboard"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">پنل کاربری</span></a></li>
        <?php }  ?>

        <?php if(strpos($check_user,'users') !== false){ ?>
            <li ><a class="app-menu__item <?php if(strpos($first_part, 'users') !== false){ ?> active <?php }  ?>" href="users"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">کاربران</span></a></li>
        <?php }  ?>

        <?php if(strpos($check_user,'category') !== false){ ?>
            <li ><a class="app-menu__item <?php if(strpos($first_part, 'category') !== false){ ?> active <?php }  ?>" href="category"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">دسته بندی ها</span></a></li>
        <?php }  ?>

        <?php if(strpos($check_user,'services') !== false){ ?>
            <li ><a class="app-menu__item <?php if(strpos($first_part, 'services') !== false){ ?> active <?php }  ?>" href="services"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">سرویس ها</span></a></li>
        <?php }  ?>

        <?php if(strpos($check_user,'payment') !== false){ ?>
            <li ><a class="app-menu__item <?php if(strpos($first_part, 'payment') !== false){ ?> active <?php }  ?>" href="payment"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">پرداختی ها</span></a></li>
        <?php }  ?>

        <?php if(strpos($check_user,'subscription') !== false){ ?>
            <li ><a class="app-menu__item <?php if(strpos($first_part, 'subscription') !== false){ ?> active <?php }  ?>" href="subscription"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">اشتراک ها</span></a></li>
        <?php }  ?>

        <?php if(strpos($check_user,'ticket') !== false){ ?>
            <li ><a class="app-menu__item <?php if(strpos($first_part, 'ticket') !== false){ ?> active <?php }  ?>" href="ticket"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">پیام ها</span></a></li>
        <?php }  ?>

        <?php if(strpos($check_user,'discount') !== false){ ?>
            <li ><a class="app-menu__item <?php if(strpos($first_part, 'discount') !== false){ ?> active <?php }  ?>" href="discount"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">کد های تخفیف</span></a></li>
        <?php }  ?>

        <?php if(strpos($check_user,'setting') !== false){ ?>
            <li ><a class="app-menu__item <?php if(strpos($first_part, 'setting') !== false){ ?> active <?php }  ?>" href="setting"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">تنظیمات</span></a></li>
        <?php }  ?>

    </ul>

</aside>
