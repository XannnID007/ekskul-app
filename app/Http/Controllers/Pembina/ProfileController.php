<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Coach;

class ProfileController extends Controller
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
     * Show the coach profile page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $coach = $user->coach;

        return view('pembina.profile', compact('user', 'coach'));
    }

    /**
     * Update the coach profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $coach = $user->coach;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'specialty' => 'nullable|string|max:100',
            'experience' => 'nullable|integer|min:0',
            'certificates' => 'nullable|string',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        $coach->specialty = $request->specialty;
        $coach->experience = $request->experience;
        $coach->certificates = $request->certificates;
        $coach->save();

        return redirect()->route('pembina.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update the coach password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('pembina.profile')
            ->with('success', 'Password berhasil diperbarui.');
    }
}
