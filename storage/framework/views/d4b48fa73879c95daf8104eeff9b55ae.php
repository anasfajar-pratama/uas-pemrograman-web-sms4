<?php $__env->startSection('title', 'Kelola Ujian'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto">

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Ujian</h1>
            <p class="text-sm text-gray-500">Buat dan kelola ujian dengan kode akses</p>
        </div>
        <a href="<?php echo e(route('dosen.ujian.create')); ?>"
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl transition text-sm shadow flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Ujian Baru
        </a>
    </div>

    <div class="grid gap-4">
        <?php $__empty_1 = true; $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white rounded-2xl shadow-sm border p-5 hover:shadow-md transition">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2">
                        <h2 class="font-bold text-lg text-gray-900 truncate"><?php echo e($exam->name); ?></h2>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-<?php echo e($exam->status_color); ?>-100 text-<?php echo e($exam->status_color); ?>-700">
                            <?php echo e($exam->status_label); ?>

                        </span>
                    </div>
                    <div class="flex flex-wrap gap-3 text-sm text-gray-500 mb-3">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <?php echo e($exam->duration_minutes); ?> menit
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <?php echo e($exam->questions_count); ?> soal
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <?php echo e($exam->sessions_count); ?> peserta
                        </span>
                        <?php if($exam->starts_at): ?>
                        <span class="flex items-center gap-1">
                            Mulai: <?php echo e($exam->starts_at->format('d M Y, H:i')); ?>

                        </span>
                        <?php endif; ?>
                        <?php if($exam->ends_at): ?>
                        <span class="flex items-center gap-1">
                            Berakhir: <?php echo e($exam->ends_at->format('d M Y, H:i')); ?>

                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center gap-2">
                        <code class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-sm font-mono font-bold select-all"><?php echo e($exam->code); ?></code>
                        <button onclick="navigator.clipboard.writeText('<?php echo e($exam->code); ?>').then(()=>this.textContent='Copied!').catch(()=>{})"
                                class="text-xs text-blue-600 hover:text-blue-800 font-semibold">Copy</button>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="<?php echo e(route('dosen.ujian.show', $exam->id)); ?>"
                       class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg transition font-semibold">Detail</a>
                    <a href="<?php echo e(route('dosen.ujian.soal.index', $exam->id)); ?>"
                       class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg transition font-semibold">Soal</a>
                    <a href="<?php echo e(route('dosen.ujian.edit', $exam->id)); ?>"
                       class="text-xs bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg transition font-semibold">Edit</a>
                    <form method="POST" action="<?php echo e(route('dosen.ujian.toggle', $exam->id)); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <button type="submit"
                                class="text-xs px-3 py-2 rounded-lg transition font-semibold <?php echo e($exam->is_active ? 'bg-red-100 hover:bg-red-200 text-red-700' : 'bg-green-100 hover:bg-green-200 text-green-700'); ?>">
                            <?php echo e($exam->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>

                        </button>
                    </form>
                    <form method="POST" action="<?php echo e(route('dosen.ujian.destroy', $exam->id)); ?>"
                          onsubmit="return confirm('Hapus ujian ini beserta semua soal dan data peserta?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="text-xs bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg transition font-semibold">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="bg-white rounded-2xl shadow-sm border p-12 text-center">
            <div class="text-4xl mb-3">📝</div>
            <h3 class="font-bold text-gray-700 mb-1">Belum ada ujian</h3>
            <p class="text-sm text-gray-500 mb-4">Buat ujian pertama Anda untuk memulai.</p>
            <a href="<?php echo e(route('dosen.ujian.create')); ?>"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl transition text-sm">
                Buat Ujian Baru
            </a>
        </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\uas-pemrograman-web\resources\views/dosen/ujian/index.blade.php ENDPATH**/ ?>