@extends('layouts.admin')
@section('content')
<div class="col-12 p-3">
    <div class="col-12 col-lg-12 p-0 ">
        <form id="validate-form" class="row" enctype="multipart/form-data" method="POST" action="{{route('admin.course.store',['subject' => $subject])}}">
            @csrf
            <div class="col-12 col-lg-8 p-0 main-box">
                <div class="col-12 px-0">
                    <div class="col-12 px-3 py-3">
                        <span class="fas fa-info-circle"></span> إضافة فصل جديد
                    </div>
                    <div class="col-12 divider" style="min-height: 2px;"></div>
                </div>
                <div class="col-12 p-3 row">
                    <div class="col-12 col-lg-6 p-2">
                        <div class="col-12">
                            المادة الدراسية
                        </div>
                        <div class="col-12 pt-3">
                            <select class="form-control select2-select" name="subject_id" required size="1" style="height:30px;opacity: 0;">
                                @foreach($subjects as $s)
                                <option value="{{$s->id}}" @selected($subject->id == $s->id)>{{$s->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 p-2">
                        <div class="col-12">
                           اسم الفصل
                        </div>
                        <div class="col-12 pt-3">
                            <input type="text" name="title" required minlength="3"  maxlength="190" class="form-control" value="{{old('name')}}" >
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 p-2">
                        <div class="col-12">
                            نبذة عن الفصل (وصف)
                        </div>
                        <div class="col-12 pt-3">
                            <textarea  name="description" maxlength="5000" class="form-control" style="min-height:150px">{{old('description')}}</textarea>
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
