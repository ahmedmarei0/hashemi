<?php

namespace App\Console\Commands;

use App\Traits\GeneralTrait;
use Illuminate\Console\Command;

class SheetReviewed extends Command
{
    use GeneralTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sheet:review';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Warning for all students that are not receive sheet ';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get all attachment that is not reviewed
        $attachments = \App\Models\Attachments::where('state', 'waiting')
            ->whereDate('final_date_receive', '<=', \Carbon\Carbon::now()->format('y-m-d h:m:s'))->get();
        // try {
        foreach ($attachments as $attachment) {

            //Get Subject that has this lesson
            $subject = $attachment->lesson->course->subject;
            //Get Lesson to sheet
            $lesson = $attachment->lesson;
            //All Student Registered in Subject
            $subjectUsers = \App\Models\StudentSubjects::select(['user_id'])->where('state', 'active')
                ->where('subject_id', $subject->id)->pluck('user_id')->toArray();
            $users = \App\Models\User::select(['id', 'name', 'notification_token'])->whereIN('id', $subjectUsers)->get();
            // return $subjectUsers;

            // Get student that receive sheet
            $subjectReceivedSheet = \App\Models\Sheets::where("lesson_id", $lesson->id)->pluck('user_id')->toArray();
            // return $subjectReceivedSheet;

            foreach ($users as $user) {
                //student doesn't receive sheet
                if (!in_array($user->id, $subjectReceivedSheet)) {
                    $reason = "تحذير للطالب / " . $user->name . " لعدم تسليم الواجب للمادة : " . $subject->title . " للدرس : " . $lesson->title;
                    if ($user->notification_token) {
                        \App\Models\NotificationsSheets::create([
                            'user_id' => $user->id,
                            'lesson_id' => $lesson->id,
                            'subject_id' => $subject->id,
                            'title' => "تحذير عدم تسليم الواجب",
                            'description' => $reason,
                        ]);

                        $this->push_notification([$user->notification_token], "تحذير عدم تسليم الواجب", $reason);
                    }

                    // Register warning on student
                    $warning = \App\Models\Warnings::create([
                        'user_id' => $user->id,
                        'lesson_id' => $lesson->id,
                        'subject_id' => $subject->id,
                        'reason' => $reason,
                        'type' => 'sheet_delay',
                    ]);
                }
            }
            $attachment->state = 'reviewed';
            $attachment->save();
        }
        // } catch (\Exception$ex) {
        //     // return false;
        //     echo $ex->getMessage();
        // }
    }
}
