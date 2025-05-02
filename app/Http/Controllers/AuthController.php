<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Admin;
use App\Models\Coach;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on user role
            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            } else if ($user->isSiswa()) {
                return redirect()->intended(route('siswa.dashboard'));
            } else if ($user->isPembina()) {
                return redirect()->intended(route('pembina.dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password yang dimasukkan tidak valid.',
        ])->withInput($request->only('email'));
    }

    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:siswa,pembina',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Create role-specific profile
        if ($request->role === 'siswa') {
            Student::create([
                'user_id' => $user->id,
                'nis' => $request->nis,
                'kelas' => $request->kelas,
                'gender' => $request->gender,
                'birthdate' => $request->birthdate,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);
        } else if ($request->role === 'pembina') {
            Coach::create([
                'user_id' => $user->id,
                'specialty' => $request->specialty,
                'experience' => $request->experience,
            ]);
        }

        // Log the user in
        Auth::login($user);

        // Redirect based on user role
        if ($user->isSiswa()) {
            return redirect()->route('siswa.dashboard');
        } else if ($user->isPembina()) {
            return redirect()->route('pembina.dashboard');
        }

        return redirect('/');
    }

    /**
     * Log the user out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
