@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="w-full max-w-md">
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Masuk ke Sistem UAS</h1>
            <p class="text-sm text-gray-500 mt-1">Ujian Akhir Semester — Pemrograman Web</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Login field --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email atau NIM</label>
                    <input type="text" name="login" value="{{ old('login') }}"
                           placeholder="Masukkan email atau NIM"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('login') border-red-400 bg-red-50 @else border-gray-300 @enderror">
                    @error('login')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                    <input type="password" name="password"
                           placeholder="••••••••"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-400 bg-red-50 @else border-gray-300 @enderror">
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center mb-6">
                    <input type="checkbox" name="remember" id="remember" class="mr-2 rounded">
                    <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-xl transition text-sm">
                    Masuk & Mulai Ujian
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-500">Belum terdaftar?
                    <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">Daftar di sini</a>
                </p>
            </div>
        </div>

        {{-- Info box --}}
        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 text-xs text-blue-700">
            <strong>ℹ️ Info:</strong> Mahasiswa wajib register terlebih dahulu. Setelah login, timer 120 menit akan berjalan otomatis. Jika logout dan login kembali, waktu dilanjutkan — bukan dimulai dari awal.
        </div>
    </div>
</div>
@endsection
