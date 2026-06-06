@extends('layouts.app')
@section('title', 'Selesai Ujian')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-8 py-6 text-white">
            <h1 class="text-2xl font-bold">🏁 Selesai Ujian</h1>
            <p class="text-red-100 text-sm mt-1">Isi form berikut untuk mengumpulkan jawaban Anda</p>
        </div>

        {{-- Summary --}}
        <div class="px-8 pt-6">
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-blue-700">{{ $answeredCount }}</div>
                    <div class="text-xs text-blue-500 mt-1">Soal Dijawab</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-gray-700">{{ $totalQuestions - $answeredCount }}</div>
                    <div class="text-xs text-gray-500 mt-1">Belum Dijawab</div>
                </div>
                <div class="bg-orange-50 rounded-xl p-4 text-center">
                    @php
                        $elapsedMin = round($session->elapsed_seconds / 60, 1);
                        $remainMin  = round(($session->remaining_seconds) / 60, 1);
                    @endphp
                    <div class="text-2xl font-bold text-orange-700">{{ $elapsedMin }}'</div>
                    <div class="text-xs text-orange-500 mt-1">Waktu Dipakai</div>
                </div>
            </div>

            @if($answeredCount < $totalQuestions)
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 mb-6 text-sm text-yellow-700">
                    ⚠️ Masih ada <b>{{ $totalQuestions - $answeredCount }} soal</b> yang belum dijawab.
                    <a href="{{ route('ujian.index') }}" class="font-semibold underline ml-1">Kembali ke soal</a>
                </div>
            @endif
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('ujian.selesai.submit') }}" class="px-8 pb-8">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    Nilai yang Anda Harapkan (0–100) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="expected_grade" min="0" max="100"
                       value="{{ old('expected_grade') }}"
                       placeholder="Masukkan angka 0–100"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-400 @error('expected_grade') border-red-400 bg-red-50 @else border-gray-300 @enderror">
                @error('expected_grade')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    Jelaskan mengapa Anda mengharapkan nilai tersebut <span class="text-red-500">*</span>
                </label>
                <textarea name="grade_reason" rows="5"
                          placeholder="Tuliskan alasan mengapa Anda mengharapkan nilai tersebut, misalnya: materi yang dikuasai, soal yang berhasil dijawab, dll."
                          class="w-full px-4 py-2.5 border rounded-xl text-sm resize-none focus:outline-none focus:ring-2 focus:ring-red-400 @error('grade_reason') border-red-400 bg-red-50 @else border-gray-300 @enderror">{{ old('grade_reason') }}</textarea>
                @error('grade_reason')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <a href="{{ route('ujian.index') }}"
                   class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-4 rounded-xl transition text-sm">
                    ← Kembali
                </a>
                <button type="submit"
                        onclick="return confirm('Yakin ingin mengumpulkan ujian? Setelah dikumpulkan, Anda tidak bisa mengubah jawaban.')"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-4 rounded-xl transition text-sm shadow-md">
                    📤 Kumpulkan Ujian
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
