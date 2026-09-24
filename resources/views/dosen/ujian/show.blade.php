@extends('layouts.app')
@section('title', 'Detail Ujian — ' . $exam->name)

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('dosen.ujian.index') }}" class="text-gray-500 hover:text-gray-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div class="flex-1">
            <div class="flex items-center gap-3">
                <h1 class="text-xl font-bold text-gray-900">{{ $exam->name }}</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-{{ $exam->status_color }}-100 text-{{ $exam->status_color }}-700">
                    {{ $exam->status_label }}
                </span>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('dosen.ujian.soal.index', $exam->id) }}"
               class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl transition font-semibold">Kelola Soal</a>
            <a href="{{ route('dosen.ujian.soal.import', $exam->id) }}"
               class="text-sm bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl transition font-semibold flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                Import Excel
            </a>
            <a href="{{ route('dosen.ujian.soal.template', $exam->id) }}"
               class="text-sm bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl transition font-semibold flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download Template
            </a>
            <a href="{{ route('dosen.ujian.edit', $exam->id) }}"
               class="text-sm bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-xl transition font-semibold">Edit</a>
        </div>
    </div>

    {{-- Exam Info --}}
    <div class="bg-white rounded-2xl shadow-sm border p-5 mb-6">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Kode Ujian</div>
                <code class="font-mono font-bold text-lg text-gray-800 select-all">{{ $exam->code }}</code>
            </div>
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Durasi</div>
                <div class="font-semibold text-gray-700">{{ $exam->duration_minutes }} menit</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Mulai</div>
                <div class="font-semibold text-gray-700">{{ $exam->starts_at?->format('d M Y, H:i') ?? 'Bebas' }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Berakhir</div>
                <div class="font-semibold text-gray-700">{{ $exam->ends_at?->format('d M Y, H:i') ?? 'Tanpa batas' }}</div>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border text-center">
            <div class="text-3xl font-black text-blue-600">{{ $totalParticipants }}</div>
            <div class="text-sm text-gray-500 mt-1">Peserta</div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border text-center">
            <div class="text-3xl font-black text-green-600">{{ $finished }}</div>
            <div class="text-sm text-gray-500 mt-1">Selesai</div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border text-center">
            <div class="text-3xl font-black text-yellow-600">{{ $inProgress }}</div>
            <div class="text-sm text-gray-500 mt-1">Sedang Ujian</div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border text-center">
            <div class="text-3xl font-black text-purple-600">{{ round($avgGrade) }}</div>
            <div class="text-sm text-gray-500 mt-1">Rata-rata Nilai</div>
        </div>
    </div>

    {{-- Participants Table --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="font-bold text-gray-800">Daftar Peserta</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-5 py-3">No</th>
                        <th class="px-5 py-3">NIM</th>
                        <th class="px-5 py-3">Nama</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Waktu</th>
                        <th class="px-5 py-3">Estimasi Nilai</th>
                        <th class="px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sessions as $i => $session)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-5 py-3 font-mono font-semibold text-gray-700">{{ $session->user->nim ?? '-' }}</td>
                        <td class="px-5 py-3 font-semibold text-gray-800">{{ $session->user->name }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $session->isFinished() ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $session->isFinished() ? 'Selesai' : 'Sedang Ujian' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ round($session->elapsed_seconds / 60, 1) }} menit</td>
                        <td class="px-5 py-3">
                            @if($session->isFinished())
                                <span class="font-bold {{ $session->estimated_grade >= 70 ? 'text-green-600' : ($session->estimated_grade >= 50 ? 'text-yellow-600' : 'text-red-500') }}">
                                    {{ $session->estimated_grade }}/100
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <a href="{{ route('dosen.detail', ['id' => $session->user->id, 'exam_id' => $exam->id]) }}"
                               class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg transition font-semibold">
                                Lihat Jawaban
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">Belum ada peserta.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Questions Summary --}}
    <div class="mt-6 bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">
            <h2 class="font-bold text-gray-800">Daftar Soal ({{ $questions->count() }})</h2>
            <a href="{{ route('dosen.ujian.soal.index', $exam->id) }}"
               class="text-xs text-blue-600 hover:text-blue-800 font-semibold">Kelola Soal →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($questions as $q)
            <div class="px-5 py-3 flex items-center gap-3 hover:bg-gray-50">
                <div class="w-7 h-7 num-{{ $q->section }} rounded-full flex items-center justify-center text-white font-bold text-xs flex-shrink-0">{{ $q->number }}</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-700 truncate">{{ $q->question_text }}</p>
                </div>
                <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full flex-shrink-0">{{ $q->type_label }}</span>
                <span class="text-xs text-gray-400 flex-shrink-0">{{ $q->points }} poin</span>
            </div>
            @endforeach
            @if($questions->isEmpty())
            <div class="px-5 py-8 text-center text-gray-400 text-sm">
                Belum ada soal. <a href="{{ route('dosen.ujian.soal.create', $exam->id) }}" class="text-blue-600 font-semibold">Tambah soal</a>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
