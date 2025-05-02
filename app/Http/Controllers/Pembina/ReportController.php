<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Coach;
use App\Models\Extracurricular;
use App\Models\Enrollment;
use App\Models\Meeting;
use App\Models\Attendance;
use App\Models\Achievement;
use PDF;
use Excel;
use App\Exports\AttendanceExport;
use App\Exports\AchievementsExport;

class ReportController extends Controller
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
     * Show the attendance report page.
     *
     * @return \Illuminate\View\View
     */
    public function attendance()
    {
        $user = Auth::user();
        $coach = $user->coach;

        $extracurriculars = $coach->extracurriculars;

        return view('pembina.reports.attendance', compact('extracurriculars'));
    }

    /**
     * Show the achievements report page.
     *
     * @return \Illuminate\View\View
     */
    public function achievements()
    {
        $user = Auth::user();
        $coach = $user->coach;

        $extracurriculars = $coach->extracurriculars;
        $extracurricularIds = $extracurriculars->pluck('id')->toArray();

        $achievements = Achievement::whereIn('extracurricular_id', $extracurricularIds)
            ->with(['student.user', 'extracurricular'])
            ->orderBy('date', 'desc')
            ->get();

        return view('pembina.reports.achievements', compact('extracurriculars', 'achievements'));
    }

    /**
     * Generate PDF for attendance report.
     *
     * @param  int  $extracurricularId
     * @return \Illuminate\Http\Response
     */
    public function attendancePdf($extracurricularId)
    {
        $user = Auth::user();
        $coach = $user->coach;

        // Check if the coach manages this extracurricular
        $extracurricular = Extracurricular::where('id', $extracurricularId)
            ->where('coach_id', $coach->id)
            ->firstOrFail();

        // Get meetings
        $meetings = Meeting::where('extracurricular_id', $extracurricularId)
            ->orderBy('date', 'desc')
            ->get();

        // Get enrolled students
        $enrollments = Enrollment::where('extracurricular_id', $extracurricularId)
            ->where('status', 'approved')
            ->with('student.user')
            ->get();

        // Get attendance statistics for each student
        foreach ($enrollments as $enrollment) {
            $totalMeetings = $meetings->count();
            $attendance = Attendance::where('enrollment_id', $enrollment->id)
                ->whereIn('meeting_id', $meetings->pluck('id'))
                ->get();

            $hadirCount = $attendance->where('status', 'hadir')->count();
            $izinCount = $attendance->where('status', 'izin')->count();
            $sakitCount = $attendance->where('status', 'sakit')->count();
            $alphaCount = $attendance->where('status', 'alpha')->count();
            $totalCount = $attendance->count();

            $enrollment->attendance_stats = [
                'hadir' => $hadirCount,
                'izin' => $izinCount,
                'sakit' => $sakitCount,
                'alpha' => $alphaCount,
                'total' => $totalCount,
                'total_meetings' => $totalMeetings,
                'percentage' => $totalMeetings > 0 ? round(($hadirCount / $totalMeetings) * 100, 2) : 0,
            ];
        }

        $data = [
            'extracurricular' => $extracurricular,
            'meetings' => $meetings,
            'enrollments' => $enrollments,
            'title' => 'Laporan Kehadiran - ' . $extracurricular->name,
            'date' => now()->format('d M Y')
        ];

        $pdf = PDF::loadView('pembina.reports.pdf.attendance', $data);

        return $pdf->download('laporan-kehadiran-' . $extracurricular->name . '.pdf');
    }

    /**
     * Generate Excel for attendance report.
     *
     * @param  int  $extracurricularId
     * @return \Illuminate\Http\Response
     */
    public function attendanceExcel($extracurricularId)
    {
        $user = Auth::user();
        $coach = $user->coach;

        // Check if the coach manages this extracurricular
        $extracurricular = Extracurricular::where('id', $extracurricularId)
            ->where('coach_id', $coach->id)
            ->firstOrFail();

        return Excel::download(new AttendanceExport($extracurricularId), 'laporan-kehadiran-' . $extracurricular->name . '.xlsx');
    }

    /**
     * Generate PDF for achievements report.
     *
     * @return \Illuminate\Http\Response
     */
    public function achievementsPdf()
    {
        $user = Auth::user();
        $coach = $user->coach;

        $extracurriculars = $coach->extracurriculars;
        $extracurricularIds = $extracurriculars->pluck('id')->toArray();

        $achievements = Achievement::whereIn('extracurricular_id', $extracurricularIds)
            ->with(['student.user', 'extracurricular'])
            ->orderBy('date', 'desc')
            ->get();

        $data = [
            'extracurriculars' => $extracurriculars,
            'achievements' => $achievements,
            'title' => 'Laporan Prestasi Ekstrakurikuler',
            'date' => now()->format('d M Y')
        ];

        $pdf = PDF::loadView('pembina.reports.pdf.achievements', $data);

        return $pdf->download('laporan-prestasi-ekstrakurikuler.pdf');
    }

    /**
     * Generate Excel for achievements report.
     *
     * @return \Illuminate\Http\Response
     */
    public function achievementsExcel()
    {
        $user = Auth::user();
        $coach = $user->coach;

        $extracurricularIds = $coach->extracurriculars->pluck('id')->toArray();

        return Excel::download(new AchievementsExport($extracurricularIds), 'laporan-prestasi-ekstrakurikuler.xlsx');
    }
}
