<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link href="{{ asset('include/panel/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('include/panel/css/main2.css') }}" rel="stylesheet">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>ورود</title>
</head>
<body>
<section class="material-half-bg">
    <div class="cover"></div>
</section>
<section class="login-content">
    <div class="logo">
        <h1 >زوبس</h1>
        <h6 id="my_otp"></h6>
    </div>
    <div class="login-box">
        <form class="login-form">
            <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>ورود</h3>
            <div class="form-group">
                <label class="control-label float-right">شماره همراه</label>
                <input class="form-control" type="number" id="phone" placeholder="شماره" autofocus>
            </div>
            <div class="form-group btn-container">
                <p class="btn btn-info btn-block" data-toggle="flip" onclick="send_code();">ارسال کد</p>
            </div>
        </form>
        <form class="forget-form">
            <h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>کد ارسالی را وارد کنید:</h3>
            <div class="form-group">
                <label class="control-label">کد</label>
                <input class="form-control" type="number" id="otp" placeholder="کد">
                <input class="form-control" type="hidden" value="">
            </div>
            <div class="form-group btn-container">
                <a class="btn btn-primary btn-block" onclick="verify_code();"><i class="fa fa-unlock fa-lg fa-fw"></i>ورود</a>
            </div>
            <div class="form-group mt-3">
                <p class="semibold-text mb-0"><a data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> اصلاح شماره</a></p>
            </div>
        </form>
    </div>
</section>
<!-- Essential javascripts for application to work-->
<script src="{{ asset('include/panel/js/jquery-3.3.1.min.js')}}"></script>
<script src="{{ asset('include/panel/js/popper.min.js')}}"></script>
<script src="{{ asset('include/panel/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('include/panel/js/main.js')}}"></script>
<script src="{{ asset('include/panel/js/plugins/pace.min.js')}}"></script>
<script src="{{ asset('include/panel/js/plugins/sweetalert.min.js')}}"></script>
<script type="text/javascript">
    // Login Page Flipbox control
    $('.login-content [data-toggle="flip"]').click(function() {
        $('.login-box').toggleClass('flipped');
        return false;
    });

    function send_code(){
        var phone=$('#phone').val();
        if(phone.length != 11){
            swal("مشکلی رخ داده", "شماره وارد شده صحیح نمیباشد.", "error");
            $('.login-box').toggleClass('flipped');
            return false;
        }
        var input ={
            'phone': phone,
            'action' :'send_otp'
        };
        $.ajax({
            url : 'api/otp',
            type : 'POST',
            data : input,
            success : function(response) {
                // alert(response);
                const res=JSON.parse(response);

                if(res.status =='ok'){

                    document.getElementById("my_otp").innerHTML=res.otp;
                    // $('#my_otp').innerHTML=res.otp;
                }else if(res.status =='nook_select_user'){
                    swal("مشکلی رخ داده", "شماره وارد شده در لیست کاربران موجود نمیباشد.", "error");
                    $('.login-box').toggleClass('flipped');
                    return false;
                }
            },
            error : function() {
                swal("موفقیت آمیز نبود", "مشکل سیستمی به وجود آمد", "error");
                $('.login-box').toggleClass('flipped');
                return false;
            }
        });
    }
    function verify_code(){
        var phone=$('#phone').val();
        var otp=$('#otp').val();
        // alert(otp);
        if(otp.length != 5){
            swal("مشکلی رخ داده", "کد وارد شده صحیح نمیباشد.", "error");
            return;
        }
        var input ={
            'phone': phone,
            'otp': otp,
            'action' :'verify_otp'
        };
        $.ajax({
            url : 'api/otp',
            type : 'POST',
            data : input,
            success : function(response) {
                // alert(response);
                const res=JSON.parse(response);
                if(res.status =='ok'){
                    // alert('ok_recive');
                    window.open('dashboard',"_self");
                }else if(res.status =='nook'){
                    swal("ارور", "کد وارد شده صحیح نمیباشد.", "error");
                }
            },
            error : function() {

                swal("موفقیت آمیز نبود", "مشکل سیستمی به وجود آمد", "error");
                $('.login-box').toggleClass('flipped');
                return false;
            }
        });
    }
</script>
</body>
</html>
