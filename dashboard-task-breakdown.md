# Breakdown Task Perbaikan Dashboard

Dokumen ini menguraikan temuan review dashboard menjadi pekerjaan implementasi, acceptance criteria, dan verifikasi.

## Prioritas

| Prioritas | Area | Dampak |
|---|---|---|
| P1 | Isolasi cache berdasarkan akses pengguna | Risiko data ringkasan dan lokasi partner tampil kepada pengguna dengan cakupan akses berbeda |
| P2 | Route endpoint peta | Endpoint peta gagal karena nama method route tidak sesuai |
| P2 | Data dashboard masih statis/demo | Angka dan chart tidak mewakili data operasional |
| P2 | Ringkasan partner per provinsi untuk departemen lain | Chart dapat kosong untuk admin/super-user selain Sales dan Purchasing |

## P1: Isolasi Cache Dashboard

**Temuan:** Cache `fetchDataCount()` menggunakan status super-admin dan kantor sebagai key. Kunci tersebut tidak membedakan semua cakupan role/departemen, sementara query super-user dapat mengembalikan data tanpa filter.

### Pekerjaan

- Audit seluruh nilai dalam payload cache, khususnya daftar lokasi partner yang memuat nama dan alamat.
- Ubah cache key agar mewakili identitas dan cakupan akses pengguna, minimal user ID, role, department ID, dan office/location ID.
- Pastikan perubahan role, department, atau office tidak dapat membaca payload dari cakupan sebelumnya.
- Pertahankan TTL cache saat ini kecuali ada kebutuhan invalidasi yang disepakati.

### Acceptance criteria

- Pengguna dengan akses berbeda tidak pernah menerima payload cache yang sama hanya karena berbagi office.
- Super-admin/super-user hanya berbagi cache jika keputusan akses dan payload memang identik.
- Daftar partner location mengikuti batas akses yang sama dengan ringkasan lainnya.

### Verifikasi

- Tambahkan feature test dengan dua pengguna pada office yang sama tetapi role/cakupan berbeda.
- Uji bahwa payload pengguna terbatas tidak mengandung partner di luar department/office-nya.
- Uji bahwa perubahan role atau cakupan membuat cache key berbeda.

## P2: Perbaiki Route Endpoint Peta

**Temuan:** Route `fetch-map-point` memanggil `DashboardController::mapjPoint`, sedangkan method controller bernama `mapPoint`.

### Pekerjaan

- Samakan action route dengan method controller yang tersedia.
- Pastikan nama route `fetch-map-point` tetap dipertahankan agar kompatibel dengan pemanggil yang sudah ada.
- Konfirmasi middleware endpoint sesuai dengan cakupan data peta.

### Acceptance criteria

- Request terautentikasi ke `fetch-map-point` tidak menghasilkan 500 akibat method tidak ditemukan.
- Respons peta mengikuti filter role, department, dan office yang berlaku.
- Pengguna tanpa akses tetap ditolak middleware.

### Verifikasi

- Tambahkan feature test untuk respons sukses dan pembatasan akses.
- Jalankan request endpoint dengan role super-admin dan role terbatas.

## P2: Ganti Data Statis dan Chart Demo

**Temuan:** View menampilkan nilai contoh seperti Revenue, Page Views, Sales, Growth, dan Server Load. Script chart menghasilkan data acak dan tidak memanggil endpoint dashboard.

### Pekerjaan

- Tentukan definisi bisnis, rentang waktu, satuan, dan cakupan akses untuk setiap kartu/chart yang ingin dipertahankan.
- Petakan metrik ke data/controller yang benar; jangan menampilkan angka contoh sebagai data produksi.
- Ganti placeholder dengan nilai dari endpoint dashboard atau data yang dirender server.
- Ganti series chart acak dengan data nyata; tampilkan empty state saat belum ada data.
- Pastikan kegagalan request memberi state error yang jelas tanpa merusak halaman.
- Tentukan source of truth untuk `dashboard.js`: view saat ini memuat `public/assets/js/pages/dashboard.js`, sementara salinan sumber juga ada di `resources/assets/js/pages/dashboard.js`. Pastikan build/deploy tidak menimpa perbaikan dengan salinan lama.

### Acceptance criteria

- Tidak ada nilai statis/demo yang tampil seolah-olah data aktual.
- Kartu dan chart konsisten dengan hasil API untuk role yang sedang login.
- Empty/loading/error states dapat dibedakan dari nilai nol.
- Bundle yang dikirim browser berisi implementasi terbaru.

### Verifikasi

- Feature test untuk payload endpoint dan pembatasan role.
- Test/manual browser check untuk loading, data tersedia, data kosong, dan kegagalan API.
- Bandingkan nilai dashboard dengan query sumber untuk sampel role dan rentang waktu.

## P2: Lengkapi Ringkasan Provinsi untuk Semua Departemen

**Temuan:** Query vendor/customer dijalankan untuk admin/super-user selain Sales dan Purchasing, tetapi hasil tidak dimasukkan ke `$final`; `total_partner_location` menjadi array kosong.

### Pekerjaan

- Tetapkan matriks visibilitas kategori vendor/customer berdasarkan role dan nama/departemen.
- Tambahkan fallback untuk departemen lain agar hasil yang memang diizinkan ikut dikirim, atau ubah query agar kategori yang tidak boleh tampil tidak dijalankan.
- Pastikan kebijakan yang sama diterapkan pada seluruh endpoint ringkasan dan map.

### Acceptance criteria

- Setiap kombinasi role/departemen yang diizinkan menerima kategori yang tepat.
- Departemen Sales hanya menerima kategori sesuai kebijakan Sales; Purchasing sesuai kebijakan Purchasing.
- Departemen lain tidak mendapat payload kosong jika seharusnya dapat melihat data.
- Tidak ada kategori atau lokasi yang bocor di luar kebijakan akses.

### Verifikasi

- Tambahkan test berbasis data untuk super-admin, Sales, Purchasing, dan departemen lain.
- Uji hasil vendor/customer per provinsi serta kondisi tanpa data.

## Urutan Pengerjaan

1. Selesaikan P1 cache-scope dan tambahkan regression test terlebih dahulu.
2. Perbaiki route peta dan verifikasi pembatasan endpoint.
3. Tetapkan matriks akses dashboard, lalu selesaikan ringkasan provinsi.
4. Sepakati definisi metrik bisnis sebelum mengganti kartu/chart statis dengan data produksi.
5. Verifikasi bundle yang benar-benar dimuat browser dan jalankan seluruh feature tests terkait.
