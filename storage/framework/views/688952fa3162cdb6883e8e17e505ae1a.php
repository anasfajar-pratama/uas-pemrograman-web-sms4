<?php $__env->startSection('title', 'Hasil Ujian'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">

    
    <div class="bg-gradient-to-r from-green-600 to-emerald-700 rounded-2xl p-6 mb-6 text-white shadow-xl">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold">✅ Ujian Telah Dikumpulkan</h1>
                <p class="text-green-100 text-sm mt-1"><?php echo e(auth()->user()->name); ?> — <?php echo e(auth()->user()->nim); ?></p>
                <p class="text-green-200 text-xs mt-0.5"><?php echo e($exam->name); ?> | Selesai: <?php echo e($session->finished_at->format('d M Y, H:i')); ?></p>
            </div>
            <div class="text-center">
                <div class="text-4xl font-black"><?php echo e($session->estimated_grade); ?><span class="text-xl">/100</span></div>
                <div class="text-green-100 text-xs mt-1">Estimasi Nilai Sistem</div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 text-center shadow-sm border">
            <div class="text-2xl font-bold text-blue-600"><?php echo e($answers->count()); ?></div>
            <div class="text-xs text-gray-500 mt-1">Soal Dijawab</div>
        </div>
        <div class="bg-white rounded-xl p-4 text-center shadow-sm border">
            <div class="text-2xl font-bold text-green-600"><?php echo e($session->estimated_grade); ?></div>
            <div class="text-xs text-gray-500 mt-1">Estimasi Nilai</div>
        </div>
        <div class="bg-white rounded-xl p-4 text-center shadow-sm border">
            <div class="text-2xl font-bold text-purple-600"><?php echo e($session->expected_grade); ?></div>
            <div class="text-xs text-gray-500 mt-1">Nilai Harapan</div>
        </div>
        <div class="bg-white rounded-xl p-4 text-center shadow-sm border">
            <div class="text-2xl font-bold text-orange-600"><?php echo e(round($session->elapsed_seconds / 60)); ?>'</div>
            <div class="text-xs text-gray-500 mt-1">Waktu Dipakai</div>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border p-5 mb-6">
        <h2 class="font-bold text-gray-800 mb-2">Alasan Nilai yang Diharapkan</h2>
        <p class="text-sm text-gray-600 whitespace-pre-line"><?php echo e($session->grade_reason); ?></p>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="px-5 py-4 border-b bg-gray-50">
            <h2 class="font-bold text-gray-800">Rekap Jawaban & Estimasi Penilaian</h2>
            <p class="text-xs text-gray-500 mt-0.5">Estimasi dihitung otomatis berdasarkan kesesuaian kata kunci jawaban.</p>
        </div>

        <?php $__currentLoopData = $answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="px-5 py-4 border-b last:border-b-0 hover:bg-gray-50 transition">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-8 h-8 num-<?php echo e($answer->question->section); ?> rounded-full flex items-center justify-center text-white font-bold text-xs">
                    <?php echo e($answer->question->number); ?>

                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <p class="text-xs text-gray-500 truncate"><?php echo e(Str::limit($answer->question->question_text, 100)); ?></p>
                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full flex-shrink-0"><?php echo e($answer->question->type_label); ?></span>
                    </div>
                    <div class="bg-gray-50 border rounded-lg px-3 py-2 text-sm text-gray-700 whitespace-pre-line max-h-28 overflow-y-auto">
                        <?php if($answer->question->type === 'pilihan_ganda'): ?>
                            <span class="font-bold text-blue-700"><?php echo e($answer->selected_option ?? '-'); ?>.</span>
                            <?php echo e($answer->answer_text ?: '(tidak dijawab)'); ?>

                        <?php else: ?>
                            <?php echo e($answer->answer_text ?: '(tidak dijawab)'); ?>

                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex-shrink-0 text-center">
                    <div class="text-lg font-bold <?php echo e($answer->estimated_score >= 4 ? 'text-green-600' : ($answer->estimated_score >= 2 ? 'text-yellow-600' : 'text-red-500')); ?>">
                        <?php echo e($answer->estimated_score); ?>

                    </div>
                    <div class="text-xs text-gray-400">/<?php echo e($answer->question->points); ?></div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="mt-4 text-center">
        <a href="<?php echo e(route('ujian.enter-code')); ?>"
           class="inline-block text-sm bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl transition font-semibold shadow">
            ← Kembali ke Daftar Ujian
        </a>
    </div>

    <div class="mt-4 text-center text-xs text-gray-400">
        Estimasi nilai adalah perkiraan awal berdasarkan kata kunci. Nilai akhir ditentukan oleh dosen.
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\uas-pemrograman-web\resources\views/exam/hasil.blade.php ENDPATH**/ ?>