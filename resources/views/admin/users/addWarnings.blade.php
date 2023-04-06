@extends('layouts.admin')
@section('content')
<div class="col-12 p-3">
	<div class="col-12 col-lg-12 p-0 ">


		<form id="validate-form" class="row" method="POST" action="{{route('admin.users.notes.save', $user)}}">
		@csrf

		<div class="col-12 col-lg-8 p-0 main-box">
			<div class="col-12 px-0">
				<div class="col-12 px-3 py-3">
				 	<span class="fas fa-info-circle"></span>	إضافة ملاحظة جديدة
				</div>
				<div class="col-12 divider" style="min-height: 2px;"></div>
                <div class="col-12 col-lg-4 py-3 px-3">
					<span class="fas fa-pages"></span> الطالب:  {{ $user->name }}
				</div>
			</div>
			<div class="col-12 p-3 row">

            <div class="col-12 col-lg-6 p-2">
				<div class="col-12">
					الملاحظة
				</div>
				<div class="col-12 pt-3">
					<textarea  name="reason" required maxlength="5000" class="form-control" style="min-height:150px">{{old('bio')}}</textarea>
				</div>
			</div>


			</div>

		</div>

		<div class="col-12 p-3">
			<button class="btn btn-success" id="submitEvaluation">حفظ</button>
		</div>
		</form>
	</div>
</div>
@endsection
