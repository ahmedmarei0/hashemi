@extends('layouts.admin')
@section('content')
<div class="col-12 p-3">
    <div class="col-12 col-lg-12 p-0 main-box">

		<div class="col-12 px-0">
			<div class="col-12 p-0 row">
				<div class="col-12 col-lg-4 py-3 px-3">
					<span class="fas fa-pages"></span> الملاحظات
				</div>

				<div class="col-12 col-lg-4 p-0">
				</div>
				<div class="col-12 col-lg-4 p-2 text-lg-end">
					<a href="{{route('admin.users.notes.add',['user' => $user])}}">
					<span class="btn btn-primary"><span class="fas fa-plus"></span> إضافة ملاحظة على الطالب</span>
					</a>
				</div>
                <div class="col-12 col-lg-4 py-3 px-3">
					<span class="fas fa-pages"></span> الطالب:  {{ $user->name }}
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
            <div class="col-12 p-0" style="min-width:1100px;">


                <table class="table table-bordered  table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>السبب</th>
                            <th>تاريخ الاضافة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($warnings as $warning)
                        <tr>
                            <td>{{$warning->id}}</td>
                            <td>{{$warning->reason}}</td>
                            <td>{{$warning->created_at}}</td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-12 p-3">
            {{$warnings->appends(request()->query())->render()}}
        </div>
    </div>
</div>
@endsection
