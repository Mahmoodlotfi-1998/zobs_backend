@include('header')

<main class="app-content">

    <div class="app-title">
        <div class="ships">
            <h1>پرداختی ها<i class="fa fa-th-list"></i></h1>
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
                                <th>کاربری</th>
                                <th>وضعیت</th>
                                <th>مقدار</th>
                                <th>توضیحات</th>
                                <th>شماره پیگیری</th>
                                <th>تاریخ</th>
                                <th>زمان</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($data as $row)

                                <tr>
                                    <td>{{$row->id}}</td>
                                    <td>{{$row->user_id}}</td>
                                    <td>{{$row->status}}</td>
                                    <td>{{$row->mount}}</td>
                                    <td>{{$row->desc}}</td>
                                    <td>{{$row->RefID}}</td>
                                    <td>{{$row->date}}</td>
                                    <td>{{$row->time}}</td>

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

