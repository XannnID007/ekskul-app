<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $this->middleware('pembina');
    }

    /**
     * Show the coach dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $coach = $user->coach;

        // Get extracurriculars managed by the coach
        $extracurriculars = $coach->extracurriculars;

        // Get total enrollments across all extracurriculars
        $totalEnrollments = 0;
        $extracurricularIds = $extracurriculars->pluck('id')->toArray();
        $totalEnrollments = Enrollment::whereIn('extracurricular_id', $extracurricularIds)
            ->where('status', 'approved')
            ->orWhere('status', 'completed')
            ->count();

        // Get upcoming meetings
        $upcomingMeetings = Meeting::whereIn('extracurricular_id', $extracurricularIds)
            ->where('date', '>=', now())
            ->where('status', 'scheduled')
            ->with('extracurricular')
            ->orderBy('date', 'asc')
            ->limit(5)
            ->get();

        // Get recent achievements
        $recentAchievements = Achievement::whereIn('extracurricular_id', $extracurricularIds)
            ->with(['student', 'extracurricular'])
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // For each extracurricular, get count of enrolled students
        foreach ($extracurriculars as $extracurricular) {
            $extracurricular->enrolledCount = $extracurricular->enrollments()
                ->where('status', 'approved')
                ->orWhere('status', 'completed')
                ->count();
            $extracurricular->pendingCount = $extracurricular->enrollments()
                ->where('status', 'pending')
                ->count();
        }

        return view('pembina.dashboard', compact(
            'coach',
            'extracurriculars',
            'totalEnrollments',
            'upcomingMeetings',
            'recentAchievements'
        ));
    }
}
