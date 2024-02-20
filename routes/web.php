<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleCommentController;
use App\Http\Controllers\SiteMapController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RedirectionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\TrafficsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuLinkController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ContactReplyController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserPermissionController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PluginController;


Auth::routes();

Route::get('/', function () {return view('front.index');})->name('home');



Route::get('/test/share' , function (){
    /* To Get Data From All function name test In Modules Active Only */
    $data =  (new \App\Units\ModulesUnit())->ShareDataModules('Test');
    /* To Pass Arguments If Function has Parameters */
    $data2 =  (new \App\Units\ModulesUnit())->ShareDataModules('Test2' , 5);

    return $data2;
});


Route::prefix('admin')->middleware(['auth','ActiveAccount'])->name('admin.')->group(function () {

    Route::get('/',[AdminController::class,'index'])->name('index');

    Route::middleware('auth')->group(function () {

        Route::get('show/file', [App\Http\Controllers\Controller::class , 'show_file'])->name('show.file');
        Route::get('download/file', [App\Http\Controllers\Controller::class , 'download_file'])->name('download.file');

        Route::resource('subject', App\Http\Controllers\Admin\Courses\SubjectsController::class);

        // Route::resource('course', App\Http\Controllers\Admin\Courses\CoursesController::class);

        Route::get('course/{subject}', [App\Http\Controllers\Admin\Courses\CoursesController::class, 'index'])->name('course.index');
        Route::get('course/create/{subject?}', [App\Http\Controllers\Admin\Courses\CoursesController::class, 'create'])->name('course.create');

        Route::get('course/lesson/{course}', [App\Http\Controllers\Admin\Courses\CoursesController::class, 'show'])->name('course.lesson.show');

        Route::post('course/{subject}', [App\Http\Controllers\Admin\Courses\CoursesController::class, 'store'])->name('course.store');
        Route::delete('course/{course}', [App\Http\Controllers\Admin\Courses\CoursesController::class, 'destroy'])->name('course.destroy');
        Route::get('course/{course}/edit', [App\Http\Controllers\Admin\Courses\CoursesController::class, 'edit'])->name('course.edit');
        Route::post('course/update/{course}', [App\Http\Controllers\Admin\Courses\CoursesController::class, 'update'])->name('course.update');

        // Route::resource('lesson', App\Http\Controllers\Admin\Courses\LessonsController::class);
        Route::get('lesson/show/{lesson}', [App\Http\Controllers\Admin\Courses\LessonsController::class , 'show'])->name('lesson.show');
        Route::get('lesson/add/{id}', [App\Http\Controllers\Admin\Courses\LessonsController::class , 'create'])->name('lesson.add.show');
        Route::post('lesson/add', [App\Http\Controllers\Admin\Courses\LessonsController::class , 'store'])->name('lesson.add');
        Route::get('lesson/{lesson}/edit', [App\Http\Controllers\Admin\Courses\LessonsController::class , 'edit'])->name('lesson.edit');
        Route::post('lesson/{lesson}', [App\Http\Controllers\Admin\Courses\LessonsController::class , 'update'])->name('lesson.update');
        Route::delete('lesson/delete/{lesson}', [App\Http\Controllers\Admin\Courses\LessonsController::class , 'destroy'])->name('lesson.destroy');

        Route::get('lesson/sheet/add/{lesson}', [App\Http\Controllers\Admin\Courses\SheetsController::class , 'create'])->name('lesson.sheet.add.show');
        Route::post('lesson/sheet/add/{lesson}', [App\Http\Controllers\Admin\Courses\SheetsController::class , 'store'])->name('lesson.sheet.add.save');
        Route::get('lesson/sheet/{attachment}/edit', [App\Http\Controllers\Admin\Courses\SheetsController::class , 'edit'])->name('lesson.sheet.edit');
        Route::post('lesson/sheet/{attachment}', [App\Http\Controllers\Admin\Courses\SheetsController::class , 'update'])->name('lesson.sheet.update');
        Route::delete('lesson/sheet/delete/{attachment}', [App\Http\Controllers\Admin\Courses\SheetsController::class , 'destroy'])->name('lesson.sheet.delete');

        Route::get('student/sheets/receive/{subject}', [App\Http\Controllers\Admin\Courses\SheetsController::class , 'receive_sheet'])->name('student.sheets.receive');
        Route::post('student/block/{user}', [App\Http\Controllers\Admin\Courses\SheetsController::class , 'user_block'])->name('users.block');

        Route::get('lesson/sheet/student/show/{lesson}', [App\Http\Controllers\Admin\Courses\SheetsReceivedFromStudentsController::class , 'index'])->name('lesson.sheet.student.show');
        Route::get('lesson/sheet/student/absence/show/{lesson}', [App\Http\Controllers\Admin\Courses\SheetsReceivedFromStudentsController::class , 'absence'])->name('lesson.sheet.absence.student.show');

        Route::get('lesson/sheet/student/attendants/show/{lesson}', [App\Http\Controllers\Admin\Courses\SheetsReceivedFromStudentsController::class , 'attendants'])->name('lesson.attendants.student.show');
        Route::get('lesson/sheet/student/attendants/absence/show/{lesson}', [App\Http\Controllers\Admin\Courses\SheetsReceivedFromStudentsController::class , 'attendants_absence'])->name('lesson.attendants.absence.student.show');

        Route::get('users/notes/show/{user}', [UserController::class , 'show_notes'])->name('users.notes.show');
        Route::get('users/notes/add/{user}', [UserController::class , 'add_notes'])->name('users.notes.add');
        Route::post('users/notes/save/{user}', [UserController::class , 'save_notes'])->name('users.notes.save');

        Route::get('support/show/last/sent', [UserController::class , 'show_support'])->name('support.show.last.sent');


        //Route::get('countries',function(){return dd(config()->get('countries'));});
        Route::resource('announcements',AnnouncementController::class);
        Route::resource('files',FileController::class);
        Route::post('contacts/resolve',[ContactController::class,'resolve'])->name('contacts.resolve');
        Route::resource('contacts',ContactController::class);
        Route::resource('menus',MenuController::class);
        Route::get('user/details/{user}', [UserController::class , "show"])->name('user.details.show');
        Route::resource('users',UserController::class);
        Route::get('users/show/{type}',[UserController::class , 'show_users'])->name('users.filter.show');
        Route::resource('roles',RoleController::class);



        Route::get('user-roles/{user}',[UserRoleController::class,'index'])->name('users.roles.index');
        Route::put('user-roles/{user}',[UserRoleController::class,'update'])->name('users.roles.update');

        Route::resource('contact-replies',ContactReplyController::class);
        // الإسئلة المتكررة
        // Route::post('faqs/order',[FaqController::class,'order'])->name('faqs.order');
        // Route::resource('faqs',FaqController::class);
        // Route::post('menu-links/get-type',[MenuLinkController::class,'getType'])->name('menu-links.get-type');
        // Route::post('menu-links/order',[MenuLinkController::class,'order'])->name('menu-links.order');
        // Route::resource('menu-links',MenuLinkController::class);
        // Route::resource('categories',CategoryController::class);
        // Route::resource('redirections',RedirectionController::class);
        Route::get('traffics',[TrafficsController::class,'index'])->name('traffics.index');
        Route::get('traffics/{traffic}/logs',[TrafficsController::class,'logs'])->name('traffics.logs');
        Route::get('error-reports',[TrafficsController::class,'error_reports'])->name('traffics.error-reports');
        Route::get('error-reports/{report}',[TrafficsController::class,'error_report'])->name('traffics.error-report');
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/',[SettingController::class,'index'])->name('index');
            Route::put('/{settings}/update',[SettingController::class,'update'])->name('update');
        });
    });

    Route::prefix('upload')->name('upload.')->group(function(){
        Route::post('/image',[HelperController::class,'upload_image'])->name('image');
        Route::post('/file',[HelperController::class,'upload_file'])->name('file');
        Route::post('/remove-file',[HelperController::class,'remove_files'])->name('remove-file');
    });


    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/',[ProfileController::class,'index'])->name('index');
        Route::get('/edit',[ProfileController::class,'edit'])->name('edit');
        Route::put('/update',[ProfileController::class,'update'])->name('update');
        Route::put('/update-password',[ProfileController::class,'update_password'])->name('update-password');
        Route::put('/update-email',[ProfileController::class,'update_email'])->name('update-email');
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',[NotificationsController::class,'index'])->name('index');
        Route::get('/ajax',[NotificationsController::class,'ajax'])->name('ajax');
        Route::post('/see',[NotificationsController::class,'see'])->name('see');
        Route::get('/create',[NotificationsController::class,'create'])->name('create');
        Route::post('/create',[NotificationsController::class,'store'])->name('store');
    });

});


Route::get('blocked',[HelperController::class,'blocked_user'])->name('blocked');
Route::get('robots.txt',[HelperController::class,'robots']);
Route::get('manifest.json',[HelperController::class,'manifest'])->name('manifest');
Route::get('sitemap.xml',[SiteMapController::class,'sitemap']);
Route::get('sitemaps/links',[SiteMapController::class,'custom_links']);
Route::get('sitemaps/{name}/{page}/sitemap.xml',[SiteMapController::class,'viewer']);


// Route::view('contact','front.pages.contact')->name('contact');
// Route::get('page/{page}',[FrontController::class,'page'])->name('page.show');
// Route::get('tag/{tag}',[FrontController::class,'tag'])->name('tag.show');
// Route::get('category/{category}',[FrontController::class,'category'])->name('category.show');
// Route::get('article/{article}',[FrontController::class,'article'])->name('article.show');
// Route::get('blog',[FrontController::class,'blog'])->name('blog');
// Route::post('contact',[FrontController::class,'contact_post'])->name('contact-post');
// Route::post('comment',[FrontController::class,'comment_post'])->name('comment-post');

Route::view('fraday/privacy/policy','front.privacy_policy')->name('privacy.policy');
