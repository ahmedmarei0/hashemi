<?php

namespace App\Http\Controllers\Admin\Courses;

use App\Http\Controllers\Controller;
use App\Models\Attachments;
use App\Models\Courses;
use App\Models\Lessions;
use App\Models\Sheets;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;

class LessonsController extends Controller
{

    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return $id;
/// this function in courseController => show function
        $course = Courses::with('lessons')->where('id', $id)->first();
        return view('courses.lessons.showLessons', compact('course'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $course = Courses::find($id);
        if ($course !== null) {
            $courses = Courses::all();
            return view('courses.lessons.addLesson', compact('courses', 'course'));
        } else {
            toastr()->success('خطاء فى أضافة درس', 'عملية أضافة فشلة');
            return back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $this->push_notification(["eGJDVVwtQraF1OBiAbjN4n:APA91bH6BDp3qgbC-d95jSf7aLW-WBFd8-0H6d2EHlGco8boGSJfkA-KVEoUmOzlsf9JQtrTqaORGGLkgyh6R2WW3oJe3wXUoiuNG1l37O-bVEgC8wpETUgVHrvYVhjvtXywAh1vklXl"], "تحذير عدم تسليم الواجب", "شسيشيسب شيس");

        // return $request->all();
        $request->validate([
            'course_id' => "required|integer",
            'course_id.*' => "required|exists:courses,id",
            'title' => "required|max:190",
            'video' => "required|max:190",
            'description' => "nullable|max:100000",
        ]);

        $lesson = Lessions::create([
            'user_id' => auth()->user()->id,
            'course_id' => $request->course_id,
            "title" => $request->title,
            "description" => $request->description,
            "video" => $request->video,
        ]);
        toastr()->success('تم إضافة الدرس بنجاح', 'عملية ناجحة');
        $course = Courses::find($request->course_id);
        $subject = \App\Models\Subjects::find($course->subject_id);
        if ($subject != null) {
            $users = $subject->students;
            $reason = "تم أضافة درس  فى مادة " . $subject->title . " الفصل " . $course->title . " درس " . $lesson->title;
            foreach ($users as $user) {
                if ($user->notification_token) {
                    \App\Models\NotificationsSheets::create([
                        'user_id' => $user->id,
                        'lesson_id' => $lesson->id,
                        'subject_id' => $subject->id,
                        'title' => "أضافة درس  فى مادة " . $subject->title,
                        'description' => $reason,
                    ]);

                    $this->push_notification([$user->notification_token], "أضافة درس  فى مادة " . $subject->title, $reason);
                }
            }
        }
        return redirect()->route('admin.course.lesson.show', ['course' => $request->course_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Lessions $lesson)
    {
        // homeworks
        $attachments = Attachments::where('lesson_id', $lesson->id)->with('added_by:id,name')->paginate(25);

        return view('courses.lessons.details', compact('lesson', 'attachments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $lesson = Lessions::find($id);
        if ($lesson !== null) {
            $courses = Courses::all();
            return view('courses.lessons.updateLesson', compact('courses', 'lesson'));
        } else {
            toastr()->success('خطاء فى تعديل بيانات الدرس', 'عملية تعديل فشلة');
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lessions $lesson)
    {
        $request->validate([
            'course_id' => "required|integer",
            'course_id.*' => "required|exists:courses,id",
            'title' => "required|max:190",
            'video' => "required|max:190",
            'description' => "nullable|max:100000",
        ]);
        $lesson->update([
            'user_id' => auth()->user()->id,
            'course_id' => $request->course_id,
            "title" => $request->title,
            "description" => $request->description,
            "video" => $request->video,
        ]);
        toastr()->success('تم تعديل الدرس بنجاح', 'عملية ناجحة');
        return redirect()->route('admin.course.lesson.show', ['course' => $request->course_id]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lessions $lesson)
    {
        $lesson->attendants()->delete();

        $sheets = $lesson->sheets;
        foreach ($sheets as $sheet) {
            $this->delete_file("sheets", $sheet->file);
        }
        $lesson->sheets()->delete();

        $attachments = $lesson->attachments;
        foreach ($attachments as $attachment) {
            $this->delete_file('attachments', $attachment->file);
        }
        $lesson->attachments()->delete();
        $lesson->delete();
        toastr()->success('تم حذف الدرس بنجاح', 'عملية ناجحة');
        return redirect()->route('admin.course.lesson.show', ['course' => $lesson->course_id]);
    }
}
