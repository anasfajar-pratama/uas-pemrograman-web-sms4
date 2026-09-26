@extends('layouts.app')
@section('title', 'Soal UAS — ' . $exam->name)

@push('styles')
<style>
.question-card, .question-card * {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
.answer-textarea, .option-radio {
    -webkit-user-select: text;
    -moz-user-select: text;
    -ms-user-select: text;
    user-select: text;
}
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Info Bar --}}
    <div class="bg-white border border-blue-200 rounded-2xl p-4 mb-6 flex flex-wrap gap-4 items-center justify-between shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900">{{ $exam->name }}</h1>
            <p class="text-sm text-gray-500">Kode: <code class="font-mono font-bold">{{ $exam->code }}</code></p>
        </div>
        <div class="flex flex-wrap gap-3 text-sm text-gray-600">
            <span class="bg-gray-100 px-3 py-1 rounded-lg"><b>{{ $totalQuestions }}</b> soal</span>
            <span class="bg-gray-100 px-3 py-1 rounded-lg"><b>{{ $totalPoints }}</b> poin total</span>
            <span id="answered-badge" class="bg-green-100 text-green-700 px-3 py-1 rounded-lg font-semibold">
                <span id="answered-count">{{ $answers->where('answer_text', '!=', '')->count() }}</span>/{{ $totalQuestions }} dijawab
            </span>
        </div>
    </div>

    {{-- Petunjuk --}}
    <div class="bg-blue-50 border-l-4 border-blue-500 px-5 py-3 rounded-r-xl mb-6 text-sm text-blue-800">
        <b>Petunjuk:</b>
        Kerjakan semua soal. Klik <b>Simpan</b> di setiap soal untuk menyimpan jawaban.
        Jawaban tersimpan otomatis saat Anda mengetik dan berpindah soal.
        Setelah selesai, klik <b>Selesai Ujian</b> di bagian bawah.
    </div>

    {{-- Questions --}}
    @php
        $sections = [
            'teori'  => ['label' => 'Bagian — Teori',    'color' => 'blue'],
            'logika' => ['label' => 'Bagian — Logika',   'color' => 'yellow'],
            'coding' => ['label' => 'Bagian — Coding',   'color' => 'green'],
        ];
        $currentSection = null;
    @endphp

    @foreach($questions as $question)
        @if($currentSection !== $question->section)
            @php $currentSection = $question->section; $sec = $sections[$currentSection] ?? ['label' => ucfirst($currentSection), 'color' => 'gray']; @endphp
            <div class="mt-8 mb-4">
                <span class="inline-block section-{{ $currentSection }} text-xs font-bold tracking-widest uppercase px-4 py-1.5 rounded-full">
                    {{ $sec['label'] }}
                </span>
            </div>
        @endif

        @php
            $answer = $answers[$question->id] ?? null;
            $answered = $answer && trim($answer->answer_text ?? '') !== '';
            if ($question->type === 'pilihan_ganda' && $answer && $answer->selected_option) {
                $answered = true;
            }
        @endphp

        <div class="question-card bg-white border rounded-2xl p-5 mb-4 shadow-sm transition-all duration-200
                    {{ $answered ? 'border-green-400' : 'border-gray-200' }}"
             id="card-{{ $question->id }}">

            {{-- Question Header --}}
            <div class="flex items-start gap-3 mb-4">
                <div class="flex-shrink-0 w-9 h-9 num-{{ $question->section }} rounded-full flex items-center justify-center text-white font-bold text-sm">
                    {{ $question->number }}
                </div>
                <div class="flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm leading-relaxed text-gray-800 whitespace-pre-line">{{ $question->question_text }}</p>
                        <div class="flex-shrink-0 flex items-center gap-2">
                            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-lg">{{ $question->type_label }}</span>
                            <span class="text-xs font-bold bg-gray-100 text-gray-500 px-2 py-0.5 rounded-lg">{{ $question->points }} poin</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Answer Area --}}
            <div class="ml-12">
                @if($question->type === 'pilihan_ganda' && !empty($question->options))
                    {{-- Multiple Choice --}}
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Pilih jawaban Anda:</label>
                    <div class="space-y-2">
                        @foreach($question->options as $letter => $optionText)
                        <label class="flex items-start gap-3 p-3 border rounded-xl cursor-pointer transition hover:bg-blue-50 {{ ($answer && $answer->selected_option === $letter) ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}"
                               id="option-{{ $question->id }}-{{ $letter }}">
                            <input type="radio" name="option_{{ $question->id }}" value="{{ $letter }}"
                                   {{ ($answer && $answer->selected_option === $letter) ? 'checked' : '' }}
                                   class="mt-1 option-radio"
                                   data-question-id="{{ $question->id }}"
                                   onchange="selectOption({{ $question->id }}, '{{ $letter }}')">
                            <div>
                                <span class="font-bold text-blue-700">{{ $letter }}.</span>
                                <span class="text-sm text-gray-700">{{ $optionText }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>

                @elseif($question->type === 'coding')
                    {{-- Coding --}}
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Jawaban Anda:</label>
                    <textarea
                        id="answer-{{ $question->id }}"
                        class="answer-textarea w-full border rounded-xl p-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 font-mono transition-colors {{ $answered ? 'border-green-400 bg-green-50' : 'border-gray-300' }}"
                        rows="8"
                        placeholder="Tuliskan kode Anda di sini..."
                        data-question-id="{{ $question->id }}"
                    >{{ $answer->answer_text ?? '' }}</textarea>

                @else
                    {{-- Isian / default --}}
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Jawaban Anda:</label>
                    <textarea
                        id="answer-{{ $question->id }}"
                        class="answer-textarea w-full border rounded-xl p-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 transition-colors {{ $answered ? 'border-green-400 bg-green-50' : 'border-gray-300' }}"
                        rows="5"
                        placeholder="Tuliskan jawaban Anda di sini..."
                        data-question-id="{{ $question->id }}"
                    >{{ $answer->answer_text ?? '' }}</textarea>
                @endif

                <div class="flex items-center justify-between mt-2">
                    <span class="save-status text-xs text-gray-400" id="status-{{ $question->id }}">
                        @if($answered) ✅ Tersimpan @else Belum dijawab @endif
                    </span>
                    <button
                        onclick="saveAnswer({{ $question->id }})"
                        class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg transition font-semibold">
                        💾 Simpan
                    </button>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Selesai Button --}}
    <div class="mt-8 bg-white border-2 border-gray-200 rounded-2xl p-6 text-center shadow-sm">
        <h2 class="font-bold text-gray-800 mb-1">Selesai Mengerjakan?</h2>
        <p class="text-sm text-gray-500 mb-4">Pastikan semua jawaban sudah tersimpan sebelum klik Selesai.</p>
        <a href="{{ route('ujian.selesai', $exam->code) }}"
           onclick="return confirm('Apakah Anda yakin ingin mengakhiri ujian? Tindakan ini tidak dapat dibatalkan.')"
           class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold px-8 py-3 rounded-xl transition text-sm shadow-md">
            🏁 Selesai Ujian
        </a>
    </div>

