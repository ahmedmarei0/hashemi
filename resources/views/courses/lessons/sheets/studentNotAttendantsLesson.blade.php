@extends('layouts.admin')
@section('content')
{{-- الطلاب الذين قاموا بتسليم الواجب --}}
<div class="col-12 p-3">
    <div class="col-12 col-lg-12 p-0 main-box">
        <div class="col-12 px-0">
            <div class="col-12 p-0 row">
                <div class="col-12 col-lg-4 py-3 px-3">
                    <span class="fas fa-pages"></span>
                    <h5>
                    الطلاب لم يحضروا الدرس
                    </h5>
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
                            <th>حالة الطالب الحالية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $attendant)
                        <tr>
                            <td>{{$attendant->id}}</td>
                            <td>{{$attendant->name}}</td>
                            <td>{{$attendant->blocked === 0? "طالب نشط": "طالب محظور"}}</td>

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
