@extends('layouts.admin')
@section('content')
<div class="col-12 p-3">
    <div class="col-12 col-lg-12 p-0 ">
        <form id="validate-form" class="row" enctype="multipart/form-data" method="POST" action="{{route('admin.lesson.sheet.add.save', ['lesson' => $lesson])}}">
            @csrf
            <div class="col-12 col-lg-8 p-0 main-box">
                <div class="col-12 px-0">
                    <div class="col-12 px-3 py-3">
                        <span class="fas fa-info-circle"></span> إضافة واجب للدرس
                    </div>
                    <div class="col-12 divider"></div>
                    <div class="col-12 py-1 px-4">
                        <span class="fas fa-pages"></span>عنوان الدرس:  {{ $lesson->title }}
                    </div>
                    <div class="col-12 px-4">
                        <span class="fas fa-pages"></span>وصف الدرس:  {{ $lesson->description }}
                    </div>
                </div>
                <div class="col-12 p-2 row">
                    <div class="col-12 col-lg-6 p-2">
                        <div class="col-12">
                            عنوان الواجب
                        </div>
                        <div class="col-12 pt-3">
                            <input type="text" name="title" required maxlength="190" class="form-control" value="{{old('title')}}">
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 p-2"></div>
                    <div class="col-12 col-lg-6 p-2">
                        <div class="col-12">
                            نبذة عن الواجب (وصف)
                        </div>
                        <div class="col-12 pt-3">
                            <textarea  name="description" maxlength="5000" class="form-control" style="min-height:150px">{{old('description')}}</textarea>
                        </div>
                    </div>

                    <div class="col-12 p-2">
                        <div class="col-12">
                            رفع الواجب
                        </div>
                        <div class="col-12 pt-3">
                            <input type="file" name="file" required class="form-control" accept="application/pdf, image/*">
                        </div>
                        <div class="col-12 pt-3">
                        </div>
                    </div>

                    <div class="col-12 col-md-6 px-0 d-flex mb-3">
                        <div class="col-3 px-2 text-start pt-1">
                            تاريخ النهائي لتسليم الواجب
                        </div>
                        <div class="col-9 px-2" >
                            <input type="datetime-local" name="final_date_receive" value="{{old('final_date_receive')}}" class="form-control">
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
