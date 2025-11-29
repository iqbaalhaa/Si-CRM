<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah.',
                ])
                ->withInput($request->only('email'));
        }

        $user = Auth::user();
        if ($user && (! $user->is_active)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors([
                    'email' => 'Akun Anda dinonaktifkan. Hubungi admin perusahaan/super admin.',
                ])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();
        $request->session()->flash('force_dark', true);

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Tampilkan form register/signup.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Proses register/signup.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // 'company_id'          => ['nullable', 'exists:companies,id'], // kalau pakai company
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            // 'company_id' => $data['company_id'] ?? null,
        ]);

        // Assign role default untuk user baru
        if (method_exists($user, 'assignRole')) {
            // ganti 'marketing' kalau mau role default lain
            $user->assignRole('marketing');
        }

        Auth::login($user);

        $request->session()->regenerate();

        return redirect($this->redirectPath());
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Tentukan redirect setelah login/register.
     * Bisa dibedakan berdasarkan role.
     */
    protected function redirectPath(): string
    {
        $user = Auth::user();

        if (! $user) {
            return route('login');
        }

        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('super-admin')) {
                return route('dashboard.superadmin');
            }
            if ($user->hasRole('admin')) {
                return route('dashboard.admin');
            }
            if ($user->hasRole('marketing')) {
                return route('dashboard.marketing');
            }
            if ($user->hasRole('cs')) {
                return route('dashboard.cs');
            }
        }

        return route('dashboard');
    }
}
