@extends('layouts.app')
@section('title', 'Edit Ujian')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('dosen.ujian.show', $exam->id) }}" class="text-gray-500 hover:text-gray-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-xl font-bold text-gray-900">Edit Ujian</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="bg-gray-50 border rounded-xl px-4 py-3 mb-6 text-sm text-gray-600">
            Kode ujian: <code class="font-mono font-bold text-gray-800">{{ $exam->code }}</code>
            <span class="text-gray-400 ml-2">(kode tidak bisa diubah)</span>
        </div>

        <form method="POST" action="{{ route('dosen.ujian.update', $exam->id) }}">
            @csrf @method('PUT')

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Ujian <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $exam->name) }}" required
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 @error('name') border-red-400 bg-red-50 @else border-gray-300 @enderror">
                @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Durasi (menit) <span class="text-red-500">*</span></label>
                <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $exam->duration_minutes) }}" required min="1" max="600"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 @error('duration_minutes') border-red-400 bg-red-50 @else border-gray-300 @enderror">
                @error('duration_minutes')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Waktu Mulai</label>
                    <input type="datetime-local" name="starts_at"
                           value="{{ old('starts_at', $exam->starts_at?->format('Y-m-d\TH:i')) }}"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 @error('starts_at') border-red-400 bg-red-50 @else border-gray-300 @enderror">
                    @error('starts_at')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Waktu Berakhir</label>
                    <input type="datetime-local" name="ends_at"
                           value="{{ old('ends_at', $exam->ends_at?->format('Y-m-d\TH:i')) }}"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 @error('ends_at') border-red-400 bg-red-50 @else border-gray-300 @enderror">
                    @error('ends_at')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('dosen.ujian.show', $exam->id) }}"
                   class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl transition text-sm">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl transition text-sm shadow">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
