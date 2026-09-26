<?php $__env->startSection('title', 'Detail Ujian — ' . $exam->name); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="<?php echo e(route('dosen.ujian.index')); ?>" class="text-gray-500 hover:text-gray-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div class="flex-1">
            <div class="flex items-center gap-3">
                <h1 class="text-xl font-bold text-gray-900"><?php echo e($exam->name); ?></h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-<?php echo e($exam->status_color); ?>-100 text-<?php echo e($exam->status_color); ?>-700">
                    <?php echo e($exam->status_label); ?>

                </span>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="<?php echo e(route('dosen.ujian.soal.index', $exam->id)); ?>"
               class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl transition font-semibold">Kelola Soal</a>
            <a href="<?php echo e(route('dosen.ujian.soal.import', $exam->id)); ?>"
               class="text-sm bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl transition font-semibold flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                Import Excel
            </a>
            <a href="<?php echo e(route('dosen.ujian.soal.template', $exam->id)); ?>"
               class="text-sm bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl transition font-semibold flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download Template
            </a>
            <a href="<?php echo e(route('dosen.ujian.edit', $exam->id)); ?>"
               class="text-sm bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-xl transition font-semibold">Edit</a>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border p-5 mb-6">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Kode Ujian</div>
                <code class="font-mono font-bold text-lg text-gray-800 select-all"><?php echo e($exam->code); ?></code>
            </div>
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Durasi</div>
                <div class="font-semibold text-gray-700"><?php echo e($exam->duration_minutes); ?> menit</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Mulai</div>
                <div class="font-semibold text-gray-700"><?php echo e($exam->starts_at?->format('d M Y, H:i') ?? 'Bebas'); ?></div>
            </div>
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Berakhir</div>
                <div class="font-semibold text-gray-700"><?php echo e($exam->ends_at?->format('d M Y, H:i') ?? 'Tanpa batas'); ?></div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border text-center">
            <div class="text-3xl font-black text-blue-600"><?php echo e($totalParticipants); ?></div>
            <div class="text-sm text-gray-500 mt-1">Peserta</div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border text-center">
            <div class="text-3xl font-black text-green-600"><?php echo e($finished); ?></div>
            <div class="text-sm text-gray-500 mt-1">Selesai</div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border text-center">
            <div class="text-3xl font-black text-yellow-600"><?php echo e($inProgress); ?></div>
            <div class="text-sm text-gray-500 mt-1">Sedang Ujian</div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border text-center">
            <div class="text-3xl font-black text-purple-600"><?php echo e(round($avgGrade)); ?></div>
            <div class="text-sm text-gray-500 mt-1">Rata-rata Nilai</div>
        </div>
    </div>

    
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
                    <?php $__empty_1 = true; $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-gray-500"><?php echo e($i + 1); ?></td>
                        <td class="px-5 py-3 font-mono font-semibold text-gray-700"><?php echo e($session->user->nim ?? '-'); ?></td>
                        <td class="px-5 py-3 font-semibold text-gray-800"><?php echo e($session->user->name); ?></td>
                        <td class="px-5 py-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold <?php echo e($session->isFinished() ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'); ?>">
                                <?php echo e($session->isFinished() ? 'Selesai' : 'Sedang Ujian'); ?>

                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500"><?php echo e(round($session->elapsed_seconds / 60, 1)); ?> menit</td>
                        <td class="px-5 py-3">
                            <?php if($session->isFinished()): ?>
                                <span class="font-bold <?php echo e($session->estimated_grade >= 70 ? 'text-green-600' : ($session->estimated_grade >= 50 ? 'text-yellow-600' : 'text-red-500')); ?>">
                                    <?php echo e($session->estimated_grade); ?>/100
                                </span>
                            <?php else: ?>
                                <span class="text-gray-400">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3">
                            <a href="<?php echo e(route('dosen.detail', ['id' => $session->user->id, 'exam_id' => $exam->id])); ?>"
                               class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg transition font-semibold">
                                Lihat Jawaban
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">Belum ada peserta.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="mt-6 bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">
            <h2 class="font-bold text-gray-800">Daftar Soal (<?php echo e($questions->count()); ?>)</h2>
            <div class="flex items-center gap-3">
                <button onclick="document.getElementById('preview-modal').classList.remove('hidden')"
                   class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg transition font-semibold flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Preview Soal
                </button>
                <a href="<?php echo e(route('dosen.ujian.soal.index', $exam->id)); ?>"
                   class="text-xs text-blue-600 hover:text-blue-800 font-semibold">Kelola Soal →</a>
            </div>
        </div>
        <div class="divide-y divide-gray-100">
            <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="px-5 py-3 flex items-center gap-3 hover:bg-gray-50">
                <div class="w-7 h-7 num-<?php echo e($q->section); ?> rounded-full flex items-center justify-center text-white font-bold text-xs flex-shrink-0"><?php echo e($q->number); ?></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-700 truncate"><?php echo e($q->question_text); ?></p>
                </div>
                <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full flex-shrink-0"><?php echo e($q->type_label); ?></span>
                <span class="text-xs text-gray-400 flex-shrink-0"><?php echo e($q->points); ?> poin</span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($questions->isEmpty()): ?>
            <div class="px-5 py-8 text-center text-gray-400 text-sm">
                Belum ada soal. <a href="<?php echo e(route('dosen.ujian.soal.create', $exam->id)); ?>" class="text-blue-600 font-semibold">Tambah soal</a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div id="preview-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between flex-shrink-0">
                <div>
                    <h2 class="font-bold text-gray-900 text-lg">Preview Soal — <?php echo e($exam->name); ?></h2>
                    <p class="text-sm text-gray-500"><?php echo e($questions->count()); ?> soal | Total <?php echo e($questions->sum('points')); ?> poin</p>
                </div>
                <button onclick="document.getElementById('preview-modal').classList.add('hidden')"
                   class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 transition text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="overflow-y-auto flex-1 p-6">
                <?php
                    $sections = [
                        'teori'  => ['label' => 'Bagian — Teori',    'color' => 'blue'],
                        'logika' => ['label' => 'Bagian — Logika',   'color' => 'yellow'],
                        'coding' => ['label' => 'Bagian — Coding',   'color' => 'green'],
                    ];
                    $currentSection = null;
                ?>

                <?php $__empty_1 = true; $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php if($currentSection !== $q->section): ?>
                        <?php $currentSection = $q->section; $sec = $sections[$currentSection] ?? ['label' => ucfirst($currentSection), 'color' => 'gray']; ?>
                        <div class="mt-6 mb-3 first:mt-0">
                            <span class="inline-block section-<?php echo e($currentSection); ?> text-xs font-bold tracking-widest uppercase px-4 py-1.5 rounded-full">
                                <?php echo e($sec['label']); ?>

                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="border border-gray-200 rounded-xl p-4 mb-3 hover:border-gray-300 transition">
                        <div class="flex items-start gap-3 mb-3">
                            <div class="flex-shrink-0 w-8 h-8 num-<?php echo e($q->section); ?> rounded-full flex items-center justify-center text-white font-bold text-sm">
                                <?php echo e($q->number); ?>

                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <p class="text-sm leading-relaxed text-gray-800 whitespace-pre-line"><?php echo e($q->question_text); ?></p>
                                    <div class="flex-shrink-0 flex items-center gap-1.5">
                                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded"><?php echo e($q->type_label); ?></span>
                                        <span class="text-xs font-bold bg-gray-100 text-gray-500 px-2 py-0.5 rounded"><?php echo e($q->points); ?> poin</span>
                                    </div>
                                </div>

                                <?php if($q->type === 'pilihan_ganda' && !empty($q->options)): ?>
                                    <div class="mt-3 space-y-1.5">
                                        <?php $__currentLoopData = $q->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $letter => $optText): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex items-start gap-2 text-sm <?php echo e(strtoupper($q->answer_key) === $letter ? 'bg-green-50 border border-green-200 rounded-lg px-3 py-1.5' : 'px-3 py-1.5'); ?>">
                                                <span class="font-bold text-blue-700"><?php echo e($letter); ?>.</span>
                                                <span class="text-gray-700"><?php echo e($optText); ?></span>
                                                <?php if(strtoupper($q->answer_key) === $letter): ?>
                                                    <span class="ml-auto text-xs text-green-600 font-semibold">✓ Kunci</span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="mt-3 pt-3 border-t border-gray-100 flex flex-wrap gap-x-6 gap-y-1 text-xs">
                                    <div>
                                        <span class="text-gray-400">Kunci Jawaban:</span>
                                        <span class="font-semibold text-gray-700 ml-1"><?php echo e($q->answer_key); ?></span>
                                    </div>
                                    <?php if(!empty($q->keywords)): ?>
                                        <div>
                                            <span class="text-gray-400">Keywords:</span>
                                            <span class="text-gray-600 ml-1"><?php echo e(implode(', ', $q->keywords)); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center text-gray-400 py-8">Belum ada soal.</div>
                <?php endif; ?>
            </div>
            <div class="px-6 py-3 border-t bg-gray-50 flex justify-end flex-shrink-0">
                <button onclick="document.getElementById('preview-modal').classList.add('hidden')"
                   class="text-sm bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition font-semibold">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\uas-pemrograman-web\resources\views/dosen/ujian/show.blade.php ENDPATH**/ ?>