</div>
@endsection

@push('scripts')
<script>
const CSRF       = document.querySelector('meta[name="csrf-token"]').content;
const EXAM_CODE  = '{{ $exam->code }}';
let   elapsed    = {{ $session->elapsed_seconds }};
let   timerStart = Date.now();
const EXAM_DURATION = {{ $exam->duration_minutes * 60 }};

// ─── Timer ──────────────────────────────────────────────
function formatTime(sec) {
    if (sec <= 0) return '00:00:00';
    const h = Math.floor(sec / 3600);
    const m = Math.floor((sec % 3600) / 60);
    const s = sec % 60;
    return [h, m, s].map(v => String(v).padStart(2, '0')).join(':');
}

const display = document.getElementById('timer-display');
function updateTimer() {
    const now = Date.now();
    const delta = Math.floor((now - timerStart) / 1000);
    const current = elapsed + delta;
    const remaining = Math.max(0, EXAM_DURATION - current);

    if (display) {
        display.textContent = formatTime(remaining);

        if (remaining <= 300) {
            display.classList.add('timer-warning', 'bg-red-600');
            display.classList.remove('bg-blue-800', 'bg-yellow-600');
        } else if (remaining <= 600) {
            display.classList.add('bg-yellow-600');
            display.classList.remove('bg-blue-800');
        }

        if (remaining === 0) {
            clearInterval(timerInterval);
            alert('⏰ Waktu ujian telah habis! Anda akan diarahkan ke halaman submit.');
            window.location.href = '{{ route("ujian.selesai", $exam->code) }}';
        }
    }
}

const timerInterval = setInterval(updateTimer, 1000);
updateTimer();

// Sync ke server setiap 30 detik
setInterval(() => {
    const delta = Math.floor((Date.now() - timerStart) / 1000);
    const current = elapsed + delta;
    fetch(`/ujian/${EXAM_CODE}/sync-timer`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ elapsed: current }),
    });
}, 30000);

