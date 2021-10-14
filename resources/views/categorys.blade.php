@include('header')

<main class="app-content">

    <div class="app-title">
        <div class="ships">
            <h1>دسته ها<i class="fa fa-th-list"></i></h1>
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
                                <th>عنوان</th>
                                <th>تصویر</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($data as $row)

                                <tr>
                                    <td>{{$row->id}}</td>
                                    <td>{{$row->title}}</td>
                                    <td class="row_image"><img src="{{$row->pic}} "></td>
                                    <td>
                                        <a class=" btn btn-info" href="dcategory.php?id={{$row->id}}">ویرایش زیر دسته ها</a>
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

