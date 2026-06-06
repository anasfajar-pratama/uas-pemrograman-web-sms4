@extends('layouts.app')
@section('title', 'Soal UAS')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Info Bar --}}
    <div class="bg-white border border-blue-200 rounded-2xl p-4 mb-6 flex flex-wrap gap-4 items-center justify-between shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900">UJIAN AKHIR SEMESTER</h1>
            <p class="text-sm text-gray-500">Pemrograman Web — React &amp; Laravel REST API</p>
        </div>
        <div class="flex flex-wrap gap-3 text-sm text-gray-600">
            <span class="bg-gray-100 px-3 py-1 rounded-lg"><b>20</b> soal</span>
            <span class="bg-gray-100 px-3 py-1 rounded-lg"><b>100</b> poin total</span>
            <span id="answered-badge" class="bg-green-100 text-green-700 px-3 py-1 rounded-lg font-semibold">
                <span id="answered-count">{{ $answers->count() }}</span>/20 dijawab
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
            'teori'  => ['label' => 'Bagian A — Teori (Soal 1–6)',    'color' => 'blue'],
            'logika' => ['label' => 'Bagian B — Logika (Soal 7–12)',   'color' => 'yellow'],
            'coding' => ['label' => 'Bagian C — Coding (Soal 13–20)',  'color' => 'green'],
        ];
        $currentSection = null;
    @endphp

    @foreach($questions as $question)
        @if($currentSection !== $question->section)
            @php $currentSection = $question->section; $sec = $sections[$currentSection]; @endphp
            <div class="mt-8 mb-4">
                <span class="inline-block section-{{ $currentSection }} text-xs font-bold tracking-widest uppercase px-4 py-1.5 rounded-full">
                    {{ $sec['label'] }}
                </span>
            </div>
        @endif

        @php
            $answered = isset($answers[$question->id]) && trim($answers[$question->id]) !== '';
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
                        <span class="flex-shrink-0 text-xs font-bold bg-gray-100 text-gray-500 px-2 py-0.5 rounded-lg">5 poin</span>
                    </div>
                </div>
            </div>

            {{-- Answer Textarea --}}
            <div class="ml-12">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Jawaban Anda:</label>
                <textarea
                    id="answer-{{ $question->id }}"
                    class="answer-textarea w-full border rounded-xl p-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 transition-colors {{ $answered ? 'border-green-400 bg-green-50' : 'border-gray-300' }}"
                    rows="5"
                    placeholder="Tuliskan jawaban Anda di sini..."
                    data-question-id="{{ $question->id }}"
                >{{ $answers[$question->id] ?? '' }}</textarea>

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
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('ujian.selesai') }}"
               onclick="return confirm('Apakah Anda yakin ingin mengakhiri ujian? Tindakan ini tidak dapat dibatalkan.')"
               class="bg-red-600 hover:bg-red-700 text-white font-bold px-8 py-3 rounded-xl transition text-sm shadow-md">
                🏁 Selesai Ujian
            </a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const CSRF       = document.querySelector('meta[name="csrf-token"]').content;
const SESSION_ID = {{ $session->id }};
let   elapsed    = {{ $session->elapsed_seconds }};
let   timerStart = Date.now();
let   savedAnswers = {};
let   pendingQueue = {};

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
    const remaining = Math.max(0, {{ \App\Models\ExamSession::EXAM_DURATION }} - current);

    display.textContent = formatTime(remaining);

    if (remaining <= 300) { // 5 menit terakhir
        display.classList.add('timer-warning', 'bg-red-600');
        display.classList.remove('bg-blue-800');
    } else if (remaining <= 600) { // 10 menit
        display.classList.add('bg-yellow-600');
        display.classList.remove('bg-blue-800');
    }

    if (remaining === 0) {
        clearInterval(timerInterval);
        alert('⏰ Waktu ujian telah habis! Anda akan diarahkan ke halaman submit.');
        window.location.href = '{{ route("ujian.selesai") }}';
    }
}

const timerInterval = setInterval(updateTimer, 1000);
updateTimer();

// Sync ke server setiap 30 detik
setInterval(() => {
    const delta = Math.floor((Date.now() - timerStart) / 1000);
    const current = elapsed + delta;
    fetch('{{ route("ujian.sync-timer") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
        },
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

// ─── Save Answer ─────────────────────────────────────────
async function saveAnswer(questionId) {
    const textarea = document.getElementById('answer-' + questionId);
    const text = textarea.value.trim();
    const statusEl = document.getElementById('status-' + questionId);
    const card = document.getElementById('card-' + questionId);

    statusEl.textContent = '💾 Menyimpan...';

    try {
        const res = await fetch('{{ route("ujian.jawab") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
            },
            body: JSON.stringify({
                question_id: questionId,
                answer_text: textarea.value,
            }),
        });

        const data = await res.json();
        if (data.success) {
            statusEl.textContent = '✅ Tersimpan';
            statusEl.className = 'save-status text-xs text-green-600';
            if (text !== '') {
                textarea.classList.add('border-green-400', 'bg-green-50');
                textarea.classList.remove('border-gray-300');
                card.classList.add('border-green-400');
                card.classList.remove('border-gray-200');
                updateAnsweredCount();
            }
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
    const filled = Array.from(document.querySelectorAll('.answer-textarea'))
        .filter(t => t.value.trim() !== '').length;
    document.getElementById('answered-count').textContent = filled;
}

// ─── Before unload: sync timer ────────────────────────────
window.addEventListener('beforeunload', () => {
    const delta = Math.floor((Date.now() - timerStart) / 1000);
    const current = elapsed + delta;
    navigator.sendBeacon('{{ route("ujian.sync-timer") }}',
        JSON.stringify({ elapsed: current, _token: CSRF }));
});
</script>
@endpush
