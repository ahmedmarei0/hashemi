@extends('layouts.admin')
@section('content')
<div class="col-12 p-3">
	<div class="col-12 col-lg-12 p-0 main-box">

		<div class="col-12 px-0">
			<div class="col-12 p-0 row">
				<div class="col-12 col-lg-4 py-3 px-3">
					<span class="fas fa-pages"></span> عرض المواد الدراسية
				</div>

				<div class="col-12 col-lg-4 p-2 text-lg-end">
					@permission('courses-create')
					<a href="{{route('admin.subject.create')}}">
					<span class="btn btn-primary"><span class="fas fa-plus"></span> إضافة مادة دراسية جديد</span>
					</a>
					@endpermission
				</div>
			</div>
			<div class="col-12 divider" style="min-height: 2px;"></div>
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
					@foreach($subjects as $subject)
					<tr>
						<td>{{$subject->id}}</td>
						<td>{{$subject->title}}</td>
						<td>{{$subject->description}}</td>
						<td>{{$subject->added_by->name??""}}</td>

						<td style="width: 270px;">

							@permission('courses-read')
							<a href="{{route('admin.course.index',['subject'=>$subject])}}">
								<span class="btn  btn-outline-primary btn-sm font-1 mx-1">
									<span class="fas fa-search "></span> عرض
								</span>
							</a>
							@endpermission

							@permission('courses-update')
							<a href="{{route('admin.subject.edit',$subject)}}">
								<span class="btn  btn-outline-success btn-sm font-1 mx-1">
									<span class="fas fa-wrench "></span> تحكم
								</span>
							</a>
							@endpermission
							@permission('courses-delete')
                            @if ($subject->state =='shown')
                            <form method="POST" action="{{route('admin.subject.destroy',['subject' => $subject])}}" class="d-inline-block">@csrf @method("DELETE")
								<button class="btn  btn-outline-danger btn-sm font-1 m-1" onclick="var result = confirm('لن يتمكن أى مستخدم للتطبيق من روية هذه أى دروس او فصول هذه المادة ؟');if(result){}else{event.preventDefault()}">
									<span class="fas fa-trash "></span>أخفاء من التطبيق
								</button>
							</form>
                            @else
                            <form method="POST" action="{{route('admin.subject.destroy',['subject' => $subject])}}" class="d-inline-block">@csrf @method("DELETE")
								<button class="btn  btn-outline-success btn-sm font-1 m-1" onclick="var result = confirm('سوف يتمكن مستخدمى التطبيق من روئة جمع الدروس والفصول فى هذه المادة ؟');if(result){}else{event.preventDefault()}">
									<span class="fas fa-trash "></span>أظهار فى التطبيق
								</button>
							</form>
                            @endif

							@endpermission
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			</div>
		</div>
		<div class="col-12 p-3">
			{{$subjects->appends(request()->query())->render()}}
		</div>
	</div>
</div>
@endsection

