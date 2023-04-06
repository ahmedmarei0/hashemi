@extends('layouts.admin')
@section('content')
{{-- الطلاب الذين قاموا بتسليم الواجب --}}
<div class="col-12 p-3">
    <div class="col-12 col-lg-12 p-0 main-box">
        <div class="col-12 px-0">
            <div class="col-12 p-0 row">
                <div class="col-12 col-lg-4 py-3 px-3">
                    <span class="fas fa-pages"></span>
                    الطلاب الذين قاموا بتسليم الواجب
                </div>


            </div>
            <div class="col-12 divider" style="min-height: 2px;"></div>
        </div>
        <div class="col-12 py-2 px-2 row">
            <div class="col-12 col-lg-4 p-2">
                <form method="GET">
                    <input type="text" name="q" class="form-control" placeholder="بحث ... "
                        value="{{request()->get('q')}}">
                </form>
            </div>
        </div>
        <div class="col-12 p-3" style="overflow:auto">
            <div class="col-12 p-0" style="min-width:1000px;">


                <table class="table table-bordered  table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم الطالب</th>
                            <th>معاد التسليم</th>
                            <th>تحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $sheet)
                        <tr>
                            <td>{{$sheet->id}}</td>
                            <td>{{$sheet->name}}</td>
                            <td>{{$sheet->created_at}}</td>

                            <td style="min-width: 270px;">

                                @permission('sheets-read')
                                <form action="{{route('admin.show.file')}}" method="GET" class="d-inline">
                                    <input type="hidden" name="url"
                                        value="{{ '/uploads/sheets/'.$sheet->file }}">
                                    <button class="btn" type="submit">
                                        <span class="btn  btn-outline-primary btn-sm font-1 mx-1">
                                            <span class="fas fa-search "></span>عرض المرفق
                                        </span>
                                    </button>
                                </form>
                                <form action="{{route('admin.download.file')}}" method="GET" class="d-inline">
                                    <input type="hidden" name="url"
                                        value="{{ '/uploads/sheets/'.$sheet->file }}">
                                    <button class="btn" type="submit">
                                        <span class="btn  btn-outline-primary btn-sm font-1 mx-1">
                                            <span class="fas fa-search "></span>تحميل المرفق مباشر
                                        </span>
                                    </button>
                                </form>
                                @endpermission

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-12 p-3">
            {{$data->appends(request()->query())->render()}}
        </div>
    </div>
</div>


@endsection
