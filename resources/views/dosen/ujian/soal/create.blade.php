@extends('layouts.app')
@section('title', 'Tambah Soal')

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('dosen.ujian.soal.index', $exam->id) }}" class="text-gray-500 hover:text-gray-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-xl font-bold text-gray-900">Tambah Soal — {{ $exam->name }}</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <form method="POST" action="{{ route('dosen.ujian.soal.store', $exam->id) }}" id="questionForm">
            @csrf

            <div class="grid grid-cols-3 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor <span class="text-red-500">*</span></label>
                    <input type="number" name="number" value="{{ old('number', $nextNumber) }}" required min="1"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 @error('number') border-red-400 @else border-gray-300 @enderror">
                    @error('number')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Section <span class="text-red-500">*</span></label>
                    <select name="section" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="teori" {{ old('section') === 'teori' ? 'selected' : '' }}>Teori</option>
                        <option value="logika" {{ old('section') === 'logika' ? 'selected' : '' }}>Logika</option>
                        <option value="coding" {{ old('section') === 'coding' ? 'selected' : '' }}>Coding</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Soal <span class="text-red-500">*</span></label>
                    <select name="type" id="typeSelect" onchange="toggleOptions()"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="isian" {{ old('type') === 'isian' ? 'selected' : '' }}>Isian</option>
                        <option value="pilihan_ganda" {{ old('type') === 'pilihan_ganda' ? 'selected' : '' }}>Pilihan Ganda</option>
                        <option value="coding" {{ old('type') === 'coding' ? 'selected' : '' }}>Coding</option>
                    </select>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Soal <span class="text-red-500">*</span></label>
                <textarea name="question_text" rows="4" required
                          class="w-full px-4 py-2.5 border rounded-xl text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 @error('question_text') border-red-400 @else border-gray-300 @enderror"
                          placeholder="Tuliskan soal di sini...">{{ old('question_text') }}</textarea>
                @error('question_text')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Multiple Choice Options --}}
            <div id="optionsSection" class="mb-5" style="display:none">
                <label class="block text-sm font-bold text-gray-700 mb-3">Opsi Jawaban <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 gap-3">
                    @foreach(['A','B','C','D'] as $letter)
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0">{{ $letter }}</span>
                        <input type="text" name="option_{{ strtolower($letter) }}" value="{{ old('option_'.strtolower($letter)) }}"
                               placeholder="Opsi {{ $letter }}"
                               class="flex-1 px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 mt-2">Jawaban benar diisi di kolom "Jawaban" di bawah (ketik A, B, C, atau D).</p>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Jawaban Benar <span class="text-red-500">*</span></label>
                <textarea name="answer_key" rows="3" required
                          class="w-full px-4 py-2.5 border rounded-xl text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 @error('answer_key') border-red-400 @else border-gray-300 @enderror"
                          placeholder="Untuk PG: ketik A/B/C/D. Untuk isian/coding: jawaban lengkap...">{{ old('answer_key') }}</textarea>
                @error('answer_key')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Keywords <span class="text-gray-400 font-normal">(pisah dengan koma)</span></label>
                    <input type="text" name="keywords" value="{{ old('keywords') }}"
                           placeholder="keyword1, keyword2, keyword3"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <p class="text-xs text-gray-500 mt-1">Untuk estimasi skor otomatis pada soal isian/coding.</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Poin <span class="text-red-500">*</span></label>
                    <input type="number" name="points" value="{{ old('points', 5) }}" required min="1" max="100"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('dosen.ujian.soal.index', $exam->id) }}"
                   class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl transition text-sm">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl transition text-sm shadow">
                    Simpan Soal
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
function toggleOptions() {
    const type = document.getElementById('typeSelect').value;
    document.getElementById('optionsSection').style.display = type === 'pilihan_ganda' ? 'block' : 'none';
}
toggleOptions();
</script>
@endpush
