@extends('layouts.admin')
@section('content')
<div class="col-12 p-3">
	<div class="col-12 col-lg-12 p-0 main-box">

		<div class="col-12 px-0">
			<div class="col-12 p-0 row">
				<div class="col-12 col-lg-4 py-3 px-3">
					<span class="fas fa-pages"></span> الفصول
				</div>

				<div class="col-12 col-lg-6 p-2 text-lg-end">
					@permission('courses-create')
					<a href="{{route('admin.course.create', ['subject'=> $subject])}}">
					<span class="btn btn-primary"><span class="fas fa-plus"></span> إضافة فصل جديد</span>
					</a>
					@endpermission
                    <a href="{{route('admin.student.sheets.receive', ['subject'=> $subject])}}">
                        <span class="btn btn-primary"><span class="fas fa-users"></span> طالب لم يسلموا الواجب</span>
                    </a>
				</div>
			</div>
		</div>

		<div class="col-12 py-2 px-2 row">
			<div class="col-12 col-lg-4 p-2">
				<form method="GET">
					<input type="text" name="q" class="form-control" placeholder="بحث ... " value="{{request()->get('q')}}">
				</form>
			</div>
		</div>
		<div class="col-12 p-3" style="overflow:auto">
			<div class="col-12 p-0" style="min-width:1100px;">


			<table class="table table-bordered  table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>العنوان</th>
						<th>المحتوى</th>
						<th>أضيف بواسطة</th>
						<th>تحكم</th>
					</tr>
				</thead>
				<tbody>
					@foreach($courses as $course)
					<tr>
						<td>{{$course->id}}</td>
						<td>{{$course->title}}</td>
						<td>{{$course->description}}</td>
						<td>{{$course->added_by->name??""}}</td>

						<td style="width: 270px;">

							@permission('courses-read')
							<a href="{{route('admin.course.lesson.show',$course)}}">
								<span class="btn  btn-outline-primary btn-sm font-1 mx-1">
									<span class="fas fa-search "></span> عرض
								</span>
							</a>
							@endpermission

							@permission('courses-update')
							<a href="{{route('admin.course.edit',$course)}}">
								<span class="btn  btn-outline-success btn-sm font-1 mx-1">
									<span class="fas fa-wrench "></span> تحكم
								</span>
							</a>
							@endpermission
							@permission('courses-delete')
							<form method="POST" action="{{route('admin.course.destroy',['course' => $course])}}" class="d-inline-block">@csrf @method("DELETE")
								<button class="btn  btn-outline-danger btn-sm font-1 mx-1" onclick="var result = confirm('هل أنت متأكد من عملية الحذف ؟');if(result){}else{event.preventDefault()}">
									<span class="fas fa-trash "></span> حذف
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
			{{$courses->appends(request()->query())->render()}}
		</div>
	</div>
</div>
@endsection

