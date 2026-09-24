<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(int $examId): void
    {
        $questions = [
            // ═══ BAGIAN A — TEORI ═══
            [
                'number'        => 1,
                'section'       => 'teori',
                'type'          => 'isian',
                'question_text' => 'Jelaskan perbedaan antara Search, Filter, dan Sort pada REST API. Berikan masing-masing 1 contoh endpoint URL yang sesuai dengan materi produk!',
                'answer_key'    => "Search — mencari keyword dengan LIKE. Contoh: GET /api/produks?search=intel\nFilter — menyaring nilai spesifik dengan WHERE. Contoh: GET /api/produks?kategori=Hardware\nSort — mengurutkan data dengan ORDER BY. Contoh: GET /api/produks?sort=harga_asc",
                'keywords'      => ['search', 'filter', 'sort', 'like', 'where', 'order by', 'keyword', 'endpoint', 'url', 'produk'],
                'points'        => 5,
            ],
            [
                'number'        => 2,
                'section'       => 'teori',
                'type'          => 'isian',
                'question_text' => 'Apa itu method spoofing di Laravel? Mengapa kita perlu menambahkan _method: PUT saat update produk dengan file gambar? Apa masalah yang terjadi jika tidak menggunakannya?',
                'answer_key'    => "Method spoofing = mensimulasikan PUT/PATCH menggunakan POST dengan menambahkan field _method: PUT di body.\nMasalah: PUT + multipart/form-data menyebabkan \$request->all() kosong karena server/client tidak membaca body dengan benar.\nSolusi: gunakan POST + _method: PUT. Gunakan ini hanya saat update + upload file.",
                'keywords'      => ['method spoofing', 'put', 'post', '_method', 'multipart', 'form-data', 'kosong', 'request', 'upload', 'file'],
                'points'        => 5,
            ],
            [
                'number'        => 3,
                'section'       => 'teori',
                'type'          => 'isian',
                'question_text' => 'Jelaskan fungsi php artisan storage:link dan kapan perintah ini harus dijalankan. Apa yang terjadi jika tidak dijalankan setelah instalasi di server baru?',
                'answer_key'    => "Membuat symbolic link dari public/storage → storage/app/public agar file bisa diakses dari browser.\nHarus dijalankan: setelah instalasi baru, setelah clone project ke server baru, atau setelah fitur upload ditambahkan.\nJika tidak: URL storage mengembalikan 404, gambar tidak tampil.",
                'keywords'      => ['storage:link', 'symbolic link', 'public/storage', 'storage/app/public', 'browser', 'instalasi', '404', 'gambar', 'akses'],
                'points'        => 5,
            ],
            [
                'number'        => 4,
                'section'       => 'teori',
                'type'          => 'isian',
                'question_text' => 'Jelaskan konsep Pagination pada API. Apa keuntungannya dibandingkan mengambil semua data sekaligus? Berikan contoh endpoint yang menggunakan pagination!',
                'answer_key'    => "Pagination = membatasi jumlah data yang dikirim API per permintaan.\nKeuntungan: lebih ringan di server dan client, respons lebih cepat, tidak membebani memori.\nContoh: GET /api/produks?page=1 → mengirim 10 data. GET /api/produks?page=2 → 10 data berikutnya.\nTanpa pagination: database 1000 produk → API kirim 1000 data sekaligus → berat.",
                'keywords'      => ['pagination', 'membatasi', 'per permintaan', 'ringan', 'respons', 'memori', 'page', 'endpoint'],
                'points'        => 5,
            ],
            [
                'number'        => 5,
                'section'       => 'teori',
                'type'          => 'isian',
                'question_text' => 'Jelaskan alur upload gambar di Laravel dari sisi client sampai data tersimpan di database. Gunakan diagram alur teks (panah →) minimal 5 langkah!',
                'answer_key'    => "Client (Form/Postman)\n↓ multipart/form-data\nLaravel Validation (image, mimes, max)\n↓ lolos validasi\nLaravel Storage → simpan ke storage/app/public/produk/\n↓ \$path = \$request->file('gambar')->store('produk','public')\nPath disimpan ke database (kolom gambar)\n↓\nAPI Response → kembalikan URL gambar ke client",
                'keywords'      => ['multipart', 'validasi', 'storage', 'store', 'database', 'path', 'url', 'client', 'gambar', 'response'],
                'points'        => 5,
            ],
            [
                'number'        => 6,
                'section'       => 'teori',
                'type'          => 'isian',
                'question_text' => 'Apa perbedaan antara $fillable dan $hidden di Laravel Model? Berikan masing-masing contoh penggunaannya di model User!',
                'answer_key'    => "\$fillable — daftar kolom yang boleh diisi via mass assignment (create()/update([])). Kolom di luar fillable diabaikan.\n\$hidden — daftar kolom yang disembunyikan dari respons JSON/array.\nContoh: \$fillable = ['name','email','password','foto']; \$hidden = ['password','remember_token'];",
                'keywords'      => ['fillable', 'hidden', 'mass assignment', 'create', 'update', 'json', 'password', 'kolom', 'disembunyikan', 'diisi'],
                'points'        => 5,
            ],
            // ═══ BAGIAN B — LOGIKA ═══
            [
                'number'        => 7,
                'section'       => 'logika',
                'type'          => 'isian',
                'question_text' => "Perhatikan skenario berikut:\n// User model: \$fillable = ['name', 'email', 'password'] // (foto TIDAK ada di \$fillable)\n\$user = auth()->user();\n\$user->update(['foto' => 'http://domain.com/storage/foto.jpg']);\n\nApakah data foto tersimpan ke database? Jelaskan alasannya dan tuliskan cara yang benar untuk menyimpannya!",
                'answer_key'    => "TIDAK tersimpan. Laravel mengabaikan diam-diam field di luar \$fillable (Mass Assignment Protection). Tidak ada error yang muncul.\nCara benar:\n\$user->foto = 'http://domain.com/storage/foto.jpg';\n\$user->save();\nAtau tambahkan 'foto' ke array \$fillable di model User.",
                'keywords'      => ['tidak tersimpan', 'fillable', 'mass assignment', 'diabaikan', 'foto', 'save', 'direct assignment'],
                'points'        => 5,
            ],
            [
                'number'        => 8,
                'section'       => 'logika',
                'type'          => 'isian',
                'question_text' => "Urutan langkah berikut ini acak. Susunlah menjadi urutan benar untuk membuat fitur Pembelian dari awal:\nA. php artisan make:model PembelianItem\nB. php artisan make:migration create_pembelian_items_table\nC. php artisan migrate\nD. php artisan make:controller Api/PembelianController\nE. php artisan make:migration create_suppliers_table\nF. php artisan make:model Supplier\nG. Tambahkan routes di api.php\nH. php artisan make:migration create_pembelians_table\nI. php artisan make:model Pembelian",
                'answer_key'    => "Urutan benar: E → H → B → C → F → I → A → D → G\nE, H, B — buat semua migration terlebih dahulu\nC — jalankan migrate setelah semua file migration siap\nF, I, A — buat model untuk setiap tabel\nD — buat controller\nG — daftarkan routes terakhir",
                'keywords'      => ['e → h → b → c', 'migration', 'migrate', 'model', 'controller', 'routes', 'urutan', 'terlebih dahulu'],
                'points'        => 5,
            ],
            [
                'number'        => 9,
                'section'       => 'logika',
                'type'          => 'isian',
                'question_text' => "Tabel pembelian_items berisi 3 baris:\nItem 1: quantity=5, harga_beli=10.000\nItem 2: quantity=2, harga_beli=50.000\nItem 3: quantity=10, harga_beli=5.000\nBerapakah total harga pembelian? Tuliskan rumus dan hasil perhitungannya!",
                'answer_key'    => "Rumus: total = Σ (quantity × harga_beli)\nItem 1: 5 × 10.000 = 50.000\nItem 2: 2 × 50.000 = 100.000\nItem 3: 10 × 5.000 = 50.000\nTotal = Rp 200.000",
                'keywords'      => ['200.000', '200000', 'quantity', 'harga_beli', 'rumus', '50.000', '100.000', 'total', 'perhitungan'],
                'points'        => 5,
            ],
            [
                'number'        => 10,
                'section'       => 'logika',
                'type'          => 'isian',
                'question_text' => "Tabel produk_images memiliki relasi:\n\$table->foreignId('produk_id')->constrained()->onDelete('cascade');\nSeorang developer menghapus produk dengan id = 5 yang memiliki 3 gambar di tabel produk_images.\nApa yang terjadi pada data di tabel produk_images? Mengapa?",
                'answer_key'    => "Ketiga baris di produk_images ikut terhapus otomatis dari database.\nKarena onDelete('cascade') pada foreign key — ketika data induk (produk) dihapus, semua data anak (produk_images) yang berelasi ikut dihapus oleh database.\nCatatan: file fisik di storage tetap ada, harus dihapus manual via Storage::delete().",
                'keywords'      => ['cascade', 'terhapus', 'otomatis', 'foreign key', 'induk', 'anak', 'storage', 'delete', 'database'],
                'points'        => 5,
            ],
            [
                'number'        => 11,
                'section'       => 'logika',
                'type'          => 'isian',
                'question_text' => 'Dalam fitur Riwayat Pesanan, pelanggan yang terdaftar mendapat diskon 5%. Seorang pelanggan bernama Budi belanja dengan total Rp 320.000. Berapakah jumlah yang harus dibayar Budi? Tuliskan rumus perhitungannya!',
                'answer_key'    => "Rumus: bayar = total × (1 - diskon/100)\nDiskon: 320.000 × 5% = 16.000\nTotal bayar = 320.000 − 16.000 = Rp 304.000",
                'keywords'      => ['304.000', '304000', '16.000', 'diskon', '5%', 'rumus', 'bayar', 'total', 'perhitungan'],
                'points'        => 5,
            ],
            [
                'number'        => 12,
                'section'       => 'logika',
                'type'          => 'isian',
                'question_text' => "Perhatikan kode React berikut:\nconst [items, setItems] = useState([\n  { produk_id: 1, quantity: 3, harga_beli: 20000 },\n  { produk_id: 2, quantity: 1, harga_beli: 0 },\n  { produk_id: 3, quantity: 0, harga_beli: 15000 },\n]);\nconst validItems = items.filter((i) => i.produk_id && i.quantity > 0);\nBerapa item yang ada di validItems? Jelaskan mengapa!",
                'answer_key'    => "2 item yang masuk ke validItems.\nItem 1 ✓ — produk_id=1 (truthy) dan quantity=3 > 0\nItem 2 ✓ — produk_id=2 (truthy) dan quantity=1 > 0\nItem 3 ✗ — quantity=0, kondisi 0 > 0 adalah false, dibuang",
                'keywords'      => ['2 item', 'dua item', 'quantity', 'truthy', 'false', 'filter', '0 > 0', 'dibuang', 'produk_id'],
                'points'        => 5,
            ],
            // ═══ BAGIAN C — CODING ═══
            [
                'number'        => 13,
                'section'       => 'coding',
                'type'          => 'coding',
                'question_text' => "Lengkapi kode React berikut! Buat komponen yang memanggil GET /api/produks saat pertama kali dimuat, menyimpan hasilnya ke state, dan menampilkan nama_barang setiap produk dalam list.\nimport { ___ } from 'react';\nconst [produks, setProduks] = ___([]);\n___(() => { ... }, []);\nreturn (<ul>{produks.map((p) => (<li ___={p.id}>{p.___}</li>))}</ul>);",
                'answer_key'    => "import { useState, useEffect } from 'react';\nconst [produks, setProduks] = useState([]);\nuseEffect(() => {\n  const fetchData = async () => {\n    const res = await api.get('/produks');\n    setProduks(res.data.data || res.data);\n  };\n  fetchData();\n}, []);\n<li key={p.id}>{p.nama_barang}</li>",
                'keywords'      => ['usestate', 'useeffect', 'setproduks', 'res.data', 'key', 'nama_barang', 'fetchdata', 'async', 'await'],
                'points'        => 5,
            ],
            [
                'number'        => 14,
                'section'       => 'coding',
                'type'          => 'coding',
                'question_text' => "Buatlah method uploadFoto di Laravel ProfilController dengan:\n- Validasi: wajib ada, harus gambar, format jpg/jpeg/png/webp, maks 5MB\n- Simpan ke folder foto-profil disk public\n- Simpan URL ke kolom foto via direct assignment\n- Return JSON: data foto + pesan sukses",
                'answer_key'    => "public function uploadFoto(Request \$request): JsonResponse {\n  \$request->validate(['foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120']);\n  \$user = \$request->user();\n  \$path = \$request->file('foto')->store('foto-profil', 'public');\n  \$url  = url('storage/' . \$path);\n  \$user->foto = \$url;\n  \$user->save();\n  return response()->json(['data' => ['foto' => \$url], 'message' => 'Foto profil berhasil diperbarui']);\n}",
                'keywords'      => ['validate', 'required', 'image', 'mimes', 'max:5120', 'store', 'foto-profil', 'public', 'url', 'save', 'json'],
                'points'        => 5,
            ],
            [
                'number'        => 15,
                'section'       => 'coding',
                'type'          => 'coding',
                'question_text' => "Buatlah komponen React StatusBadge yang menerima props status (\"pending\" / \"diterima\" / \"dibatalkan\") dan menampilkan badge berwarna sesuai status. Gunakan pola STATUS_LABEL seperti di PembelianList.jsx!",
                'answer_key'    => "const STATUS_LABEL = {\n  pending:    { text: 'Pending',    cls: 'bg-yellow-100 text-yellow-700' },\n  diterima:   { text: 'Diterima',   cls: 'bg-green-100 text-green-700'  },\n  dibatalkan: { text: 'Dibatalkan', cls: 'bg-red-100 text-red-700'     },\n};\nexport default function StatusBadge({ status }) {\n  const info = STATUS_LABEL[status] || { text: status, cls: 'bg-gray-100 text-gray-700' };\n  return (<span className={\`... \${info.cls}\`}>{info.text}</span>);\n}",
                'keywords'      => ['status_label', 'pending', 'diterima', 'dibatalkan', 'badge', 'props', 'export default', 'classname', 'info.cls', 'info.text'],
                'points'        => 5,
            ],
            [
                'number'        => 16,
                'section'       => 'coding',
                'type'          => 'coding',
                'question_text' => "Lengkapi method index di ProdukController agar mendukung search (berdasarkan nama_barang) dan pagination (10 per halaman):\n\$query = Produk::___();\nif (\$request->filled('search')) { \$query->where(___, 'LIKE', '%' . ___ . '%'); }\n\$produk = \$query->paginate(___);\nreturn ProdukResource::collection(\$produk);\nIsi tiga bagian ___ yang kosong!",
                'answer_key'    => "(1) query()\n(2) 'nama_barang' dan \$request->search\n(3) 10\n\nLengkap:\n\$query = Produk::query();\n\$query->where('nama_barang', 'LIKE', '%' . \$request->search . '%');\n\$produk = \$query->paginate(10);",
                'keywords'      => ['query()', 'nama_barang', 'request->search', 'paginate(10)', 'like', 'search', '10'],
                'points'        => 5,
            ],
            [
                'number'        => 17,
                'section'       => 'coding',
                'type'          => 'coding',
                'question_text' => "Tuliskan isi migration Laravel untuk tabel suppliers dengan kolom: id (auto), nama (wajib/string), no_hp (nullable), email (nullable, unique), alamat (nullable/text), timestamps.",
                'answer_key'    => "public function up(): void {\n  Schema::create('suppliers', function (Blueprint \$table) {\n    \$table->id();\n    \$table->string('nama');\n    \$table->string('no_hp')->nullable();\n    \$table->string('email')->nullable()->unique();\n    \$table->text('alamat')->nullable();\n    \$table->timestamps();\n  });\n}",
                'keywords'      => ['schema::create', 'suppliers', 'id()', 'string', 'nama', 'no_hp', 'nullable', 'email', 'unique', 'text', 'alamat', 'timestamps'],
                'points'        => 5,
            ],
            [
                'number'        => 18,
                'section'       => 'coding',
                'type'          => 'coding',
                'question_text' => "Buatlah kode React untuk form tambah supplier sederhana menggunakan useState. Form memiliki field nama, no_hp, dan email. Tampilkan 3 input text dengan satu handler onChange yang efisien!",
                'answer_key'    => "const [form, setForm] = useState({ nama: '', no_hp: '', email: '' });\nconst handleChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });\nreturn (<div>\n  <input name=\"nama\"  value={form.nama}  onChange={handleChange} />\n  <input name=\"no_hp\" value={form.no_hp} onChange={handleChange} />\n  <input name=\"email\" value={form.email} onChange={handleChange} />\n</div>);",
                'keywords'      => ['usestate', 'handlechange', 'e.target.name', 'e.target.value', 'spread', '...form', 'nama', 'no_hp', 'email', 'onchange'],
                'points'        => 5,
            ],
            [
                'number'        => 19,
                'section'       => 'coding',
                'type'          => 'coding',
                'question_text' => "Lengkapi potongan kode Laravel berikut agar gambar lama dihapus dari storage sebelum gambar baru disimpan saat update produk:\nif (\$request->hasFile('gambar')) {\n  if (\$produk->gambar) {\n    Storage::disk(___)->delete(___);\n  }\n  \$path = \$request->file('gambar')->store(___, 'public');\n  \$produk->gambar = \$path;\n}\nIsi tiga ___ yang kosong!",
                'answer_key'    => "(1) 'public'\n(2) \$produk->gambar\n(3) 'produk'\n\nStorage::disk('public')->delete(\$produk->gambar);\n\$path = \$request->file('gambar')->store('produk', 'public');",
                'keywords'      => ["'public'", 'produk->gambar', "'produk'", 'disk', 'delete', 'store', 'storage'],
                'points'        => 5,
            ],
            [
                'number'        => 20,
                'section'       => 'coding',
                'type'          => 'coding',
                'question_text' => "Tuliskan routes di api.php (dalam middleware auth:sanctum) untuk fitur Pembelian yang mencakup:\n- CRUD lengkap untuk PembelianController (resource route)\n- CRUD lengkap untuk SupplierController (resource route)\n- Route khusus untuk mengubah status pembelian: PATCH /pembelians/{id}/status",
                'answer_key'    => "Route::middleware('auth:sanctum')->group(function () {\n  Route::apiResource('suppliers', SupplierController::class);\n  Route::apiResource('pembelians', PembelianController::class);\n  Route::patch('pembelians/{id}/status', [PembelianController::class, 'updateStatus']);\n});",
                'keywords'      => ['middleware', 'auth:sanctum', 'apiresource', 'suppliers', 'pembelians', 'patch', 'status', 'updatestatus', 'route::middleware', 'group'],
                'points'        => 5,
            ],
        ];

        foreach ($questions as $q) {
            Question::updateOrCreate(
                ['exam_id' => $examId, 'number' => $q['number']],
                array_merge($q, ['exam_id' => $examId])
            );
        }
    }
}
