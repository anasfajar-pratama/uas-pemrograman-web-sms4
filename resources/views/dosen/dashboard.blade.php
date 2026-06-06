@extends('layouts.app')
@section('title', 'Dashboard Dosen')

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Dosen</h1>
            <p class="text-sm text-gray-500">Pemantauan Ujian Akhir Semester — Pemrograman Web</p>
        </div>
        <a href="{{ route('dosen.export') }}"
           class="text-sm bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2.5 rounded-xl transition flex items-center gap-2 shadow">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export CSV
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <div class="text-3xl font-black text-blue-600">{{ $totalMahasiswa }}</div>
            <div class="text-sm text-gray-500 mt-1">Total Mahasiswa</div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <div class="text-3xl font-black text-green-600">{{ $sudahSelesai }}</div>
            <div class="text-sm text-gray-500 mt-1">Sudah Selesai</div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <div class="text-3xl font-black text-yellow-600">{{ $sedangUjian }}</div>
            <div class="text-sm text-gray-500 mt-1">Sedang Ujian</div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <div class="text-3xl font-black text-gray-400">{{ $belumMulai }}</div>
            <div class="text-sm text-gray-500 mt-1">Belum Mulai</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">
            <h2 class="font-bold text-gray-800">Daftar Mahasiswa</h2>
            <span class="text-xs text-gray-400">Auto-refresh setiap 60 detik</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-5 py-3 font-semibold">No</th>
                        <th class="px-5 py-3 font-semibold">NIM</th>
                        <th class="px-5 py-3 font-semibold">Nama</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold">Waktu</th>
                        <th class="px-5 py-3 font-semibold">Estimasi Nilai</th>
                        <th class="px-5 py-3 font-semibold">Nilai Harapan</th>
                        <th class="px-5 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($mahasiswas as $i => $mhs)
                    @php
                        $session = $mhs->examSession;
                        $status  = !$session ? 'belum' : ($session->isFinished() ? 'selesai' : 'ujian');
                        $statusLabel = [
                            'belum'  => ['text' => 'Belum Mulai',    'class' => 'bg-gray-100 text-gray-500'],
                            'ujian'  => ['text' => 'Sedang Ujian',   'class' => 'bg-yellow-100 text-yellow-700'],
                            'selesai'=> ['text' => 'Selesai',        'class' => 'bg-green-100 text-green-700'],
                        ][$status];
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-5 py-3 font-mono font-semibold text-gray-700">{{ $mhs->nim ?? '-' }}</td>
                        <td class="px-5 py-3 font-semibold text-gray-800">{{ $mhs->name }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $statusLabel['class'] }}">
                                {{ $statusLabel['text'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500">
                            @if($session)
                                {{ round($session->elapsed_seconds / 60, 1) }} menit
                                @if($session->isTimeUp()) <span class="text-red-500 text-xs">(habis)</span> @endif
                            @else —
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @if($session && $session->isFinished())
                                <span class="font-bold {{ $session->estimated_grade >= 70 ? 'text-green-600' : ($session->estimated_grade >= 50 ? 'text-yellow-600' : 'text-red-500') }}">
                                    {{ $session->estimated_grade }}/100
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @if($session && $session->expected_grade !== null)
                                <span class="font-semibold text-purple-600">{{ $session->expected_grade }}/100</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <a href="{{ route('dosen.detail', $mhs->id) }}"
                               class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg transition font-semibold">
                                Lihat Jawaban
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                            Belum ada mahasiswa yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Auto-refresh setiap 60 detik
setTimeout(() => location.reload(), 60000);
</script>
@endpush
