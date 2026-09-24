<?php $__env->startSection('title', 'Tambah Soal'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="<?php echo e(route('dosen.ujian.soal.index', $exam->id)); ?>" class="text-gray-500 hover:text-gray-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-xl font-bold text-gray-900">Tambah Soal — <?php echo e($exam->name); ?></h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <form method="POST" action="<?php echo e(route('dosen.ujian.soal.store', $exam->id)); ?>" id="questionForm">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-3 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor <span class="text-red-500">*</span></label>
                    <input type="number" name="number" value="<?php echo e(old('number', $nextNumber)); ?>" required min="1"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 <?php $__errorArgs = ['number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Section <span class="text-red-500">*</span></label>
                    <select name="section" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="teori" <?php echo e(old('section') === 'teori' ? 'selected' : ''); ?>>Teori</option>
                        <option value="logika" <?php echo e(old('section') === 'logika' ? 'selected' : ''); ?>>Logika</option>
                        <option value="coding" <?php echo e(old('section') === 'coding' ? 'selected' : ''); ?>>Coding</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Soal <span class="text-red-500">*</span></label>
                    <select name="type" id="typeSelect" onchange="toggleOptions()"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="isian" <?php echo e(old('type') === 'isian' ? 'selected' : ''); ?>>Isian</option>
                        <option value="pilihan_ganda" <?php echo e(old('type') === 'pilihan_ganda' ? 'selected' : ''); ?>>Pilihan Ganda</option>
                        <option value="coding" <?php echo e(old('type') === 'coding' ? 'selected' : ''); ?>>Coding</option>
                    </select>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Soal <span class="text-red-500">*</span></label>
                <textarea name="question_text" rows="4" required
                          class="w-full px-4 py-2.5 border rounded-xl text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 <?php $__errorArgs = ['question_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          placeholder="Tuliskan soal di sini..."><?php echo e(old('question_text')); ?></textarea>
                <?php $__errorArgs = ['question_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div id="optionsSection" class="mb-5" style="display:none">
                <label class="block text-sm font-bold text-gray-700 mb-3">Opsi Jawaban <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 gap-3">
                    <?php $__currentLoopData = ['A','B','C','D']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $letter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0"><?php echo e($letter); ?></span>
                        <input type="text" name="option_<?php echo e(strtolower($letter)); ?>" value="<?php echo e(old('option_'.strtolower($letter))); ?>"
                               placeholder="Opsi <?php echo e($letter); ?>"
                               class="flex-1 px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <p class="text-xs text-gray-500 mt-2">Jawaban benar diisi di kolom "Jawaban" di bawah (ketik A, B, C, atau D).</p>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Jawaban Benar <span class="text-red-500">*</span></label>
                <textarea name="answer_key" rows="3" required
                          class="w-full px-4 py-2.5 border rounded-xl text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 <?php $__errorArgs = ['answer_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          placeholder="Untuk PG: ketik A/B/C/D. Untuk isian/coding: jawaban lengkap..."><?php echo e(old('answer_key')); ?></textarea>
                <?php $__errorArgs = ['answer_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Keywords <span class="text-gray-400 font-normal">(pisah dengan koma)</span></label>
                    <input type="text" name="keywords" value="<?php echo e(old('keywords')); ?>"
                           placeholder="keyword1, keyword2, keyword3"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <p class="text-xs text-gray-500 mt-1">Untuk estimasi skor otomatis pada soal isian/coding.</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Poin <span class="text-red-500">*</span></label>
                    <input type="number" name="points" value="<?php echo e(old('points', 5)); ?>" required min="1" max="100"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
            </div>

            <div class="flex gap-3">
                <a href="<?php echo e(route('dosen.ujian.soal.index', $exam->id)); ?>"
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleOptions() {
    const type = document.getElementById('typeSelect').value;
    document.getElementById('optionsSection').style.display = type === 'pilihan_ganda' ? 'block' : 'none';
}
toggleOptions();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\uas-pemrograman-web\resources\views/dosen/ujian/soal/create.blade.php ENDPATH**/ ?>