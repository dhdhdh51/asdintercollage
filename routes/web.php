<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Student;
use App\Http\Controllers\Teacher;
use App\Http\Controllers\Parent as ParentCtrl;
use App\Http\Controllers\Public as PublicCtrl;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicCtrl\HomeController::class, 'index'])->name('home');
Route::get('/about', fn() => view('public.about'))->name('about');
Route::get('/contact', [PublicCtrl\HomeController::class, 'contact'])->name('contact');
Route::get('/blog', [PublicCtrl\HomeController::class, 'blog'])->name('blog');
Route::get('/blog/{post:slug}', [PublicCtrl\HomeController::class, 'blogPost'])->name('blog.show');
Route::get('/sitemap.xml', [PublicCtrl\HomeController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [PublicCtrl\HomeController::class, 'robots'])->name('robots');

// Public Admission
Route::prefix('admission')->name('admission.')->group(function () {
    Route::get('/', [PublicCtrl\AdmissionController::class, 'form'])->name('form');
    Route::post('/submit', [PublicCtrl\AdmissionController::class, 'submit'])->name('submit');
    Route::get('/status', [PublicCtrl\AdmissionController::class, 'status'])->name('status');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.forgot');
    Route::post('/forgot-password', [AuthController::class, 'sendOtp'])->name('password.otp.send');
    Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('password.verify-otp');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('password.verify-otp.post');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Students
    Route::resource('students', Admin\StudentController::class);

    // Teachers
    Route::resource('teachers', Admin\TeacherController::class);

    // Classes & Sections & Subjects
    Route::resource('classes', Admin\ClassController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/classes/{class}/sections', [Admin\ClassController::class, 'sections'])->name('classes.sections');
    Route::post('/classes/{class}/sections', [Admin\ClassController::class, 'storeSection'])->name('classes.sections.store');
    Route::get('/classes/{class}/subjects', [Admin\ClassController::class, 'subjects'])->name('classes.subjects');
    Route::post('/classes/{class}/subjects', [Admin\ClassController::class, 'storeSubject'])->name('classes.subjects.store');

    // Admissions
    Route::get('/admissions', [Admin\AdmissionController::class, 'index'])->name('admissions.index');
    Route::get('/admissions/{admission}', [Admin\AdmissionController::class, 'show'])->name('admissions.show');
    Route::post('/admissions/{admission}/approve', [Admin\AdmissionController::class, 'approve'])->name('admissions.approve');
    Route::post('/admissions/{admission}/reject', [Admin\AdmissionController::class, 'reject'])->name('admissions.reject');

    // Attendance
    Route::get('/attendance', [Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [Admin\AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/report', [Admin\AttendanceController::class, 'report'])->name('attendance.report');

    // Fees
    Route::get('/fees', [Admin\FeeController::class, 'index'])->name('fees.index');
    Route::get('/fees/create', [Admin\FeeController::class, 'create'])->name('fees.create');
    Route::post('/fees', [Admin\FeeController::class, 'store'])->name('fees.store');
    Route::get('/fees/{fee}', [Admin\FeeController::class, 'show'])->name('fees.show');
    Route::post('/fees/{fee}/collect-cash', [Admin\FeeController::class, 'collectCash'])->name('fees.collect-cash');
    Route::get('/fees/{fee}/invoice', [Admin\FeeController::class, 'invoice'])->name('fees.invoice');
    Route::get('/fees/categories', [Admin\FeeController::class, 'categories'])->name('fees.categories');
    Route::post('/fees/categories', [Admin\FeeController::class, 'storeCategory'])->name('fees.categories.store');
    Route::get('/fees/transactions', [Admin\FeeController::class, 'transactions'])->name('fees.transactions');

    // Notifications
    Route::get('/notifications', [Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [Admin\NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [Admin\NotificationController::class, 'store'])->name('notifications.store');

    // Blog / Announcements
    Route::resource('blog', Admin\BlogController::class);

    // Settings
    Route::get('/settings', [Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/general', [Admin\SettingsController::class, 'updateGeneral'])->name('settings.general');
    Route::post('/settings/smtp', [Admin\SettingsController::class, 'updateSmtp'])->name('settings.smtp');
    Route::post('/settings/seo', [Admin\SettingsController::class, 'updateSeo'])->name('settings.seo');
    Route::post('/settings/payment', [Admin\SettingsController::class, 'updatePayment'])->name('settings.payment');
    Route::post('/settings/feature', [Admin\SettingsController::class, 'toggleFeature'])->name('settings.feature');
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [Student\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [Student\DashboardController::class, 'profile'])->name('profile');
    Route::get('/attendance', [Student\DashboardController::class, 'attendance'])->name('attendance');
    Route::get('/fees', [Student\DashboardController::class, 'fees'])->name('fees');
    Route::get('/fees/{fee}/pay', [Student\PaymentController::class, 'initiate'])->name('fees.pay');
});

// PayU callbacks (no auth middleware, verified by hash)
Route::post('/payment/success', [Student\PaymentController::class, 'success'])->name('payment.success');
Route::post('/payment/failure', [Student\PaymentController::class, 'failure'])->name('payment.failure');

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
*/
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/dashboard', [Teacher\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/attendance', [Teacher\DashboardController::class, 'attendance'])->name('attendance');
    Route::post('/attendance', [Teacher\DashboardController::class, 'storeAttendance'])->name('attendance.store');
    Route::get('/homeworks', [Teacher\DashboardController::class, 'homeworks'])->name('homeworks');
    Route::post('/homeworks', [Teacher\DashboardController::class, 'storeHomework'])->name('homeworks.store');
});

/*
|--------------------------------------------------------------------------
| Parent Routes
|--------------------------------------------------------------------------
*/
Route::prefix('parent')->name('parent.')->middleware(['auth', 'role:parent'])->group(function () {
    Route::get('/dashboard', [ParentCtrl\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/children', [ParentCtrl\DashboardController::class, 'children'])->name('children');
    Route::get('/children/{student}/attendance', [ParentCtrl\DashboardController::class, 'childAttendance'])->name('children.attendance');
    Route::get('/children/{student}/fees', [ParentCtrl\DashboardController::class, 'childFees'])->name('children.fees');
});
