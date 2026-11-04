# Bilingual Runtime Design

## Intent

Menyelesaikan dukungan Bahasa Indonesia dan Inggris pada form publik serta area aplikasi yang sudah menyediakan pemilih bahasa. Pergantian bahasa harus konsisten untuk Blade, JavaScript, pesan validasi, dan format tanggal tanpa menggantungkan runtime aplikasi pada layanan terjemahan eksternal.

## Goals

- Laravel native translation menjadi sumber teks runtime melalui `__()`, `@lang`, dan locale session.
- Form publik menyediakan pemilih Bahasa Indonesia dan Inggris yang dapat digunakan dengan keyboard.
- Teks statis pada shell form publik, wizard, tombol, pesan validasi, peta, dan upload menggunakan translation key.
- Locale Carbon mengikuti locale aktif pada setiap request.
- Translation Manager hanya dapat diakses role `super-admin`.
- Google Translate tetap opsional untuk membantu mengisi terjemahan melalui environment variable, bukan untuk menerjemahkan halaman saat request berlangsung.
- File bahasa Indonesia dan Inggris memiliki key yang sama serta fallback yang dapat diprediksi.

## Non-goals

- Tidak menambahkan bahasa selain `id` dan `en`.
- Tidak menerjemahkan data buatan admin seperti judul/deskripsi Form Link karena model belum memiliki kolom per-locale.
- Tidak memanggil Google Translate saat pengguna membuka atau mengirim form.
- Tidak mengubah identitas visual form publik.

## Constraints

- Project menggunakan Laravel 8 dan session locale yang sudah ada.
- Form publik memuat banyak HTML dinamis dari JavaScript sehingga translation copy harus dikirim dari Blade sebagai data JSON yang aman.
- Package `barryvdh/laravel-translation-manager` tetap dipertahankan untuk pengelolaan terjemahan.
- API key Google Translate tidak boleh disimpan dalam repository.

## Approaches

### Selected: Hybrid native runtime

Laravel native menangani seluruh teks saat runtime. Translation Manager hanya mengimpor, mengedit, dan mengekspor file bahasa. Google Translate digunakan secara eksplisit oleh administrator ketika diperlukan.

Kelebihan: runtime stabil, tidak bergantung jaringan atau database translation, perubahan sesuai struktur project saat ini, dan mudah diuji. Kekurangan: setiap copy baru tetap harus memakai translation key.

### Rejected: Database/package-driven runtime

Seluruh teks dibaca dan dikelola melalui package/database. Pendekatan ini menambah coupling, risiko cache atau database, dan membutuhkan perubahan besar pada view yang sudah memakai translator Laravel.

## Runtime Flow

1. Pengguna memilih `id` atau `en` melalui route locale yang sudah ada.
2. `LocaleController` memvalidasi locale dan menyimpannya ke session.
3. `SetLocale` membaca session pada request berikutnya, menetapkan locale Laravel dan Carbon.
4. Blade mengambil teks dari `resources/lang/{locale}/messages.php`.
5. JavaScript menerima copy terjemahan melalui `@json()` dan tidak menyimpan hardcoded copy bilingual sendiri.
6. Jika key tidak tersedia, Laravel memakai fallback locale yang dikonfigurasi.

## Translation Administration

- Route `/translations` tetap disediakan package tetapi middleware ditambah `role:super-admin`.
- Controller custom yang tidak terhubung route dihapus setelah dipastikan tidak direferensikan.
- Konfigurasi Google Translate dipublish atau dibuat dengan `GOOGLE_TRANSLATE_API_KEY` nullable.
- Tanpa API key, aplikasi dan pergantian bahasa tetap berfungsi; hanya auto-translate administratif yang tidak tersedia.

## UI Direction

Reading this as: portal registrasi B2B untuk vendor eksternal, memakai bahasa visual form publik yang sudah ada, dial ENERGY 1 / RHYTHM 1 / MOTION 1.

- Pemilih bahasa ditempatkan di topbar karena berlaku untuk seluruh halaman.
- Kontrol menggunakan label bahasa yang jelas, bukan ikon tanpa keterangan, agar dapat dipahami dan dioperasikan lewat keyboard.
- Warna, tipografi, dan spacing mengikuti komponen topbar yang ada; tidak ditambahkan dekorasi baru.
- Locale aktif diberi penanda teks dan state `aria-current` agar status tidak hanya bergantung pada warna.

## Error and Fallback Behavior

- Locale di luar allowlist mengembalikan HTTP 400 seperti perilaku saat ini.
- Session locale yang tidak valid akan kembali ke locale default.
- Pesan validasi server menggunakan translation key atau memilih sisi pesan bilingual sesuai locale aktif selama migrasi copy berlangsung.
- Teks yang berasal dari data admin tetap ditampilkan apa adanya.

## Verification

- Feature test memastikan switch locale disimpan dan locale tidak valid ditolak.
- Test memastikan Carbon mengikuti `id` dan `en`.
- Test memastikan route Translation Manager ditolak untuk user non-super-admin.
- Structural/UI test memastikan form publik memiliki pemilih bahasa dan copy utama tidak hardcoded.
- Pemeriksaan parity key `id` dan `en`.
- Full PHPUnit suite, route inspection, Blade compilation, dan diff check.

