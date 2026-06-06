<?php $__env->startSection('title', 'Registrasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-[70vh] flex items-center justify-center py-8">
    <div class="w-full max-w-lg">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Registrasi Ujian</h1>
            <p class="text-sm text-gray-500 mt-1">Daftarkan diri untuk mengikuti UAS</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form method="POST" action="<?php echo e(route('register')); ?>" id="registerForm">
                <?php echo csrf_field(); ?>

                
                <div class="mb-6 border-2 border-dashed border-gray-300 rounded-xl p-4 text-center" id="fotoSection">
                    <p class="text-sm font-semibold text-gray-700 mb-3">📸 Foto Wajah (Wajib)</p>

                    
                    <div class="relative inline-block">
                        <video id="webcam" class="rounded-xl w-48 h-36 object-cover bg-gray-900 mx-auto" autoplay playsinline muted></video>
                        <canvas id="overlay" class="absolute top-0 left-0 w-48 h-36 rounded-xl pointer-events-none"></canvas>
                    </div>
                    <img id="capturedPhoto" class="rounded-xl w-48 h-36 object-cover mx-auto hidden border-2 border-green-400" alt="Foto Wajah">

                    <div class="mt-3 flex gap-2 justify-center">
                        <button type="button" id="btnCapture"
                                class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition font-semibold disabled:opacity-50"
                                disabled>
                            📷 Ambil Foto
                        </button>
                        <button type="button" id="btnRetake"
                                class="text-sm bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition font-semibold hidden">
                            🔄 Ulangi
                        </button>
                    </div>

                    <p id="faceStatus" class="mt-2 text-xs text-gray-500">⏳ Memuat model deteksi wajah...</p>
                    <input type="hidden" name="foto_wajah" id="fotoWajahInput">

                    <?php $__errorArgs = ['foto_wajah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>"
                           placeholder="Nama sesuai KTM"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">NIM <span class="text-red-500">*</span></label>
                    <input type="text" name="nim" value="<?php echo e(old('nim')); ?>"
                           placeholder="Nomor Induk Mahasiswa"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 <?php $__errorArgs = ['nim'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['nim'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>"
                           placeholder="email@kampus.ac.id"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" placeholder="Min. 6 karakter"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 border-gray-300">
                </div>

                <button type="submit" id="btnSubmit" disabled
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4 rounded-xl transition text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    Daftar &amp; Mulai Ujian
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-500">Sudah terdaftar?
                    <a href="<?php echo e(route('login')); ?>" class="text-blue-600 font-semibold hover:underline">Login di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model/';
const video     = document.getElementById('webcam');
const overlay   = document.getElementById('overlay');
const capturedPhoto = document.getElementById('capturedPhoto');
const btnCapture = document.getElementById('btnCapture');
const btnRetake  = document.getElementById('btnRetake');
const faceStatus = document.getElementById('faceStatus');
const fotoInput  = document.getElementById('fotoWajahInput');
const btnSubmit  = document.getElementById('btnSubmit');
const fotoSection = document.getElementById('fotoSection');

let stream = null;
let detectInterval = null;
let faceDetected = false;
let photoTaken = false;

async function loadModels() {
    try {
        // Muat model dari CDN vladmandic (lebih ringan, CDN yang andal)
        const MODEL_CDN = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.14/model/';
        await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_CDN);
        faceStatus.textContent = '✅ Model siap. Arahkan wajah ke kamera.';
        faceStatus.className = 'mt-2 text-xs text-blue-600';
        startWebcam();
    } catch (e) {
        faceStatus.textContent = '⚠️ Gagal memuat model: ' + e.message;
        faceStatus.className = 'mt-2 text-xs text-red-600';
        // Fallback: izinkan foto tanpa deteksi
        startWebcam(true);
    }
}

async function startWebcam(skipDetection = false) {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { width: 320, height: 240 } });
        video.srcObject = stream;
        video.onloadedmetadata = () => {
            video.play();
            if (!skipDetection) startDetection();
            else {
                btnCapture.disabled = false;
                faceStatus.textContent = '⚠️ Deteksi auto dinonaktifkan. Ambil foto manual.';
            }
        };
    } catch(e) {
        faceStatus.textContent = '❌ Izin kamera ditolak. Aktifkan kamera dan refresh.';
        faceStatus.className = 'mt-2 text-xs text-red-600';
    }
}

