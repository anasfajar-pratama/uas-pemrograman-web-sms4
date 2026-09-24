<?php $__env->startSection('title', 'Soal UAS — ' . $exam->name); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">

    
    <div class="bg-white border border-blue-200 rounded-2xl p-4 mb-6 flex flex-wrap gap-4 items-center justify-between shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900"><?php echo e($exam->name); ?></h1>
            <p class="text-sm text-gray-500">Kode: <code class="font-mono font-bold"><?php echo e($exam->code); ?></code></p>
        </div>
        <div class="flex flex-wrap gap-3 text-sm text-gray-600">
            <span class="bg-gray-100 px-3 py-1 rounded-lg"><b><?php echo e($totalQuestions); ?></b> soal</span>
            <span class="bg-gray-100 px-3 py-1 rounded-lg"><b><?php echo e($totalPoints); ?></b> poin total</span>
            <span id="answered-badge" class="bg-green-100 text-green-700 px-3 py-1 rounded-lg font-semibold">
                <span id="answered-count"><?php echo e($answers->where('answer_text', '!=', '')->count()); ?></span>/<?php echo e($totalQuestions); ?> dijawab
            </span>
        </div>
    </div>

    
    <div class="bg-blue-50 border-l-4 border-blue-500 px-5 py-3 rounded-r-xl mb-6 text-sm text-blue-800">
        <b>Petunjuk:</b>
        Kerjakan semua soal. Klik <b>Simpan</b> di setiap soal untuk menyimpan jawaban.
        Jawaban tersimpan otomatis saat Anda mengetik dan berpindah soal.
        Setelah selesai, klik <b>Selesai Ujian</b> di bagian bawah.
    </div>

    
    <?php
        $sections = [
            'teori'  => ['label' => 'Bagian — Teori',    'color' => 'blue'],
            'logika' => ['label' => 'Bagian — Logika',   'color' => 'yellow'],
            'coding' => ['label' => 'Bagian — Coding',   'color' => 'green'],
        ];
        $currentSection = null;
    ?>

    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($currentSection !== $question->section): ?>
            <?php $currentSection = $question->section; $sec = $sections[$currentSection] ?? ['label' => ucfirst($currentSection), 'color' => 'gray']; ?>
            <div class="mt-8 mb-4">
                <span class="inline-block section-<?php echo e($currentSection); ?> text-xs font-bold tracking-widest uppercase px-4 py-1.5 rounded-full">
                    <?php echo e($sec['label']); ?>

                </span>
            </div>
        <?php endif; ?>

        <?php
            $answer = $answers[$question->id] ?? null;
            $answered = $answer && trim($answer->answer_text ?? '') !== '';
            if ($question->type === 'pilihan_ganda' && $answer && $answer->selected_option) {
                $answered = true;
            }
        ?>

        <div class="question-card bg-white border rounded-2xl p-5 mb-4 shadow-sm transition-all duration-200
                    <?php echo e($answered ? 'border-green-400' : 'border-gray-200'); ?>"
             id="card-<?php echo e($question->id); ?>">

            
            <div class="flex items-start gap-3 mb-4">
                <div class="flex-shrink-0 w-9 h-9 num-<?php echo e($question->section); ?> rounded-full flex items-center justify-center text-white font-bold text-sm">
                    <?php echo e($question->number); ?>

                </div>
                <div class="flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm leading-relaxed text-gray-800 whitespace-pre-line"><?php echo e($question->question_text); ?></p>
                        <div class="flex-shrink-0 flex items-center gap-2">
                            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-lg"><?php echo e($question->type_label); ?></span>
                            <span class="text-xs font-bold bg-gray-100 text-gray-500 px-2 py-0.5 rounded-lg"><?php echo e($question->points); ?> poin</span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="ml-12">
                <?php if($question->type === 'pilihan_ganda' && !empty($question->options)): ?>
                    
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Pilih jawaban Anda:</label>
                    <div class="space-y-2">
                        <?php $__currentLoopData = $question->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $letter => $optionText): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="flex items-start gap-3 p-3 border rounded-xl cursor-pointer transition hover:bg-blue-50 <?php echo e(($answer && $answer->selected_option === $letter) ? 'border-blue-500 bg-blue-50' : 'border-gray-200'); ?>"
                               id="option-<?php echo e($question->id); ?>-<?php echo e($letter); ?>">
                            <input type="radio" name="option_<?php echo e($question->id); ?>" value="<?php echo e($letter); ?>"
                                   <?php echo e(($answer && $answer->selected_option === $letter) ? 'checked' : ''); ?>

                                   class="mt-1 option-radio"
                                   data-question-id="<?php echo e($question->id); ?>"
                                   onchange="selectOption(<?php echo e($question->id); ?>, '<?php echo e($letter); ?>')">
                            <div>
                                <span class="font-bold text-blue-700"><?php echo e($letter); ?>.</span>
                                <span class="text-sm text-gray-700"><?php echo e($optionText); ?></span>
                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                <?php elseif($question->type === 'coding'): ?>
                    
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Jawaban Anda:</label>
                    <textarea
                        id="answer-<?php echo e($question->id); ?>"
                        class="answer-textarea w-full border rounded-xl p-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 font-mono transition-colors <?php echo e($answered ? 'border-green-400 bg-green-50' : 'border-gray-300'); ?>"
                        rows="8"
                        placeholder="Tuliskan kode Anda di sini..."
                        data-question-id="<?php echo e($question->id); ?>"
                    ><?php echo e($answer->answer_text ?? ''); ?></textarea>

                <?php else: ?>
                    
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Jawaban Anda:</label>
                    <textarea
                        id="answer-<?php echo e($question->id); ?>"
                        class="answer-textarea w-full border rounded-xl p-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 transition-colors <?php echo e($answered ? 'border-green-400 bg-green-50' : 'border-gray-300'); ?>"
                        rows="5"
                        placeholder="Tuliskan jawaban Anda di sini..."
                        data-question-id="<?php echo e($question->id); ?>"
                    ><?php echo e($answer->answer_text ?? ''); ?></textarea>
                <?php endif; ?>

                <div class="flex items-center justify-between mt-2">
                    <span class="save-status text-xs text-gray-400" id="status-<?php echo e($question->id); ?>">
                        <?php if($answered): ?> ✅ Tersimpan <?php else: ?> Belum dijawab <?php endif; ?>
                    </span>
                    <button
                        onclick="saveAnswer(<?php echo e($question->id); ?>)"
                        class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg transition font-semibold">
                        💾 Simpan
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <div class="mt-8 bg-white border-2 border-gray-200 rounded-2xl p-6 text-center shadow-sm">
        <h2 class="font-bold text-gray-800 mb-1">Selesai Mengerjakan?</h2>
        <p class="text-sm text-gray-500 mb-4">Pastikan semua jawaban sudah tersimpan sebelum klik Selesai.</p>
        <a href="<?php echo e(route('ujian.selesai', $exam->code)); ?>"
           onclick="return confirm('Apakah Anda yakin ingin mengakhiri ujian? Tindakan ini tidak dapat dibatalkan.')"
           class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold px-8 py-3 rounded-xl transition text-sm shadow-md">
            🏁 Selesai Ujian
        </a>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const CSRF       = document.querySelector('meta[name="csrf-token"]').content;
const EXAM_CODE  = '<?php echo e($exam->code); ?>';
let   elapsed    = <?php echo e($session->elapsed_seconds); ?>;
let   timerStart = Date.now();
const EXAM_DURATION = <?php echo e($exam->duration_minutes * 60); ?>;

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
            window.location.href = '<?php echo e(route("ujian.selesai", $exam->code)); ?>';
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
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\uas-pemrograman-web\resources\views/exam/index.blade.php ENDPATH**/ ?>