<?php

namespace App\Http\Controllers\Admin\Courses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lessions;
use App\Models\Sheets;
use App\Models\User;
use App\Models\Attendants;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class SheetsReceivedFromStudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Lessions $lesson)
    {
        // $data = DB::table('users')
        //             ->leftJoin('sheets', 'users.id', '=', 'sheets.user_id')
        //             ->leftJoin('lessons', 'lessons.id', '=', 'sheets.lesson_id')
        //             ->select(DB::raw('users.id, users.name, sheets.file, sheets.created_at'))
        //             ->where('sheets.lesson_id', '=', $lesson->id)
        //             ->paginate(25);
        $sqlData = DB::select("select u.id, u.name, s.file, s.created_at from users u, sheets s, lessons l where u.id = s.user_id and l.id = s.lesson_id and s.lesson_id = ?", [$lesson->id]);
        $data = new Paginator($sqlData, 25);

        return view('courses.lessons.sheets.studentsReceiveSheet', compact('data'));
    }
    public function absence(Lessions $lesson)
    {
        // $data = DB::table('users')
        //             ->leftJoin('sheets', 'users.id', '!=', 'sheets.user_id')
        //             ->leftJoin('lessons', 'lessons.id', '=', 'sheets.lesson_id')
        //             ->select(DB::raw('users.id, users.name, users.blocked'))
        //             ->where('sheets.lesson_id', '=', $lesson->id)
        //             ->distinct()
        //             ->paginate(25);
        $sqlData = DB::select("select distinct u.id, u.name, u.blocked from users u, sheets s, lessons l where u.id != s.user_id and l.id = s.lesson_id and s.lesson_id = ?", [$lesson->id]);
        $data = new Paginator($sqlData, 25);
        return view('courses.lessons.sheets.studentsNotReceiveSheet', compact('data'));
    }

    public function attendants(Lessions $lesson)
    {
        $data = DB::table('users')
                        ->leftJoin('attendants', 'users.id', '=', 'attendants.user_id')
                        ->select(DB::raw('users.id, users.name,  users.blocked, attendants.count,  attendants.created_at'))
                        ->where('attendants.lesson_id',  '=', $lesson->id)
                        ->distinct()
                        ->paginate(25);
        return view('courses.lessons.sheets.studentAttendantsLesson', compact('data'));
    }
    public function attendants_absence(Lessions $lesson)
    {
        $sqlData = DB::select("select u.id, u.name,  u.blocked from users u where u.id not in (select a.user_id from  attendants a, lessons l where  l.id = a.lesson_id and l.id = ?);", [$lesson->id]);
        $data = new Paginator($sqlData, 25);
        return view('courses.lessons.sheets.studentNotAttendantsLesson', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