function startDetection() {
    const ctx = overlay.getContext('2d');
    overlay.width  = video.videoWidth  || 192;
    overlay.height = video.videoHeight || 144;

    detectInterval = setInterval(async () => {
        if (photoTaken) return;
        const result = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ scoreThreshold: 0.4 }));

        ctx.clearRect(0, 0, overlay.width, overlay.height);

        if (result) {
            faceDetected = true;
            faceStatus.textContent = '✅ Wajah terdeteksi! Klik "Ambil Foto".';
            faceStatus.className = 'mt-2 text-xs text-green-600 font-semibold';
            btnCapture.disabled = false;
            fotoSection.className = fotoSection.className.replace('border-gray-300','border-green-400');

            // Gambar kotak wajah
            const box = result.box;
            const scaleX = overlay.width / (video.videoWidth || 320);
            const scaleY = overlay.height / (video.videoHeight || 240);
            ctx.strokeStyle = '#22c55e';
            ctx.lineWidth = 2;
            ctx.strokeRect(box.x*scaleX, box.y*scaleY, box.width*scaleX, box.height*scaleY);
        } else {
            faceDetected = false;
            btnCapture.disabled = true;
            faceStatus.textContent = '👤 Tidak ada wajah terdeteksi. Arahkan wajah ke kamera.';
            faceStatus.className = 'mt-2 text-xs text-gray-500';
        }
    }, 400);
}

function capturePhoto() {
    if (!faceDetected && !document.getElementById('fotoWajahInput').value) {
        faceStatus.textContent = '❌ Pastikan wajah Anda terdeteksi sebelum ambil foto!';
        faceStatus.className = 'mt-2 text-xs text-red-600';
        return;
    }

    // Ambil frame dari video
    const canvas = document.createElement('canvas');
    canvas.width  = video.videoWidth  || 320;
    canvas.height = video.videoHeight || 240;
    canvas.getContext('2d').drawImage(video, 0, 0);

    const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
    fotoInput.value = dataUrl;
    capturedPhoto.src = dataUrl;

    // Tampilkan preview, sembunyikan video
    video.classList.add('hidden');
    overlay.classList.add('hidden');
    capturedPhoto.classList.remove('hidden');
    btnCapture.classList.add('hidden');
    btnRetake.classList.remove('hidden');
    faceStatus.textContent = '✅ Foto wajah berhasil diambil!';
    faceStatus.className = 'mt-2 text-xs text-green-700 font-semibold';
    fotoSection.classList.remove('border-dashed', 'border-gray-300');
    fotoSection.classList.add('border-green-400', 'bg-green-50');

    photoTaken = true;
    clearInterval(detectInterval);

    // Aktifkan tombol daftar
    btnSubmit.disabled = false;

    // Hentikan stream kamera
    if (stream) stream.getTracks().forEach(t => t.stop());
}

function retakePhoto() {
    fotoInput.value = '';
    capturedPhoto.classList.add('hidden');
    video.classList.remove('hidden');
    overlay.classList.remove('hidden');
    btnCapture.classList.remove('hidden');
    btnRetake.classList.add('hidden');
    btnSubmit.disabled = true;
    photoTaken = false;
    faceDetected = false;
    fotoSection.classList.add('border-dashed');
    fotoSection.classList.remove('border-green-400', 'bg-green-50');

    startWebcam();
}

// Validasi sebelum submit
document.getElementById('registerForm').addEventListener('submit', function(e) {
    if (!fotoInput.value) {
        e.preventDefault();
        faceStatus.textContent = '❌ Foto wajah wajib diambil sebelum mendaftar!';
        faceStatus.className = 'mt-2 text-xs text-red-600 font-semibold';
        window.scrollTo(0, 0);
    }
});

btnCapture.addEventListener('click', capturePhoto);
btnRetake.addEventListener('click', retakePhoto);

// Muat model saat halaman siap
window.addEventListener('load', loadModels);
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\uas-pemrograman-web\resources\views/auth/register.blade.php ENDPATH**/ ?>