@extends('layouts.app')
@section('title', 'Masuk Ujian')

@section('content')
<div class="max-w-lg mx-auto mt-12">

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-8 py-8 text-white text-center">
            <svg class="w-16 h-16 mx-auto mb-3 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
            <h1 class="text-2xl font-bold">Masuk Ujian</h1>
            <p class="text-blue-200 text-sm mt-1">Masukkan kode ujian yang diberikan dosen Anda</p>
        </div>

        <form method="POST" action="{{ route('ujian.submit-code') }}" class="px-8 py-8">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Kode Ujian</label>
                <input type="text" name="code" value="{{ old('code') }}" required autofocus
                       placeholder="Contoh: UAS-7X3K9M"
                       class="w-full px-5 py-4 border-2 rounded-xl text-lg font-mono font-bold text-center tracking-widest uppercase focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 @error('code') border-red-400 bg-red-50 @else border-gray-300 @enderror">
                @error('code')<p class="mt-2 text-sm text-red-600 font-semibold">{{ $message }}</p>@enderror
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition text-base shadow-lg">
                Masuk Ujian →
            </button>
        </form>
    </div>

    <div class="mt-6 text-center text-sm text-gray-500">
        <p>Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">Daftar di sini</a></p>
    </div>

</div>
@endsection
