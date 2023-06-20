<?php

namespace App\Http\Controllers\Admin\Courses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subjects;
use Illuminate\Pagination\Paginator;
use App\Models\Courses;
use App\Models\User;

class SubjectsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:subject-create', ['only' => ['create','store']]);
        $this->middleware('permission:subject-read',   ['only' => ['show', 'index']]);
        $this->middleware('permission:subject-update',   ['only' => ['edit','update']]);
        $this->middleware('permission:subject-delete',   ['only' => ['delete']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $subjects = Subjects::where(function ($q) use ($request) {
            if ($request->id != null) {
                $q->where('id', $request->id);
            }
            if ($request->q != null) {
                $q->where('title', 'LIKE', '%' . $request->q . '%')->orWhere('description', 'LIKE', '%' . $request->q . '%');
            }

        })->with('added_by:id,name')->paginate();
        // $StudentSubjects =StudentSubjects::where('user_id', $user->id)->where('state', 'active')->get();

        // $user = User::with(['subjects' => function ($q) use ($request) {
        //     if ($request->id != null) {
        //         $q->where('id', $request->id)->paginate();
        //     }
        //     if ($request->q != null) {
        //         $q->where('title', 'LIKE', '%' . $request->q . '%')->orWhere('description', 'LIKE', '%' . $request->q . '%')->paginate();
        //     }

        // }])->find(\Auth::id());
        // $subjects = [];
        // if($user->subjects != null)
        // $subjects = new Paginator($user->subjects, 25);
        // return $subjects;

        return view('courses.subject.showSubjects', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('courses.subject.addSubject');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>"required|string|max:190",
            'description'=>"nullable|max:100000"
        ]);
        $subject = Subjects::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->user()->id,
        ]);
        \App\Models\StudentSubjects::create([
            'user_id'=> \Auth::id(),
            'subject_id'=> $subject->id,
            'state'=> 'active'
        ]);
        return redirect()->route('admin.subject.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Subjects $subject =null)
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Subjects $subject)
    {
        return view('courses.subject.updateSubjects', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subjects $subject)
    {
        $request->validate([
            'title'=>"required|string|max:190",
            'description'=>"nullable|max:100000"
        ]);
        $subject->update([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->user()->id,
        ]);
        return redirect()->route('admin.subject.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subjects $subject)
    {
        $subject->state = $subject->state =='shown' ? 'hidden': 'shown';
        $subject->save();
        return redirect()->route('admin.subject.index');
    }
}
