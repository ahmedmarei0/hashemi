<?php

namespace App\Http\Controllers\Admin\Courses;

use App\Http\Controllers\Controller;
use App\Models\Attachments;
use App\Models\Lessions;
use Illuminate\Http\Request;

class SheetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
