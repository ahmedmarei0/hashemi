<?php

namespace App\Http\Controllers\Apis\Courses;

use App\Http\Controllers\Controller;
use App\Models\Attendants;
use App\Models\Courses;
use App\Models\Lessions;
use App\Models\Message;
use App\Models\Sheets;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\Subjects;
use App\Models\StudentSubjects;

class CoursesController extends Controller
{
    use GeneralTrait;
    public function __construct()
    {
        // Verify that the user is currently logged in
        $this->middleware('auth');
    }
    public function show_subjects()
    {
        $link = env("APP_URL").env("STORAGE_URL").'/uploads/attachments/';
        /*
            All Subject
            All Courses in Subject
            All Lesson in Courses
            All Attachment in Lesson
         */

        $studentSubjects =StudentSubjects::select(['subject_id'])->where('user_id', \Auth::id())->where('state', 'active')->pluck('subject_id')->toArray();
        $subjects = Subjects::select([ 'id', 'title', 'description', 'state'])
                    ->with(['courses'=>function($courses) use($link){
                        return $courses->select([ 'id', 'subject_id', 'title', 'description'])
                                ->with(['lessons'=>function($lessons) use($link){
                                  return $lessons->select([ 'id', 'course_id', 'title', 'description', 'video'])
                                         ->with(['attachments' => function($attachments) use($link){
                                          return $attachments->select(['id', 'lesson_id','title','description',\DB::raw("concat('$link',file) as file"),'final_date_receive']);
                                    }]);
                            }]);
                    }])->where('state', 'shown')->whereIn('id', $studentSubjects)->get();
    $images = [
        env("APP_URL").env("STORAGE_URL"). "/app/mock4.jpg",
        env("APP_URL").env("STORAGE_URL"). "/app/mock2.jpg",
        env("APP_URL").env("STORAGE_URL"). "/app/mock3.jpg"
    ];
        return $this->returnSuccessMessageApi([ 'subjects' => $subjects , 'images' => $images , 'android_version' => 1 , 'ios_version' => 1]);
    }


    public function show_courses()
    {
        $courses = Courses::select(['id', 'title', 'description'])->get();
        return $this->returnSuccessMessageApi($courses);
    }

    public function show_lesson($id)
    {
        $link = env("APP_URL").env("STORAGE_URL").'/uploads/attachments/';
        $lessons = Lessions::select(['id', 'title', 'description', 'video'])->where('course_id', $id)

                    ->with(['attachments'=> function($query) use ($link){

                        return  $query->select(['id', 'lesson_id','title','description',\DB::raw("concat('$link',file) as file"),'final_date_receive'])->get();
                    }])->get();
        return $this->returnSuccessMessageApi($lessons);
    }

    public function attendance(Request $request)
    {
        $request->validate([
            'user_id' => "required|integer|exists:users,id",
            'lesson_id' => "required|integer|exists:lessons,id",
        ]);
        $attendant = Attendants::where('user_id', auth()->user()->id)
            ->where('lesson_id', $request->lesson_id)->first();
        if ($attendant === null) {
            $attendant = Attendants::create([
                'user_id' => $request->user_id,
                'lesson_id' => $request->lesson_id,
                'count' => 1,
            ]);
            return $this->returnSuccessMessageApi('attendants add successfully');
        } else {
            $attendant->count++;
            $attendant->save();
            return $this->returnSuccessMessageApi('attendants counted successfully');
        }
    }

    public function sheet(Request $request)
    {

        $request->validate([
            'user_id' => "required|integer|exists:users,id",
            'lesson_id' => "required|integer|exists:lessons,id",
            'file' => 'required|mimes:jpg,jpeg,png,pdf|max:20000',
        ]);

        $sheet = Sheets::where('lesson_id', $request->lesson_id)->where('user_id', $request->user_id)->first();
        if($sheet ===null){

            $fileIs = $request->file('file');
            $file = $this->upload_image($fileIs, '/uploads/sheets/');

            if ($file['success']) {
                Sheets::create([
                    'user_id' => $request->user_id,
                    'lesson_id' => $request->lesson_id,
                    'file' => $file['filename'],
                ]);
                return $this->returnSuccessMessageApi('sheet added successfully');
            }else{
                return $this->returnErrorApi("100014", "Failed to upload the file");
            }
        }else{

            return $this->returnErrorApi("100015", "You have already uploaded the homework for this lesson");
        }

    }

    public function add_message(Request $request)
    {
        $request->validate([
            'student_id' => "required|integer|exists:users,id",
            'content' => "required|string",
        ]);

        Message::create([
            'student_id' => $request->student_id,
            'content' => $request->content,
        ]);
        return $this->returnSuccessMessageApi('message added successfully');

    }

    public function show_message($page = null)
    {

        $messages = Message::where('student_id', auth()->user()->id)
            ->orderBy('created_at')->simplePaginate(10);
        return $this->returnSuccessMessageApi($messages);

    }

    public function contact_post(Request $request)
    {
        $request->validate([
            'user_id' => "required|integer|exists:users,id",
            "message"=>"required|min:3|max:10000",
        ]);
        $contacts = \App\Models\Contact::where("status", "PENDING")->where("user_id", auth()->id())
                                        ->whereDate("created_at", \Carbon\Carbon::now()->format('y-m-d'))->get();
        if(count($contacts) === 0){
            \App\Models\Contact::create([
                'user_id'=> auth()->id(),
                'name'=>auth()->user()->name,
                'email'=>auth()->user()->email,
                'phone'=>auth()->user()->phone,
                'message'=>/*"قادم من : ".urldecode(url()->previous())."\n\nالرسالة : ".*/$request->message
            ]);
           return $this->returnSuccessMessageApi('تم استلام رسالتك بنجاح وسنتواصل معك في أقرب وقت');
        }
        else{
            return $this->returnErrorApi("100017", "لقد ارسلت من قبل رسالة هذا اليوم للدعم سوف يتم التواصل معك");
        }
    }

    public function show_contacts(){
        $contacts =  \App\Models\Contact::with('replies')->where('user_id', auth()->id())->orderBy('id','DESC')->paginate();

        return $this->returnSuccessMessageApi($contacts);
    }
    public function show_notifications(){
        $notifications =  \App\Models\NotificationsSheets::select(['id', 'title', 'description', 'created_at'])
                            ->where('user_id', auth()->id())->orderBy('created_at','DESC')->paginate();

        return $this->returnSuccessMessageApi($notifications);
    }
    public function app_image(){
        $images = [
            env("APP_URL").env("STORAGE_URL"). "/app/mock4.jpg",
            env("APP_URL").env("STORAGE_URL"). "/app/mock2.jpg",
            env("APP_URL").env("STORAGE_URL"). "/app/mock3.jpg"
        ];

           return $this->returnSuccessMessageApi($images);
    }

}