// ─── Auto-save on blur ───────────────────────────────────
document.querySelectorAll('.answer-textarea').forEach(textarea => {
    textarea.addEventListener('blur', () => {
        saveAnswer(parseInt(textarea.dataset.questionId));
    });
    textarea.addEventListener('input', () => {
        const qid = parseInt(textarea.dataset.questionId);
        document.getElementById('status-' + qid).textContent = '⏳ Belum disimpan...';
    });
});

// ─── Select Option (PG) ──────────────────────────────────
function selectOption(questionId, letter) {
    // Update visual
    document.querySelectorAll(`[id^="option-${questionId}-"]`).forEach(el => {
        el.classList.remove('border-blue-500', 'bg-blue-50');
        el.classList.add('border-gray-200');
    });
    const selected = document.getElementById(`option-${questionId}-${letter}`);
    if (selected) {
        selected.classList.add('border-blue-500', 'bg-blue-50');
        selected.classList.remove('border-gray-200');
    }

    // Auto-save
    saveAnswer(questionId, letter);
}

// ─── Save Answer ─────────────────────────────────────────
async function saveAnswer(questionId, selectedOption = null) {
    const textarea = document.getElementById('answer-' + questionId);
    const statusEl = document.getElementById('status-' + questionId);
    const card = document.getElementById('card-' + questionId);

    const body = { question_id: questionId };

    if (textarea) {
        body.answer_text = textarea.value;
    }
    if (selectedOption) {
        body.selected_option = selectedOption;
    } else {
        const checked = document.querySelector(`input[name="option_${questionId}"]:checked`);
        if (checked) body.selected_option = checked.value;
    }

    statusEl.textContent = '💾 Menyimpan...';

    try {
        const res = await fetch(`/ujian/${EXAM_CODE}/jawab`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(body),
        });

        const data = await res.json();
        if (data.success) {
            statusEl.textContent = '✅ Tersimpan';
            statusEl.className = 'save-status text-xs text-green-600';
            card.classList.add('border-green-400');
            card.classList.remove('border-gray-200');
            if (textarea) {
                if (textarea.value.trim() !== '') {
                    textarea.classList.add('border-green-400', 'bg-green-50');
                    textarea.classList.remove('border-gray-300');
                }
            }
            updateAnsweredCount();
        } else {
            statusEl.textContent = '❌ Gagal disimpan';
            statusEl.className = 'save-status text-xs text-red-600';
        }
    } catch(e) {
        statusEl.textContent = '❌ Error jaringan';
        statusEl.className = 'save-status text-xs text-red-600';
    }
}

function updateAnsweredCount() {
    let filled = 0;
    // Count textareas
    document.querySelectorAll('.answer-textarea').forEach(t => {
        if (t.value.trim() !== '') filled++;
    });
    // Count PG that have a checked option but no textarea
    document.querySelectorAll('.option-radio').forEach(radio => {
        if (radio.checked) {
            const qid = radio.dataset.questionId;
            const textarea = document.getElementById('answer-' + qid);
            if (!textarea) filled++;
        }
    });
    document.getElementById('answered-count').textContent = filled;
}

// ─── Before unload: sync timer ────────────────────────────
window.addEventListener('beforeunload', () => {
    const delta = Math.floor((Date.now() - timerStart) / 1000);
    const current = elapsed + delta;
    navigator.sendBeacon(`/ujian/${EXAM_CODE}/sync-timer`,
        JSON.stringify({ elapsed: current, _token: CSRF }));
});

// ─── Anti-copy protection ─────────────────────────────────
document.addEventListener('contextmenu', e => {
    if (e.target.closest('.question-card') && !e.target.closest('.answer-textarea')) {
        e.preventDefault();
    }
});

document.addEventListener('keydown', e => {
    if (e.target.closest('.answer-textarea')) return;
    if ((e.ctrlKey || e.metaKey) && ['c','x','a','p','s'].includes(e.key.toLowerCase())) {
        e.preventDefault();
        e.stopPropagation();
    }
    if (e.key === 'PrintScreen' || e.key === 'F12') {
        e.preventDefault();
    }
});

document.querySelectorAll('.question-card').forEach(card => {
    card.addEventListener('copy', e => {
        if (!e.target.closest('.answer-textarea')) {
            e.preventDefault();
        }
    });
    card.addEventListener('cut', e => e.preventDefault());
    card.addEventListener('selectstart', e => {
        if (!e.target.closest('.answer-textarea')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
