<?php

namespace App\Http\Controllers\Admin\Courses;

use App\Http\Controllers\Controller;
use App\Models\Courses;
use Illuminate\Http\Request;
use App\Models\Subjects;

class CoursesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Subjects $subject)
    {
        $courses = Courses::where('subject_id', $subject->id)->where(function ($q) use ($request) {
            if ($request->id != null) {
                $q->where('id', $request->id);
            }

            if ($request->q != null) {
                $q->where('title', 'LIKE', '%' . $request->q . '%')->orWhere('description', 'LIKE', '%' . $request->q . '%');
            }

        })->with('added_by:id,name')->paginate();
        return view('courses.course.showCoures', compact('courses', 'subject'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Subjects $subject)
    {
     if($subject !== null){
            $subjects = Subjects::all();
            return view('courses.course.addCorse', compact('subjects','subject'));
        }
        else{
            toastr()->success('خطاء فى أضافة درس','عملية أضافة فشلة');
            return back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Subjects $subject)
    {

        $request->validate([
            'subject_id'=>"required|integer",
            'subject_id.*'=>"required|exists:subjects,id",
            'title'=>"required|string|max:190",
            'description'=>"nullable|max:100000"
        ]);
        Courses::create([
            'subject_id' => $request->subject_id,
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->user()->id,
        ]);
        return redirect()->route('admin.course.index',['subject' => $request->subject_id]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Courses $course)
    {

        if ($course !== null) {
            $lessons = \App\Models\Lessions::where('course_id', $course->id)->paginate(25);
            return view('courses.lessons.showLessons', compact('course', 'lessons'));
        } else {
            return redirect()->route('admin.course.index');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Courses $course)
    {
        return view('courses.course.updateCourse', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Courses $course)
    {
        $request->validate([
            'title'=>"required|string|max:190",
            'description'=>"nullable|max:100000"
        ]);
        $course->update([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->user()->id,
        ]);
        return redirect()->route('admin.course.index', ['subject' => $course->subject_id]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Courses $course)
    {
        $lessons = $course->lessons;
        foreach ($lessons as $lesson) {
            $lesson->attendants()->delete();

            $sheets =$lesson->sheets;
            foreach ($sheets as $sheet) {
                $this->delete_file("sheets" ,$sheet->file);
            }
            $lesson->sheets()->delete();

            $attachments =$lesson->attachments;
            foreach ($attachments as $attachment) {
                $this->delete_file('attachments' ,$attachment->file);
            }
            $lesson->attachments()->delete();
            $lesson->delete();
        }
        $subject_id = $course->subject_id;
        $course->delete();
        toastr()->success('تم حذف المحاضرة بنجاح','عملية ناجحة');
        return redirect()->route('admin.course.index',  ['subject' => $subject_id]);
    }
}
