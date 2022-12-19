@extends('layouts.admin')
@section('content')
<div class="col-12 p-3">
    <div class="col-12 col-lg-12 p-0 ">
        <form id="validate-form" class="row" method="POST" action="{{route('admin.course.update', ['course' => $course])}}">
            @csrf
            {{-- @method("PUT") --}}

            <div class="col-12 col-lg-8 p-0 main-box">
                <div class="col-12 px-0">
                    <div class="col-12 px-3 py-3">
                        <span class="fas fa-info-circle"></span> تعديل فصل
                    </div>
                    <div class="col-12 divider" style="min-height: 2px;"></div>
                </div>
                <div class="col-12 p-3 row">

                    <div class="col-12 col-lg-6 p-2">
                        <div class="col-12">
                           اسم الفصل
                        </div>
                        <div class="col-12 pt-3">
                            <input type="text" name="title" required minlength="3"  maxlength="190" class="form-control" value="{{$course->title}}" >
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 p-2">
                        <div class="col-12">
                            نبذة عن الفصل (وصف)
                        </div>
                        <div class="col-12 pt-3">
                            <textarea  name="description" maxlength="5000" class="form-control" style="min-height:150px" value="{{$course->description}}">{{$course->description}}</textarea>
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
