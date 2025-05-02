<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Coach;
use App\Models\Extracurricular;
use App\Models\Enrollment;
use App\Models\Meeting;
use App\Models\Achievement;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get statistics for the dashboard
        $totalStudents = Student::count();
        $totalCoaches = Coach::count();
        $totalExtracurriculars = Extracurricular::count();
        $totalEnrollments = Enrollment::count();
        $activeExtracurriculars = Extracurricular::where('status', 'active')->count();
        $upcomingMeetings = Meeting::where('date', '>=', now())
            ->where('status', 'scheduled')
            ->count();
        $recentAchievements = Achievement::with(['student', 'extracurricular'])
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // Get enrollment distribution for chart
        $extracurricularEnrollments = Extracurricular::withCount(['enrollments' => function ($query) {
            $query->where('status', 'approved')->orWhere('status', 'completed');
        }])->orderBy('enrollments_count', 'desc')->limit(10)->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalCoaches',
            'totalExtracurriculars',
            'totalEnrollments',
            'activeExtracurriculars',
            'upcomingMeetings',
            'recentAchievements',
            'extracurricularEnrollments'
        ));
    }
}
