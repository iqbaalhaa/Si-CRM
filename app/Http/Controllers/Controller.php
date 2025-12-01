<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

abstract class Controller extends BaseController
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = $request->user();
            if ($user && (! $user->is_active)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Akun Anda dinonaktifkan. Hubungi admin perusahaan/super admin.',
                ]);
            }

            return $next($request);
        });
    }
}
