<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Extracurricular;
use App\Models\Meeting;
use App\Models\Enrollment;
use App\Models\Attendance;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
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
     * Display a listing of the extracurriculars managed by the coach.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $coach = $user->coach;

        $extracurriculars = $coach->extracurriculars;

        return view('pembina.attendances.index', compact('extracurriculars'));
    }

    /**
     * Display a listing of meetings for an extracurricular.
     *
     * @param  int  $extracurricularId
     * @return \Illuminate\View\View
     */
    public function meetings($extracurricularId)
    {
        $user = Auth::user();
        $coach = $user->coach;

        // Check if the coach manages this extracurricular
        $extracurricular = Extracurricular::where('id', $extracurricularId)
            ->where('coach_id', $coach->id)
            ->firstOrFail();

        $meetings = Meeting::where('extracurricular_id', $extracurricularId)
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('pembina.attendances.meetings', compact('extracurricular', 'meetings'));
    }

    /**
     * Show the form for creating a new meeting.
     *
     * @param  int  $extracurricularId
     * @return \Illuminate\View\View
     */
    public function createMeeting($extracurricularId)
    {
        $user = Auth::user();
        $coach = $user->coach;

        // Check if the coach manages this extracurricular
        $extracurricular = Extracurricular::where('id', $extracurricularId)
            ->where('coach_id', $coach->id)
            ->firstOrFail();

        return view('pembina.attendances.create_meeting', compact('extracurricular'));
    }

    /**
     * Store a newly created meeting in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $extracurricularId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeMeeting(Request $request, $extracurricularId)
    {
        $user = Auth::user();
        $coach = $user->coach;

        // Check if the coach manages this extracurricular
        $extracurricular = Extracurricular::where('id', $extracurricularId)
            ->where('coach_id', $coach->id)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date|after_or_equal:today',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $meeting = Meeting::create([
            'extracurricular_id' => $extracurricularId,
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'duration' => $request->duration,
            'status' => $request->status,
        ]);

        return redirect()->route('pembina.attendances.meetings', $extracurricularId)
            ->with('success', 'Pertemuan berhasil ditambahkan.');
    }

    /**
     * Show the form for managing attendance for a meeting.
     *
     * @param  int  $meetingId
     * @return \Illuminate\View\View
     */
    public function manage($meetingId)
    {
        $user = Auth::user();
        $coach = $user->coach;

        // Get the meeting and check if the coach manages the related extracurricular
        $meeting = Meeting::with('extracurricular')
            ->findOrFail($meetingId);

        if ($meeting->extracurricular->coach_id != $coach->id) {
            abort(403, 'Unauthorized');
        }

        $extracurricular = $meeting->extracurricular;

        // Get enrolled students
        $enrollments = Enrollment::where('extracurricular_id', $extracurricular->id)
            ->where('status', 'approved')
            ->with('student.user')
            ->get();

        // Get existing attendance records
        $attendances = Attendance::where('meeting_id', $meetingId)
            ->get()
            ->keyBy('enrollment_id');

        return view('pembina.attendances.manage', compact('meeting', 'extracurricular', 'enrollments', 'attendances'));
    }

    /**
     * Store or update attendance records for a meeting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $meetingId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $meetingId)
    {
        $user = Auth::user();
        $coach = $user->coach;

        // Get the meeting and check if the coach manages the related extracurricular
        $meeting = Meeting::with('extracurricular')
            ->findOrFail($meetingId);

        if ($meeting->extracurricular->coach_id != $coach->id) {
            abort(403, 'Unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'status.*' => 'required|in:hadir,izin,sakit,alpha',
            'notes.*' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Process each attendance record
        foreach ($request->enrollment_id as $index => $enrollmentId) {
            Attendance::updateOrCreate(
                [
                    'enrollment_id' => $enrollmentId,
                    'meeting_id' => $meetingId,
                ],
                [
                    'status' => $request->status[$index],
                    'notes' => $request->notes[$index] ?? null,
                ]
            );
        }

        return redirect()->route('pembina.attendances.meetings', $meeting->extracurricular_id)
            ->with('success', 'Data kehadiran berhasil disimpan.');
    }

    /**
     * Show the attendance report for an extracurricular.
     *
     * @param  int  $extracurricularId
     * @return \Illuminate\View\View
     */
    public function report($extracurricularId)
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

        return view('pembina.attendances.report', compact('extracurricular', 'meetings', 'enrollments'));
    }
}
