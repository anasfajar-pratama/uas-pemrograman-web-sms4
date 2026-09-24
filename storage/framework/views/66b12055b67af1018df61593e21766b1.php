<?php $__env->startSection('title', 'Kelola Soal — ' . $exam->name); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto">

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('dosen.ujian.show', $exam->id)); ?>" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Soal: <?php echo e($exam->name); ?></h1>
                <p class="text-sm text-gray-500"><?php echo e($questions->count()); ?> soal | Total <?php echo e($questions->sum('points')); ?> poin</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('dosen.ujian.soal.import', $exam->id)); ?>"
               class="text-sm bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl transition font-semibold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                Import Excel
            </a>
            <a href="<?php echo e(route('dosen.ujian.soal.create', $exam->id)); ?>"
               class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl transition font-semibold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Soal
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-500 uppercase tracking-wider bg-gray-50">
                        <th class="px-5 py-3">No</th>
                        <th class="px-5 py-3">Section</th>
                        <th class="px-5 py-3">Tipe</th>
                        <th class="px-5 py-3">Soal</th>
                        <th class="px-5 py-3">Poin</th>
                        <th class="px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3">
                            <div class="w-7 h-7 num-<?php echo e($q->section); ?> rounded-full flex items-center justify-center text-white font-bold text-xs">
                                <?php echo e($q->number); ?>

                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <span class="section-<?php echo e($q->section); ?> text-xs font-bold px-2.5 py-0.5 rounded-full"><?php echo e($q->section_label); ?></span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full"><?php echo e($q->type_label); ?></span>
                        </td>
                        <td class="px-5 py-3">
                            <p class="text-gray-700 max-w-md truncate"><?php echo e($q->question_text); ?></p>
                        </td>
                        <td class="px-5 py-3 font-semibold text-gray-700"><?php echo e($q->points); ?></td>
                        <td class="px-5 py-3">
                            <div class="flex gap-2">
                                <a href="<?php echo e(route('dosen.ujian.soal.edit', [$exam->id, $q->id])); ?>"
                                   class="text-xs bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg transition font-semibold">Edit</a>
                                <form method="POST" action="<?php echo e(route('dosen.ujian.soal.destroy', [$exam->id, $q->id])); ?>"
                                      onsubmit="return confirm('Hapus soal ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="text-xs bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg transition font-semibold">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            Belum ada soal.
                            <div class="mt-3 flex gap-3 justify-center">
                                <a href="<?php echo e(route('dosen.ujian.soal.create', $exam->id)); ?>" class="text-blue-600 font-semibold text-sm hover:underline">Tambah soal manual</a>
                                <span class="text-gray-300">|</span>
                                <a href="<?php echo e(route('dosen.ujian.soal.import', $exam->id)); ?>" class="text-green-600 font-semibold text-sm hover:underline">Import dari Excel</a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\uas-pemrograman-web\resources\views/dosen/ujian/soal/index.blade.php ENDPATH**/ ?>