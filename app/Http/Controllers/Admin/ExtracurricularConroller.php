<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Extracurricular;
use App\Models\Coach;
use Illuminate\Support\Facades\Validator;

class ExtracurricularConroller extends Controller
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
     * Display a listing of the extracurriculars.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $extracurriculars = Extracurricular::with('coach.user')->paginate(10);
        return view('admin.extracurriculars.index', compact('extracurriculars'));
    }

    /**
     * Show the form for creating a new extracurricular.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $coaches = Coach::with('user')->get();
        return view('admin.extracurriculars.create', compact('coaches'));
    }

    /**
     * Store a newly created extracurricular in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'schedule' => 'nullable|string',
            'coach_id' => 'required|exists:coaches,id',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Extracurricular::create($request->all());

        return redirect()->route('admin.extracurriculars.index')
            ->with('success', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    /**
     * Display the specified extracurricular.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $extracurricular = Extracurricular::with(['coach.user', 'enrollments.student.user'])
            ->findOrFail($id);

        return view('admin.extracurriculars.show', compact('extracurricular'));
    }

    /**
     * Show the form for editing the specified extracurricular.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $extracurricular = Extracurricular::findOrFail($id);
        $coaches = Coach::with('user')->get();

        return view('admin.extracurriculars.edit', compact('extracurricular', 'coaches'));
    }

    /**
     * Update the specified extracurricular in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'schedule' => 'nullable|string',
            'coach_id' => 'required|exists:coaches,id',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $extracurricular = Extracurricular::findOrFail($id);
        $extracurricular->update($request->all());

        return redirect()->route('admin.extracurriculars.index')
            ->with('success', 'Ekstrakurikuler berhasil diperbarui.');
    }

    /**
     * Remove the specified extracurricular from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $extracurricular = Extracurricular::findOrFail($id);
        $extracurricular->delete();

        return redirect()->route('admin.extracurriculars.index')
            ->with('success', 'Ekstrakurikuler berhasil dihapus.');
    }
}
