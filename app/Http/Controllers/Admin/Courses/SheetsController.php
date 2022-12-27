<?php

namespace App\Http\Controllers\Admin\Courses;

use App\Http\Controllers\Controller;
use App\Models\Attachments;
use App\Models\Lessions;
use Illuminate\Http\Request;
use App\Models\Subjects;
use App\Models\Sheets;

class SheetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }
    public function receive_sheet(Subjects $subject)
    {
        // return \Carbon\Carbon::now()->format('y-m-d h:m:s');
        $attachments = \App\Models\Attachments::where('state', 'waiting')
                        ->whereDate('final_date_receive', '<=',\Carbon\Carbon::now()->format('y-m-d h:m:s'))->get();
        foreach ($attachments as $attachment) {

            $subject = $attachment->lesson->course->subject;
            $lesson = $attachment->lesson;
            // return $lesson;
            // return $subject;
            //All Student Registered in Subject
            $subjectUsers = \App\Models\StudentSubjects::select(['user_id'])->where('state', 'active')
                ->where('subject_id' , $subject->id)->pluck('user_id')->toArray();
            $users = \App\Models\User::select(['id', 'name' , 'notification_token'])->whereIN('id' , $subjectUsers)->get();
            // return $subjectUsers;

            $subjectReceivedSheet = \App\Models\Sheets::where("lesson_id" , $lesson->id)->pluck('user_id')->toArray();
            // return $subjectReceivedSheet;

            foreach ($users as $user) {
                return $user;

                //student doesn't receive sheet
                if(!in_array($user->id, $subjectReceivedSheet)){
                    $reason = "تحذير للطالب ". $user->name ." لعدم تسليم الواجب للمادة :". $subject->title. " للدرس : ". $lesson->title;
                    if($user->notification_token){
                        return $this->send_notification([$user->notification_token], "تحذير عدم تسليم الواجب", $reason);
                        \App\Models\NotificationsSheets::create([
                            'user_id' => $user->id,
                            'lesson_id' => $lesson->id,
                            'subject_id' => $subject->id,
                            'title' => "تحذير عدم تسليم الواجب",
                            'description' => $reason,
                        ]);
                    }

                    // Register warning on student
                    $warning = \App\Models\Warnings::create([
                        'user_id' => $user->id,
                        'lesson_id' => $lesson->id,
                        'subject_id' => $subject->id,
                        'reason' => $reason,
                        'type' => 'sheet_delay'
                    ]);

                }
            }
            $attachment->state = 'reviewed';
            $attachment->save();
        }
        return $attachments;









        $subjects = Subjects::select([ 'id', 'title', 'description', 'state'])
        ->with(['courses'=>function($courses){
            return $courses->select([ 'id', 'subject_id', 'title', 'description'])
                    ->with(['lessons'=>function($lessons){
                      return $lessons->select([ 'id', 'course_id', 'title', 'description', 'video']);
                }]);
        }])->where('id', $subject->id)->get();


        return $subjects;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Lessions $lesson)
    {
        return view('courses.lessons.sheets.addSheet', compact('lesson'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Lessions $lesson)
    {
        $request->validate([
            'title' => "required|max:190",
            'description' => "nullable|max:100000",
            'final_date_receive' => "required|date",
            'file' => 'required|mimes:jpeg,png,jpg,gif,svg,pdf',
        ]);
        if ($request->hasFile('file')) {
            $fileIs = $request->file('file');
            $file = $this->upload_image($fileIs, '/uploads/attachments/');

            if ($file['success']) {
                Attachments::create([
                    'user_id' => auth()->user()->id,
                    'lesson_id' => $lesson->id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'final_date_receive' => $request->final_date_receive,
                    'file' => $file['filename'],
                ]);
                toastr()->success('تم إضافة واجب للدرس بنجاح', 'عملية ناجحة');
            } else {

                toastr()->error('حدث خطاء اثناء رفع الملف الرجاء المحاولة مرة اخرى', 'عملية فاشلة');
            }
        } else {
            toastr()->error('لم يتم إضافة واجب للدرس فشل', 'عملية فاشلة');
        }
        return redirect()->route('admin.lesson.show', ['lesson' => $lesson]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Attachments $attachment)
    {
        $lesson = Lessions::find($attachment->lesson_id);
        return view('courses.lessons.sheets.updateSheet', compact('lesson', 'attachment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attachments $attachment)
    {
        $request->validate([
            'title' => "required|max:190",
            'description' => "nullable|max:100000",
            'final_date_receive' => "required|date",
        ]);
        // return $request->all();
        if ($attachment === null) {
            toastr()->error('لم يتم إضافة واجب للدرس فشل', 'عملية فاشلة');
            return back();
        }
        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|mimes:jpeg,png,jpg,gif,svg,pdf',
            ]);
            if (\File::exists(public_path(env("STORAGE_URL") . '/uploads/attachments/' . $attachment->file))) {
                \File::delete(public_path(env("STORAGE_URL") . '/uploads/attachments/' . $attachment->file));
                if (\File::exists(public_path(env("STORAGE_URL") . '/uploads/attachments/small/' . $attachment->file))) {
                    \File::delete(public_path(env("STORAGE_URL") . '/uploads/attachments/small/' . $attachment->file));
                }
            }
            $this->remove_hub_file($attachment->file);

            $fileIs = $request->file('file');
            $file = $this->upload_image($fileIs, '/uploads/attachments/');

            if ($file['success']) {
                $attachment->file = $file['filename'];
            } else {
                return redirect()->route('admin.lesson.show', ['lesson' => $attachment->lesson_id]);
            }

            $attachment->user_id = auth()->user()->id;
            $attachment->title = $request->title;
            $attachment->description = $request->description;
            $attachment->final_date_receive = $request->final_date_receive;
            $attachment->save();
            toastr()->success('تم تعديل بيانات الواجب للدرس بنجاح', 'عملية ناجحة');
        }
        else{

            toastr()->error('حدث خطاء اثناء رفع الملف الرجاء المحاولة مرة اخرى', 'عملية فاشلة');
        }
        return redirect()->route('admin.lesson.show', ['lesson' => $attachment->lesson_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attachments $attachment)
    {
        if (\File::exists(public_path(env("STORAGE_URL") . '/uploads/attachments/' . $attachment->file))) {
            \File::delete(public_path(env("STORAGE_URL") . '/uploads/attachments/' . $attachment->file));
            if (\File::exists(public_path(env("STORAGE_URL") . '/uploads/attachments/small/' . $attachment->file))) {
                \File::delete(public_path(env("STORAGE_URL") . '/uploads/attachments/small/' . $attachment->file));
            }
        }
        $this->remove_hub_file($attachment->file);
        $attachment->delete();
        toastr()->success('تم حذف واجب الدرس بنجاح', 'عملية ناجحة');
        return back();

    }
}
