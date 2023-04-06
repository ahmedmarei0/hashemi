@extends('layouts.admin')
@section('content')
<div class="col-12 p-3">
    <div class="col-12 col-lg-12 p-0 ">
        <form id="validate-form" class="row" enctype="multipart/form-data" method="POST" action="{{route('admin.lesson.update', $lesson)}}">
            @csrf
            <div class="col-12 col-lg-8 p-0 main-box">
                <div class="col-12 px-0">
                    <div class="col-12 px-3 py-3">
                        <span class="fas fa-info-circle"></span> تعديل بيانات درس
                    </div>
                    <div class="col-12 divider" style="min-height: 2px;"></div>
                </div>
                <div class="col-12 p-3 row">

                    <div class="col-12 col-lg-6 p-2">
                        <div class="col-12">
                            الفصل
                        </div>
                        <div class="col-12 pt-3">
                            <select class="form-control select2-select" name="course_id" required size="1" style="height:30px;opacity: 0;">
                                @foreach($courses as $c)
                                <option value="{{$c->id}}" @selected($lesson->course_id == $c->id)>{{$c->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 p-2">
                        <div class="col-12">
                            عنوان الدرس
                        </div>
                        <div class="col-12 pt-3">
                            <input type="text" name="title" value={{ $lesson->title }} maxlength="190" class="form-control">
                        </div>
                    </div>

                    <div class="col-12 p-2">
                        <div class="col-12">
                           رابط الدرس
                        </div>
                        <div class="col-12 pt-3">
                            <input type="text" name="video" value="{{ $lesson->video }}" required maxlength="190" class="form-control">
                        </div>
                    </div>


                    <div class="col-12 col-lg-6 p-2">
                        <div class="col-12">
                            نبذة عن الفصل (وصف)
                        </div>
                        <div class="col-12 pt-3">
                            <textarea  name="description" maxlength="5000" class="form-control" style="min-height:150px">{{$lesson->description}}</textarea>
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
