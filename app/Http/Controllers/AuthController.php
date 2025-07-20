<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ResetPassword as Reset;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordOtpMail;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function ubahProfil()
    {
        $user = Auth::user();
        return view('auth.ubahProfil', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $user = User::findOrFail($request->idUser);

        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255|unique:users,nama,' . $request->idUser . ',idUser',
            'email' => 'required|email|max:255|unique:users,email,' . $request->idUser . ',idUser',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|confirmed',
        ]);

        // Update nama/email
        $user->nama = $request->nama;
        $user->email = $request->email;

        // Jika user mengisi password baru, maka:
        if ($request->filled('password')) {
            // Validasi password lama
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
            }

            // Ganti password
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Email tidak ditemukan'
            ]);
        }

        $otp = rand(100000, 999999);
        $expiredAt = now()->addMinutes(10);

        // Cek apakah sudah ada data reset sebelumnya
        $reset = Reset::where('email', $request->email)->first();

        if ($reset) {
            // update existing reset record
            $reset->update([
                'otp_code' => $otp,
                'otp_expires_at' => $expiredAt
            ]);
        } else {
            // buat baru jika belum ada
            Reset::create([
                'email' => $request->email,
                'otp_code' => $otp,
                'otp_expires_at' => $expiredAt
            ]);
        }

        // data untuk email
        $data = [
            'nama' => $user->nama ?? $user->name ?? 'User',
            'otp' => $otp,
            'expired' => $expiredAt->format('H:i'),
        ];

        Mail::to($user->email)->send(new ResetPasswordOtpMail($data));

        return response()->json([
            'status' => true,
            'message' => 'Kode OTP telah dikirim ke email Anda'
        ]);
    }

    public function submitResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        // Cek apakah OTP valid
        $reset = Reset::where('email', $request->email)
            ->where('otp_code', $request->otp_code)
            ->where('otp_expires_at', '>', now())
            ->first();

        if (!$reset) {
            return response()->json([
                'status' => false,
                'message' => 'Kode OTP salah atau sudah kadaluarsa'
            ]);
        }

        // Ambil user dan ubah passwordnya
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus kode OTP setelah digunakan
        $reset->delete();

        return response()->json([
            'status' => true,
            'message' => 'Password berhasil direset, silakan login'
        ]);
    }
}
