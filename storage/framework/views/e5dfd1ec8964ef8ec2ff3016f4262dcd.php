<?php $__env->startSection('title', 'Masuk Ujian'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-lg mx-auto mt-12">

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-8 py-8 text-white text-center">
            <svg class="w-16 h-16 mx-auto mb-3 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
            <h1 class="text-2xl font-bold">Masuk Ujian</h1>
            <p class="text-blue-200 text-sm mt-1">Masukkan kode ujian yang diberikan dosen Anda</p>
        </div>

        <form method="POST" action="<?php echo e(route('ujian.submit-code')); ?>" class="px-8 py-8">
            <?php echo csrf_field(); ?>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Kode Ujian</label>
                <input type="text" name="code" value="<?php echo e(old('code')); ?>" required autofocus
                       placeholder="Contoh: UAS-7X3K9M"
                       class="w-full px-5 py-4 border-2 rounded-xl text-lg font-mono font-bold text-center tracking-widest uppercase focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-sm text-red-600 font-semibold"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition text-base shadow-lg">
                Masuk Ujian →
            </button>
        </form>
    </div>

    <div class="mt-6 text-center text-sm text-gray-500">
        <p>Belum punya akun? <a href="<?php echo e(route('register')); ?>" class="text-blue-600 font-semibold hover:underline">Daftar di sini</a></p>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\uas-pemrograman-web\resources\views/exam/enter-code.blade.php ENDPATH**/ ?>