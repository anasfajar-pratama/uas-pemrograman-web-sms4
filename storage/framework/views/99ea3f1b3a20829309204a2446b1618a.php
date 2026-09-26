<?php $__env->startSection('title', 'Import Soal Excel'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="<?php echo e(route('dosen.ujian.soal.index', $exam->id)); ?>" class="text-gray-500 hover:text-gray-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-xl font-bold text-gray-900">Import Soal — <?php echo e($exam->name); ?></h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 mb-6 text-sm text-green-800">
            <b>Format kolom Excel:</b>
            <div class="mt-2 overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-green-100">
                            <th class="px-2 py-1 text-left">No</th>
                            <th class="px-2 py-1 text-left">Section</th>
                            <th class="px-2 py-1 text-left">Tipe</th>
                            <th class="px-2 py-1 text-left">Soal</th>
                            <th class="px-2 py-1 text-left">Opsi A</th>
                            <th class="px-2 py-1 text-left">Opsi B</th>
                            <th class="px-2 py-1 text-left">Opsi C</th>
                            <th class="px-2 py-1 text-left">Opsi D</th>
                            <th class="px-2 py-1 text-left">Jawaban</th>
                            <th class="px-2 py-1 text-left">Keywords</th>
                            <th class="px-2 py-1 text-left">Poin</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <ul class="mt-2 space-y-1 text-xs">
                <li><b>Section:</b> teori / logika / coding</li>
                <li><b>Tipe:</b> pilihan_ganda / isian / coding</li>
                <li><b>Jawaban PG:</b> huruf A, B, C, atau D</li>
                <li><b>Keywords:</b> pisahkan dengan koma (untuk isian/coding)</li>
                <li>Untuk isian/coding: kolom Opsi A-D boleh dikosongkan</li>
            </ul>
        </div>

        <div class="mb-6">
            <a href="<?php echo e(route('dosen.ujian.soal.template', $exam->id)); ?>"
               class="inline-flex items-center gap-2 text-sm bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2.5 rounded-xl transition font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download Template Excel
            </a>
        </div>

        <form method="POST" action="<?php echo e(route('dosen.ujian.soal.import.store', $exam->id)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Upload File Excel <span class="text-red-500">*</span></label>
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="flex gap-3">
                <a href="<?php echo e(route('dosen.ujian.soal.index', $exam->id)); ?>"
                   class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl transition text-sm">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl transition text-sm shadow">
                    Import Soal
                </button>
            </div>
        </form>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\uas-pemrograman-web\resources\views/dosen/ujian/import.blade.php ENDPATH**/ ?>