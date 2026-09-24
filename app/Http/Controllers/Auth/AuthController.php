<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'nim'        => 'required|string|max:20|unique:users,nim',
            'email'      => 'required|email|unique:users,email',
            'password'   => ['required', 'confirmed', Password::min(6)],
            'foto_wajah' => 'required|string',
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'nim.required'       => 'NIM wajib diisi.',
            'nim.unique'         => 'NIM sudah terdaftar.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'foto_wajah.required'=> 'Foto wajah wajib diambil.',
        ]);

        $base64 = $request->foto_wajah;
        if (!preg_match('/^data:image\/(jpeg|png|webp);base64,/', $base64)) {
            return back()->withErrors(['foto_wajah' => 'Format foto tidak valid.'])->withInput();
        }

        $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64));
        $filename  = 'foto-wajah/' . uniqid() . '.jpg';
        $uploadDir = dirname($_SERVER['DOCUMENT_ROOT']) . '/public_html/uploads/foto-wajah/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        file_put_contents($uploadDir . basename($filename), $imageData);

        $user = User::create([
            'name'       => $request->name,
            'nim'        => $request->nim,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => 'mahasiswa',
            'foto_wajah' => 'uploads/foto-wajah/' . basename($filename),
        ]);

        Auth::login($user);

        return redirect()->route('ujian.enter-code')
            ->with('success', 'Registrasi berhasil! Silakan masukkan kode ujian.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required'    => 'Email atau NIM wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? ['email' => $request->login, 'password' => $request->password]
            : ['nim'   => $request->login, 'password' => $request->password];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['login' => 'Email/NIM atau password salah.'])->withInput();
        }

        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->isMahasiswa()) {
            return redirect()->route('ujian.enter-code');
        }

        return redirect()->route('dosen.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah logout.');
    }
}
