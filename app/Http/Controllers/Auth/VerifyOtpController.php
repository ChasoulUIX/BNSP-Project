<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class VerifyOtpController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.verify-otp', ['email' => $request->email]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'digits:6'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Email tidak ditemukan.',
            ]);
        }

        if ($user->otp_code !== $request->otp) {
            throw ValidationException::withMessages([
                'otp' => 'Kode OTP tidak valid.',
            ]);
        }

        if ($user->otp_expires_at && $user->otp_expires_at->isPast()) {
            throw ValidationException::withMessages([
                'otp' => 'Kode OTP telah kedaluwarsa.',
            ]);
        }

        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        return redirect()->route('password.reset', ['email' => $user->email])
            ->with('status', 'OTP berhasil diverifikasi. Silakan atur password baru.');
    }
}
