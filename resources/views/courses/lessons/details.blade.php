@extends('layouts.admin')
@section('content')
<div class="col-12 p-3">
    <div class="col-12 col-lg-12 p-0 main-box">

        <div class="col-12 px-0">
            <div class="col-12 p-0 row">
                <div class="col-12 col-lg-4 py-3 px-3">
                    <h5>
                        <span class="fas fa-pages"></span> الــدرس: {{ $lesson->title }}
                    </h5>
                </div>
                <div class="col-12 col-lg-8 p-2 text-lg-end  clearfix">
                    @permission('lessons-create')

                    <div class="float-end m-1">
                        <div class="dropdown">
                            <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                أدارة عامة
                            </a>

                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="{{ route('admin.lesson.sheet.student.show', ['lesson' => $lesson]) }}">
                                        الطلاب الذين قاموا بتسليم الواجب
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.lesson.sheet.absence.student.show', ['lesson' => $lesson]) }}">
                                        الطلاب الذين لم يسلموا الواجب
                                    </a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('admin.lesson.attendants.student.show', ['lesson' => $lesson]) }}">الطلاب الحاضرين</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.lesson.attendants.absence.student.show', ['lesson' => $lesson]) }}">الطلاب طلاب لم يحضرو</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="float-end m-1">
                        <a href="{{route('admin.lesson.sheet.add.show',$lesson)}}">
                            <span class="btn btn-primary"><span class="fas fa-plus"></span> إضافة واجب</span>
                        </a>
                    </div>
                    @endpermission
                </div>
                <div class="col-12 col-lg-4 py-3 px-3">
                    <span class="fas fa-pages"></span> وصف الدرس: {{ $lesson->description }}
                </div>
                <div class="col-12 py-3 px-3 border-t">
                    <h6>
                        <span class="fas fa-pages"></span> وصف الدرس: {{ $lesson->description }}
                    </h6>
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
                            <th>عنوان الواجب</th>
                            <th>محتوى الواجب</th>
                            <th>اخر معاد للتسليم</th>
                            <th>أضيف بواسطة</th>
                            <th>تحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attachments as $attachment)
                        <tr>
                            <td>{{$attachment->id}}</td>
                            <td>{{$attachment->title}}</td>
                            <td>{{$attachment->description}}</td>
                            <td>{{$attachment->final_date_receive}}</td>
                            <td>{{$attachment->added_by->name??""}}</td>

                            <td style="min-width: 270px;">

                                @permission('lessons-read')
                                <form action="{{route('admin.show.file')}}" method="GET">
                                    <input type="hidden" name="url"
                                        value="/uploads/attachments/{{$attachment->file }}">
                                    <button class="btn" type="submit">
                                        <span class="btn  btn-outline-primary btn-sm font-1 mx-1">
                                            <span class="fas fa-search "></span>عرض المرفق
                                        </span>
                                    </button>
                                </form>
                                <form action="{{route('admin.download.file')}}" method="GET">
                                    <input type="hidden" name="url"
                                        value="{{ '/uploads/attachments/'.$attachment->file }}">
                                    <button class="btn" type="submit">
                                        <span class="btn  btn-outline-primary btn-sm font-1 mx-1">
                                            <span class="fas fa-search "></span>تحميل المرفق
                                        </span>
                                    </button>
                                </form>
                                @endpermission

                                @permission('lessons-update')
                                <a href="{{route('admin.lesson.sheet.edit', ['attachment'=>$attachment])}}">
                                    <span class="btn  btn-outline-success btn-sm font-1 mx-1">
                                        <span class="fas fa-wrench "></span> تحكم
                                    </span>
                                </a>
                                @endpermission
                                @permission('lessons-delete')
                                <form method="POST"
                                    action="{{route('admin.lesson.sheet.delete',['attachment'=>$attachment])}}"
                                    class="d-inline-block">@csrf @method("DELETE")
                                    <button class="btn  btn-outline-danger btn-sm font-1 mx-1"
                                        onclick="var result = confirm('هل أنت متأكد من عملية الحذف ؟');if(result){}else{event.preventDefault()}">
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
            {{$attachments->appends(request()->query())->render()}}
        </div>
    </div>
</div>



@endsection
