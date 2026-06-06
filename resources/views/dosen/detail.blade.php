@extends('layouts.app')
@section('title', 'Detail Jawaban — ' . $mahasiswa->name)

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Back + Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('dosen.dashboard') }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $mahasiswa->name }}</h1>
                {{-- Setelah nama mahasiswa --}}
                @if($mahasiswa->foto_wajah)
                    <img src="{{ asset($mahasiswa->foto_wajah) }}"
                        alt="Foto {{ $mahasiswa->name }}"
                        class="w-16 h-16 rounded-full object-cover border-2 border-blue-300 ml-2">
                @endif
                <p class="text-sm text-gray-500">NIM: {{ $mahasiswa->nim ?? 'N/A' }} &nbsp;|&nbsp; {{ $mahasiswa->email }}</p>
            </div>
        </div>
        @if($session)
        <div class="flex gap-3 text-sm">
            <div class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-2 text-center">
                <div class="font-bold text-blue-700">{{ $session->estimated_grade ?? '-' }}</div>
                <div class="text-xs text-blue-400">Estimasi</div>
            </div>
            <div class="bg-purple-50 border border-purple-200 rounded-xl px-4 py-2 text-center">
                <div class="font-bold text-purple-700">{{ $session->expected_grade ?? '-' }}</div>
                <div class="text-xs text-purple-400">Harapan</div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-2 text-center">
                <div class="font-bold text-green-700">{{ round($totalEstimated, 1) }}/{{ $maxTotal }}</div>
                <div class="text-xs text-green-400">Skor Mentah</div>
            </div>
        </div>
        @endif
    </div>

    {{-- Session Info --}}
    @if($session)
    <div class="bg-white rounded-2xl shadow-sm border p-5 mb-6">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm mb-4">
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Mulai Ujian</div>
                <div class="font-semibold text-gray-700">{{ $session->started_at->format('d M Y, H:i') }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Selesai</div>
                <div class="font-semibold text-gray-700">{{ $session->finished_at?->format('d M Y, H:i') ?? 'Belum selesai' }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Waktu Ujian</div>
                <div class="font-semibold text-gray-700">{{ round($session->elapsed_seconds / 60, 1) }} menit</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Status</div>
                <span class="{{ $session->isFinished() ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} px-2.5 py-0.5 rounded-full text-xs font-bold">
                    {{ $session->isFinished() ? 'Selesai' : 'Belum Selesai' }}
                </span>
            </div>
        </div>

        @if($session->grade_reason)
        <div class="bg-purple-50 border border-purple-200 rounded-xl px-4 py-3">
            <div class="text-xs font-bold text-purple-500 uppercase tracking-wider mb-1">
                Alasan Harap Nilai {{ $session->expected_grade }}
            </div>
            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $session->grade_reason }}</p>
        </div>
        @endif
    </div>
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6 text-sm text-yellow-700">
        ⚠️ Mahasiswa ini belum memulai ujian.
    </div>
    @endif

    {{-- Questions & Answers --}}
    @php
        $sectionLabels = [
            'teori'  => 'Bagian A — Teori',
            'logika' => 'Bagian B — Logika',
            'coding' => 'Bagian C — Coding',
        ];
        $currentSection = null;
    @endphp

    @foreach($questions as $question)
        @if($currentSection !== $question->section)
            @php $currentSection = $question->section; @endphp
            <div class="mt-8 mb-3">
                <span class="section-{{ $question->section }} text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full">
                    {{ $sectionLabels[$question->section] }}
                </span>
            </div>
        @endif

        @php $answer = $answers[$question->id] ?? null; @endphp
        <div class="bg-white border rounded-2xl overflow-hidden mb-4 shadow-sm">
            <div class="px-5 py-3 bg-gray-50 border-b flex items-center gap-3">
                <div class="w-7 h-7 num-{{ $question->section }} rounded-full flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                    {{ $question->number }}
                </div>
                <p class="text-sm font-medium text-gray-700 flex-1">{{ Str::limit($question->question_text, 150) }}</p>
                @if($answer)
                    <span class="flex-shrink-0 font-bold text-sm {{ $answer->estimated_score >= 4 ? 'text-green-600' : ($answer->estimated_score >= 2 ? 'text-yellow-600' : 'text-red-500') }}">
                        {{ $answer->estimated_score }}/5
                    </span>
                @else
                    <span class="flex-shrink-0 text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">Tidak dijawab</span>
                @endif
            </div>

            <div class="grid sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-gray-100">
                {{-- Jawaban Mahasiswa --}}
                <div class="p-4">
                    <div class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-2">Jawaban Mahasiswa</div>
                    <div class="text-sm text-gray-700 whitespace-pre-line leading-relaxed min-h-12">
                        {{ $answer?->answer_text ?: '(tidak dijawab)' }}
                    </div>
                </div>

                {{-- Kunci Jawaban --}}
                <div class="p-4 bg-green-50">
                    <div class="text-xs font-bold text-green-700 uppercase tracking-wider mb-2">✅ Kunci Jawaban</div>
                    <div class="text-sm text-gray-600 whitespace-pre-line leading-relaxed">
                        {{ $question->answer_key }}
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>
@endsection
