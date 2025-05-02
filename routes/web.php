<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ExtracurricularController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\RecommendationController;
use App\Http\Controllers\Siswa\EnrollmentController as SiswaEnrollmentController;
use App\Http\Controllers\Siswa\ScheduleController;
use App\Http\Controllers\Siswa\AchievementController as SiswaAchievementController;
use App\Http\Controllers\Siswa\ProfileController as SiswaProfileController;
use App\Http\Controllers\Pembina\DashboardController as PembinaDashboardController;
use App\Http\Controllers\Pembina\ExtracurricularController as PembinaExtracurricularController;
use App\Http\Controllers\Pembina\EnrollmentController as PembinaEnrollmentController;
use App\Http\Controllers\Pembina\MeetingController;
use App\Http\Controllers\Pembina\AttendanceController;
use App\Http\Controllers\Pembina\AchievementController as PembinaAchievementController;
use App\Http\Controllers\Pembina\ReportController as PembinaReportController;
use App\Http\Controllers\Pembina\ProfileController as PembinaProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Halaman Utama (Welcome)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Ekstrakurikuler
    Route::resource('extracurriculars', ExtracurricularController::class);

    // Pengguna
    Route::resource('users', UserController::class);

    // Laporan
    Route::get('/reports/enrollments', [AdminReportController::class, 'enrollments'])->name('reports.enrollments');
    Route::get('/reports/attendance', [AdminReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('/reports/achievements', [AdminReportController::class, 'achievements'])->name('reports.achievements');

    // Export Laporan
    Route::get('/reports/enrollments/export', [AdminReportController::class, 'exportEnrollments'])->name('reports.enrollments.export');
    Route::get('/reports/attendance/export', [AdminReportController::class, 'exportAttendance'])->name('reports.attendance.export');
    Route::get('/reports/achievements/export', [AdminReportController::class, 'exportAchievements'])->name('reports.achievements.export');
});

// Siswa Routes
Route::prefix('siswa')->middleware(['auth', 'siswa'])->name('siswa.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');

    // Rekomendasi
    Route::get('/recommendations/survey', [RecommendationController::class, 'showSurveyForm'])->name('recommendations.survey');
    Route::post('/recommendations/save-survey', [RecommendationController::class, 'saveSurvey'])->name('recommendations.save-survey');
    Route::get('/recommendations', [RecommendationController::class, 'showRecommendations'])->name('recommendations.show');
    Route::post('/recommendations/enroll', [RecommendationController::class, 'enroll'])->name('recommendations.enroll');

    // Pendaftaran Ekstrakurikuler
    Route::get('/enrollments', [SiswaEnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('/enrollments/{enrollment}', [SiswaEnrollmentController::class, 'show'])->name('enrollments.show');
    Route::post('/enrollments/cancel/{enrollment}', [SiswaEnrollmentController::class, 'cancel'])->name('enrollments.cancel');

    // Jadwal
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');

    // Prestasi
    Route::get('/achievements', [SiswaAchievementController::class, 'index'])->name('achievements');
    Route::get('/achievements/{achievement}', [SiswaAchievementController::class, 'show'])->name('achievements.show');

    // Profil
    Route::get('/profile', [SiswaProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [SiswaProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/update-password', [SiswaProfileController::class, 'updatePassword'])->name('profile.update-password');
});

// Pembina Routes
Route::prefix('pembina')->middleware(['auth', 'pembina'])->name('pembina.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [PembinaDashboardController::class, 'index'])->name('dashboard');

    // Ekstrakurikuler
    Route::get('/extracurriculars', [PembinaExtracurricularController::class, 'index'])->name('extracurriculars.index');
    Route::get('/extracurriculars/{extracurricular}', [PembinaExtracurricularController::class, 'show'])->name('extracurriculars.show');
    Route::get('/extracurriculars/{extracurricular}/edit', [PembinaExtracurricularController::class, 'edit'])->name('extracurriculars.edit');
    Route::put('/extracurriculars/{extracurricular}', [PembinaExtracurricularController::class, 'update'])->name('extracurriculars.update');

    // Pendaftaran
    Route::get('/enrollments', [PembinaEnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('/enrollments/{enrollment}', [PembinaEnrollmentController::class, 'show'])->name('enrollments.show');
    Route::post('/enrollments/{enrollment}/approve', [PembinaEnrollmentController::class, 'approve'])->name('enrollments.approve');
    Route::post('/enrollments/{enrollment}/reject', [PembinaEnrollmentController::class, 'reject'])->name('enrollments.reject');

    // Pertemuan
    Route::get('/meetings', [MeetingController::class, 'index'])->name('meetings.index');
    Route::get('/meetings/create', [MeetingController::class, 'create'])->name('meetings.create');
    Route::post('/meetings', [MeetingController::class, 'store'])->name('meetings.store');
    Route::get('/meetings/{meeting}', [MeetingController::class, 'show'])->name('meetings.show');
    Route::get('/meetings/{meeting}/edit', [MeetingController::class, 'edit'])->name('meetings.edit');
    Route::put('/meetings/{meeting}', [MeetingController::class, 'update'])->name('meetings.update');
    Route::delete('/meetings/{meeting}', [MeetingController::class, 'destroy'])->name('meetings.destroy');

    // Kehadiran
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/attendances/extracurricular/{extracurricular}/meetings', [AttendanceController::class, 'meetings'])->name('attendances.meetings');
    Route::get('/attendances/meeting/{meeting}/create', [AttendanceController::class, 'createMeeting'])->name('attendances.create-meeting');
    Route::post('/attendances/meeting/{extracurricular}', [AttendanceController::class, 'storeMeeting'])->name('attendances.store-meeting');
    Route::get('/attendances/manage/{meeting}', [AttendanceController::class, 'manage'])->name('attendances.manage');
    Route::post('/attendances/store/{meeting}', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::get('/attendances/report/{extracurricular}', [AttendanceController::class, 'report'])->name('attendances.report');

    // Prestasi
    Route::get('/achievements', [PembinaAchievementController::class, 'index'])->name('achievements.index');
    Route::get('/achievements/create', [PembinaAchievementController::class, 'create'])->name('achievements.create');
    Route::post('/achievements', [PembinaAchievementController::class, 'store'])->name('achievements.store');
    Route::get('/achievements/{achievement}', [PembinaAchievementController::class, 'show'])->name('achievements.show');
    Route::get('/achievements/{achievement}/edit', [PembinaAchievementController::class, 'edit'])->name('achievements.edit');
    Route::put('/achievements/{achievement}', [PembinaAchievementController::class, 'update'])->name('achievements.update');
    Route::delete('/achievements/{achievement}', [PembinaAchievementController::class, 'destroy'])->name('achievements.destroy');

    // Laporan
    Route::get('/reports/attendance', [PembinaReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('/reports/achievements', [PembinaReportController::class, 'achievements'])->name('reports.achievements');

    // Export Laporan
    Route::get('/reports/attendance/{extracurricular}/pdf', [PembinaReportController::class, 'attendancePdf'])->name('reports.attendance.pdf');
    Route::get('/reports/attendance/{extracurricular}/excel', [PembinaReportController::class, 'attendanceExcel'])->name('reports.attendance.excel');
    Route::get('/reports/achievements/pdf', [PembinaReportController::class, 'achievementsPdf'])->name('reports.achievements.pdf');
    Route::get('/reports/achievements/excel', [PembinaReportController::class, 'achievementsExcel'])->name('reports.achievements.excel');

    // Profil
    Route::get('/profile', [PembinaProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [PembinaProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/update-password', [PembinaProfileController::class, 'updatePassword'])->name('profile.update-password');
});
