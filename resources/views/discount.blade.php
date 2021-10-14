@include('header')

<main class="app-content">

    <div class="app-title">
        <div class="ships">
            <h1>کد های تخفیف<i class="fa fa-th-list"></i></h1>
        </div>
        <p><a class="btn btn-info icon-btn" onclick="open_create_user();">اضافه کردن<i style="margin-left: 10px;" class="fa fa-plus"></i></a></p>
    </div>

    <div class="row" style="display: none;" id="insert_dcat">
        <div class="col-md-12">
            <div class="tile">
                <div class="row">
                    <form class="dispaly-contents" id="add_user" method="post">
                        <div class="col-lg-6 float-right" style="float: right;">
                            <div class="form-group">
                                <br>
                                <button class="btn btn-info icon-btn" name="inc">افزودن<i style="margin-left: 10px;" class="fa fa-plus"></i></button>
                                <br>
                                <br>
                                <div class="btn btn-danger"  onclick="close_create_user();">لغو</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputPassword1">نام</label>
                                <input class="form-control" name="name" id="exampleInputPassword1" type="text">
                                <label for="exampleInputPassword1">درصد تخفیف</label>
                                <input class="form-control" name="price_off" id="exampleInputPassword1" type="number">

                                <input class="form-control" name="action" value="add_discount" id="exampleInputPassword1" type="hidden" >

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="display: none;" id="edite_dcat">
        <div class="col-md-12">
            <div class="tile">
                <div class="row">
                    <form class="dispaly-contents" id="edit_user" method="post">
                        <div class="col-lg-6 float-right" style="float: right;">
                            <div class="form-group">
                                <br>
                                <button class="btn btn-info icon-btn" name="inc">ویرایش<i style="margin-left: 10px;" class="fa fa-plus"></i></button>
                                <br>
                                <br>
                                <div class="btn btn-danger"  onclick="close_edit_user();">لغو</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputPassword1">عنوان</label>
                                <input class="form-control" name="name" id="phone_edite" type="text" maxlength="11">
                                <input class="form-control" name="action" value="change_user_rules" type="hidden" >
                                <input class="form-control" name="user_id" value="" id="user_id" type="hidden" >

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>آیدی</th>
                                <th>نام</th>
                                <th>کد</th>
                                <th>درصد کاهش</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($data as $row)

                                <tr>
                                    <td>{{$row->id}}</td>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->code}}</td>
                                    <td>{{$row->price_off}}</td>

                                    <td>
                                        <a class=" btn btn-danger" onclick="delete_user({{$row->id}});">حذف</a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('footer')
<script type="text/javascript" src="{{ asset('include/panel/js/plugins/jquery.dataTables.min.js')}}" ></script>
<script type="text/javascript" src="{{ asset('include/panel/js/plugins/dataTables.bootstrap.min.js')}}"></script>
<script type="text/javascript">$('#sampleTable').DataTable();</script>

<script>

    var rules = document.getElementById("rules");
    var x = document.getElementById("insert_dcat");

    function open_create_user() {
        x.style.display = "block";
    }

    function close_create_user() {
        x.style.display = "none";
    }

    var edite_dcat = document.getElementById("edite_dcat");

    function close_edit_user() {
        edite_dcat.style.display = "none";
    }

    function show_rules(id,price_traffic,price_part) {

        $('input[name=service_id]').val(id);
        $('input[name=service_id_show]').val(id);
        $('input[name=price_traffic]').val(price_traffic);
        $('input[name=price_part]').val(price_part);

        window.scrollTo({
            top: 0,
            left: 0,
            behavior: 'smooth'
        });

        edite_dcat.style.display = "block";

    }

    function change_rules(id,type_id){
        var types = document.getElementsByName("type");
        var type='x';
        var len = types.length;
        for (var i = 0; i < len; i++) {
            if(i==type_id){
                type=types[i].value;
            }
        }
        alert(type_id);
        alert(type);
        var input ={
            'user_id': id,
            'type': type,
            'action' :'change_user_rules'
        };
        $.ajax({
            url : 'controler.php',
            type : 'POST',
            data : input,
            success : function(response) {
                const res=JSON.parse(response);
                if(res.status =='ok'){
                    swal("موفقیت آمیز بود", ".عملیات با موفقیت انجام گرفت", "success");
                    location.reload();
                }else{
                    swal("مشکلی رخ داده", "ارور پایگاه داده رخ داده است", "error");
                }
            },
            error : function() {
                swal("موفقیت آمیز نبود", "مشکل سیستمی به وجود آمد", "error");
            }
        });
    }

    function delete_user(id){
        swal({
            title: "آیا مطمعنید؟",
            text: "!!!اطلاعات کاربر مورد نظر پاک خواهد شد",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: ".بله حذف شود",
            cancelButtonText: ".خیر منصرف شدم",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm) {
            if (isConfirm) {
                var input ={
                    'id': id,
                    'action' :'del_discount'
                };

                $.ajax({
                    url : 'api/panel',
                    type : 'POST',
                    data : input,
                    success : function(response) {
                        const res=JSON.parse(response);
                        if(res.status =='ok'){
                            swal("موفقیت آمیز بود!", "عملیات با موفقیت انجام شد", "success");
                            location.reload();
                        }else{
                            swal("ارور پایگاه داده", ".مشکلی به وجود آمده", "error");
                        }
                    },
                    error : function() {
                        swal("ارور سیستمی", ".مشکلی به وجود آمده", "error");
                    }
                });

            } else {
                swal("Cancelled", ".با موفقیت کنسل شد", "info");
            }
        });
    }

    $("form#add_user").submit(function(e) {

        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'api/panel',
            type: 'POST',
            data: formData,
            success: function (data) {
                const res=JSON.parse(data);

                if (res.status=='ok'){
                    swal({
                        title: "عملیات با موفقیت انجام شد",
                        text: "تصمیم بعدی شما چیست؟",
                        type: "success",
                        confirmButtonText: "ادامه",
                        confirmButtonColor: '#002bff',
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }, function(isConfirm) {
                        if (isConfirm) {
                            location.reload();
                        }
                    });
                }else if(res.status=='exist_user'){
                    swal("ارور افزودن کاربر", "کاربری با این شماره وجود دارد", "error");
                }else if(res.status=='wrong_phone'){
                    swal("ارور افزودن کاربر", "شماره همراه نادرست میباشد", "error");
                }
                else {

                    swal("ارور پایگاه داده", "مشکلی به وجود آمده", "error");
                }
            },
            error : function() {
                swal("ارور سیستمی", "مشکلی به وجود آمده", "error");
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    $("form#edit_user").submit(function(e) {

        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'api/panel',
            type: 'POST',
            data: formData,
            success: function (data) {
                const res=JSON.parse(data);

                if (res.status=='ok'){
                    swal({
                        title: "عملیات با موفقیت انجام شد",
                        text: "تصمیم بعدی شما چیست؟",
                        type: "success",
                        confirmButtonText: "ادامه",
                        confirmButtonColor: '#002bff',
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }, function(isConfirm) {
                        if (isConfirm) {
                            location.reload();
                        }
                    });
                }else if(res.status=='exist_user'){
                    swal("ارور افزودن کاربر", "کاربری با این شماره وجود دارد", "error");
                }else if(res.status=='wrong_phone'){
                    swal("ارور افزودن کاربر", "شماره همراه نادرست میباشد", "error");
                }
                else {

                    swal("ارور پایگاه داده", "مشکلی به وجود آمده", "error");
                }
            },
            error : function() {
                swal("ارور سیستمی", "مشکلی به وجود آمده", "error");
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });


</script>
