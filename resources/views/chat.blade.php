@include('header')

<main class="app-content">

    <div class="col-md-12">
      <div class="tile">
        <div class="messanger">
          <div class="messages">

              @foreach($data as $row)

                  <div class="message @if($row->sender ==1) me @endif">
                      <p class="info">{{$row->description}}</p>
                  </div>

              @endforeach

          </div>
          <div class="sender">
              <form class="dispaly-contents" id="add_chat" method="post">
                  <input type="text" name="description" style="direction: rtl;text-align: right;" placeholder="پیام">
                  <input type="hidden"  name="action" value="add_ticket">
                  <input type="hidden" name="user_id" value="{{$user_id}}">
                  <button class="btn btn-primary" type="submit"><i class="fa fa-lg fa-fw fa-paper-plane"></i></button>
              </form>
            </div>
        </div>
      </div>
    </div>

</main>
@include('footer')
<script>
    $("form#add_chat").submit(function(e) {

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
