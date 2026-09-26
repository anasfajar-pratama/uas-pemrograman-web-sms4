<?php $__env->startSection('title', 'Dashboard Dosen'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto">

    
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Dosen</h1>
            <p class="text-sm text-gray-500">Pemantauan Ujian Akhir Semester — Pemrograman Web</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="<?php echo e(route('dosen.ujian.index')); ?>"
               class="text-sm bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl transition flex items-center gap-2 shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Kelola Ujian
            </a>
            <a href="<?php echo e(route('dosen.export')); ?>"
               class="text-sm bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2.5 rounded-xl transition flex items-center gap-2 shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </a>
        </div>
    </div>

    
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <div class="text-3xl font-black text-blue-600"><?php echo e($totalMahasiswa); ?></div>
            <div class="text-sm text-gray-500 mt-1">Total Mahasiswa</div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <div class="text-3xl font-black text-green-600"><?php echo e($sudahSelesai); ?></div>
            <div class="text-sm text-gray-500 mt-1">Sudah Selesai</div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <div class="text-3xl font-black text-yellow-600"><?php echo e($sedangUjian); ?></div>
            <div class="text-sm text-gray-500 mt-1">Sedang Ujian</div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <div class="text-3xl font-black text-gray-400"><?php echo e($belumMulai); ?></div>
            <div class="text-sm text-gray-500 mt-1">Belum Mulai</div>
        </div>
    </div>

    
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
                    <?php $__empty_1 = true; $__currentLoopData = $mahasiswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $session = $mhs->examSession;
                        $status  = !$session ? 'belum' : ($session->isFinished() ? 'selesai' : 'ujian');
                        $statusLabel = [
                            'belum'  => ['text' => 'Belum Mulai',    'class' => 'bg-gray-100 text-gray-500'],
                            'ujian'  => ['text' => 'Sedang Ujian',   'class' => 'bg-yellow-100 text-yellow-700'],
                            'selesai'=> ['text' => 'Selesai',        'class' => 'bg-green-100 text-green-700'],
                        ][$status];
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-gray-500"><?php echo e($i + 1); ?></td>
                        <td class="px-5 py-3 font-mono font-semibold text-gray-700"><?php echo e($mhs->nim ?? '-'); ?></td>
                        <td class="px-5 py-3 font-semibold text-gray-800"><?php echo e($mhs->name); ?></td>
                        <td class="px-5 py-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold <?php echo e($statusLabel['class']); ?>">
                                <?php echo e($statusLabel['text']); ?>

                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500">
                            <?php if($session): ?>
                                <?php echo e(round($session->elapsed_seconds / 60, 1)); ?> menit
                                <?php if($session->isTimeUp()): ?> <span class="text-red-500 text-xs">(habis)</span> <?php endif; ?>
                            <?php else: ?> —
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3">
                            <?php if($session && $session->isFinished()): ?>
                                <span class="font-bold <?php echo e($session->estimated_grade >= 70 ? 'text-green-600' : ($session->estimated_grade >= 50 ? 'text-yellow-600' : 'text-red-500')); ?>">
                                    <?php echo e($session->estimated_grade); ?>/100
                                </span>
                            <?php else: ?>
                                <span class="text-gray-400">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3">
                            <?php if($session && $session->expected_grade !== null): ?>
                                <span class="font-semibold text-purple-600"><?php echo e($session->expected_grade); ?>/100</span>
                            <?php else: ?>
                                <span class="text-gray-400">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3">
                            <?php if($session): ?>
                            <a href="<?php echo e(route('dosen.detail', ['id' => $mhs->id, 'exam_id' => $session->exam_id])); ?>"
                               class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg transition font-semibold">
                                Lihat Jawaban
                            </a>
                            <?php else: ?>
                            <span class="text-xs text-gray-400">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                            Belum ada mahasiswa yang terdaftar.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Auto-refresh setiap 60 detik
setTimeout(() => location.reload(), 60000);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\uas-pemrograman-web\resources\views/dosen/dashboard.blade.php ENDPATH**/ ?>