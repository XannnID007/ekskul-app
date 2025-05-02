<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Extracurricular;
use App\Models\Enrollment;
use App\Models\Meeting;
use App\Models\Achievement;
use App\Models\Recommendation;

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
        $this->middleware('siswa');
    }

    /**
     * Show the student dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        // Get student's enrollments
        $enrollments = $student->enrollments()
            ->with('extracurricular')
            ->where('status', 'approved')
            ->orWhere('status', 'completed')
            ->get();

        // Get upcoming meetings for enrolled extracurriculars
        $extracurricularIds = $enrollments->pluck('extracurricular_id')->toArray();
        $upcomingMeetings = Meeting::whereIn('extracurricular_id', $extracurricularIds)
            ->where('date', '>=', now())
            ->where('status', 'scheduled')
            ->with('extracurricular')
            ->orderBy('date', 'asc')
            ->limit(5)
            ->get();

        // Get student's achievements
        $achievements = $student->achievements()
            ->with('extracurricular')
            ->orderBy('date', 'desc')
            ->get();

        // Get student's recommendations
        $recommendations = $student->recommendations()
            ->with('extracurricular')
            ->orderBy('score', 'desc')
            ->limit(5)
            ->get();

        // Check if student has filled interests survey
        $hasInterests = $student->interests()->count() > 0;

        return view('siswa.dashboard', compact(
            'student',
            'enrollments',
            'upcomingMeetings',
            'achievements',
            'recommendations',
            'hasInterests'
        ));
    }
}
