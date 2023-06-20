<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\StudentSubjects;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use App\Models\Warnings;

class UserController extends Controller
{


    public function __construct()
    {
        $this->middleware('permission:users-create', ['only' => ['create','store']]);
        $this->middleware('permission:users-read',   ['only' => ['show', 'index']]);
        $this->middleware('permission:users-update',   ['only' => ['edit','update']]);
        $this->middleware('permission:users-delete',   ['only' => ['delete']]);
    }

    public function show_notes(User $user){
        $warnings =  new Paginator($user->warning, 25);
        return view('admin.users.showWarnings',compact('user' , 'warnings'));

    }
    public function add_notes(User $user){
        return view('admin.users.addWarnings',compact('user'));

    }
    public function save_notes(Request $request, User $user){
        $request->validate([
            'reason' =>"required|max:191",
        ]);
        Warnings::create([
            'user_id' => $user->id,
            'reason' => $request->reason,
            'type' => 'admin_reason'
        ]);
        return redirect()->route('admin.users.notes.show', $user);

    }


    public function index(Request $request)
    {

        $users =  User::where(function($q)use($request){
            if($request->id!=null)
                $q->where('id',$request->id);
            if($request->q!=null)
                $q->where('name','LIKE','%'.$request->q.'%')->orWhere('phone','LIKE','%'.$request->q.'%')->orWhere('email','LIKE','%'.$request->q.'%');
        })->orderBy('id','DESC')->paginate();

        $type = null;
        return view('admin.users.index',compact('users' , 'type'));

    }
    public function show_users(Request $request, $type=null)
    {
        $users = [];
        if($type == 'active_student'){
            $users =  User::where('blocked', '0')->where('power',"USER")->where(function($q)use($request){
                if($request->id!=null)
                    $q->where('id',$request->id);
                if($request->q!=null)
                    $q->where('name','LIKE','%'.$request->q.'%')->orWhere('phone','LIKE','%'.$request->q.'%')->orWhere('email','LIKE','%'.$request->q.'%');
            })->orderBy('id','DESC')->paginate();
        }
        else if($type == 'blocked_student'){
            $users =  User::where('blocked' , '!=', '0')->where('power',"USER")->where(function($q)use($request){
                if($request->id!=null)
                    $q->where('id',$request->id);
                if($request->q!=null)
                    $q->where('name','LIKE','%'.$request->q.'%')->orWhere('phone','LIKE','%'.$request->q.'%')->orWhere('email','LIKE','%'.$request->q.'%');
            })->orderBy('id','DESC')->paginate();
        }
        else if($type == 'active_user'){
            $users =  User::where('blocked', '0')->where('power',"ADMIN")->where(function($q)use($request){
                if($request->id!=null)
                    $q->where('id',$request->id);
                if($request->q!=null)
                    $q->where('name','LIKE','%'.$request->q.'%')->orWhere('phone','LIKE','%'.$request->q.'%')->orWhere('email','LIKE','%'.$request->q.'%');
            })->orderBy('id','DESC')->paginate();
        }
        else if($type == 'blocked_user'){
            $users =  User::where('blocked' , '!=', 0)->where('power',"ADMIN")->where(function($q)use($request){
                if($request->id!=null)
                    $q->where('id',$request->id);
                if($request->q!=null)
                    $q->where('name','LIKE','%'.$request->q.'%')->orWhere('phone','LIKE','%'.$request->q.'%')->orWhere('email','LIKE','%'.$request->q.'%');
            })->orderBy('id','DESC')->paginate();
        }
        else{

            $users =  User::where(function($q)use($request){
                if($request->id!=null)
                    $q->where('id',$request->id);
                if($request->q!=null)
                    $q->where('name','LIKE','%'.$request->q.'%')->orWhere('phone','LIKE','%'.$request->q.'%')->orWhere('email','LIKE','%'.$request->q.'%');
            })->orderBy('id','DESC')->paginate();
        }


        return view('admin.users.index',compact('users', 'type'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = \App\Models\Role::get();
        $subjects = \App\Models\Subjects::get();

        return view('admin.users.create',compact('roles', 'subjects'));
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
            'name'=>"nullable|max:190",
            'username'=>"nullable|max:190|unique:users,username",
            'phone'=>"nullable|max:190",
            'bio'=>"nullable|max:5000",
            'blocked'=>"required|in:0,1",
            'email'=>"nullable|unique:users,email",
            'password'=>"required|min:8|max:190",
            'subjects'=>"required|array",
            'subjects.*'=>"required|exists:subjects,id",
        ]);
        $user = User::create([
            "name"=>$request->name,
            "username"=>$request->username,
            "phone"=>$request->phone,
            "bio"=>$request->bio,
            "blocked"=>$request->blocked,
            "email"=>$request->email,
            "password"=>\Hash::make($request->password),
        ]);
        if($request->roles != null){
            $request->validate([
                'roles'=>"required|array",
                'roles.*'=>"required|exists:roles,id",
            ]);
            $user->syncRoles($request->roles);
            $user->syncPermissions(DB::table('permission_role')->whereIn('role_id',$request->roles)->pluck('permission_id'));
        }

        foreach ($request->subjects as $subject) {
            StudentSubjects::create([
                'user_id'=> $user->id,
                'subject_id'=> $subject,
                'state'=> 'active'
            ]);
        }
        if($request->hasFile('avatar')){

            $fileIs = $request->file('avatar');
            $file = $this->upload_image($fileIs, '/uploads/users/');

            if ($file['success']) {
                 $user->update(['avatar'=>$file['filename']]);
            }
            else{
                toastr()->error('حدث خطاء اثناء رفع الملف الرجاء المحاولة مرة اخرى', 'عملية فاشلة');
                return redirect()->route('admin.users.index');
            }
        }

        toastr()->success('تم إضافة المستخدم بنجاح','عملية ناجحة');
        return redirect()->route('admin.users.index');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('admin.users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = \App\Models\Role::get();
        $subjects = \App\Models\Subjects::get();

        $StudentSubjects =StudentSubjects::where('user_id', $user->id)->where('state', 'active')->get();
        // return $StudentSubjects;
        foreach ($StudentSubjects as $ss) {
            for ($i=0; $i < count($subjects) ; $i++) {
                if($ss->subject_id == $subjects[$i]->id){
                    $subjects[$i]->selected = true;
                }
            }
         }
        // return $subjects;
        return view('admin.users.edit',compact('user','roles', 'subjects', 'StudentSubjects'));
    }


    function checkArray($myArray, $subject_id) {
        foreach ($myArray as $element) {
            if ($element->subject_id == $subject_id){
                return true;
            }
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'=>"nullable|max:190",
            'phone'=>"nullable|max:190",
            'bio'=>"nullable|max:5000",
            'blocked'=>"required|in:0,1",
            'email'=>"nullable|unique:users,email,".$user->id,
            'username'=>"string|max:190|unique:users,username,".$user->id,
            'password'=>"nullable|min:8|max:190"
        ]);
        // return $request->all();
        $StudentSubjects =StudentSubjects::where('user_id', $user->id)->where('state', 'active')->get();

        if($request->subjects != null){
            foreach ($request->subjects as $subject) {
                if($this->checkArray($StudentSubjects, $subject) === false){
                    StudentSubjects::create([
                        'user_id'=> $user->id,
                        'subject_id'=> $subject,
                        'state'=> 'active'
                    ]);
                }
             }



             foreach ($StudentSubjects as $ss) {
                if(in_array($ss->subject_id,$request->subjects) === false){
                    $ss->state = 'expired';
                    $ss->expired_date = \Carbon\Carbon::now();
                    $ss->save();
                }
                // else if($this->checkArray($StudentSubjects, $subject) && $ss->state = 'blocked' ){
                //     $ss->state = 'active';
                //     $ss->save();
                // }
             }
        }else{
            foreach ($StudentSubjects as $ss) {
                if($ss->state == 'active'){
                    $ss->state = 'expired';
                    $ss->expired_date = \Carbon\Carbon::now();
                    $ss->save();
                }
            }
        }
        $user->update([
            "name"=>$request->name,
            "phone"=>$request->phone,
            "bio"=>$request->bio,
            "blocked"=>$request->blocked,
            "email"=>$request->email,
            "username"=>$request->username,

        ]);
        if(auth()->user()->isAbleTo('user-roles-update') && $request->has('roles')){
            $request->validate([
                'roles'=>"required|array",
                'roles.*'=>"required|exists:roles,id",
            ]);
            $user->syncRoles($request->roles);
            $user->syncPermissions(DB::table('permission_role')->whereIn('role_id',$request->roles)->pluck('permission_id'));
        }

        if($request->password!=null){
            $user->update([
                "password"=>\Hash::make($request->password)
            ]);
        }
        if($request->hasFile('avatar')){
            // $file = $this->store_file([
            //     'source'=>$request->avatar,
            //     'validation'=>"image",
            //     'path_to_save'=>'/uploads/users/',
            //     'type'=>'USER',
            //     'user_id'=>\Auth::user()->id,
            //     'resize'=>[500,1000],
            //     'small_path'=>'small/',
            //     'visibility'=>'PUBLIC',
            //     'file_system_type'=>env('FILESYSTEM_DRIVER'),
            //     /*'watermark'=>true,*/
            //     'optimize'=>true
            // ]);
            $fileIs = $request->file('avatar');
            $file = $this->upload_image($fileIs, '/uploads/users/');

            if ($file['success']) {
                 $user->update(['avatar'=>$file['filename']]);
            }
            else{
                toastr()->error('حدث خطاء اثناء رفع الملف الرجاء المحاولة مرة اخرى', 'عملية فاشلة');
                return redirect()->route('admin.users.index');
            }
        }

        toastr()->success('تم تحديث المستخدم بنجاح','عملية ناجحة');
        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // return auth()->user()->id == $user->id ? "true":"false";
        if(!auth()->user()->isAbleTo('users-delete'))abort(403);
        if(auth()->user()->id == $user->id){
            toastr()->success('لا يمكنك حذف المستخدم الرئيسى للنظام','عملية فاشلة');
        }
        else{
            $user->delete();
            toastr()->success('تم حذف المستخدم بنجاح','عملية ناجحة');
        }
        return redirect()->route('admin.users.index');
    }
}
