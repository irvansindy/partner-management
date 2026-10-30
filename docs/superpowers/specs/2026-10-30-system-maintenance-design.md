# System Maintenance Panel Design

## Intent

Menyediakan satu halaman internal agar super-admin dapat menjalankan command maintenance Laravel yang sudah disetujui tanpa akses terminal. Fitur ini ditujukan untuk operasi rutin, bukan terminal web atau pengganti deployment pipeline.

## Access and security

- Seluruh route menggunakan middleware `auth` dan `role:super-admin`.
- Eksekusi hanya melalui `POST` dengan CSRF.
- Command dipilih memakai identifier internal dari allowlist; tidak ada nama command atau argument bebas dari request.
- Setiap command memakai application lock untuk mencegah eksekusi paralel dan double-click.
- Output ditampilkan sebagai teks ter-escape dan dibatasi panjangnya.
- Setiap percobaan menyimpan user, command, status, durasi, dan waktu ke application log tanpa environment variables atau secrets.
- Command destruktif seperti `db:wipe`, `migrate:fresh`, `key:generate`, `tinker`, `queue:flush`, dan command `make:*` tidak tersedia.

## Command groups

### Cache and optimization

- `optimize`
- `optimize:clear`
- `cache:clear`
- `config:cache`
- `config:clear`
- `route:cache`
- `route:clear`
- `view:cache`
- `view:clear`
- `event:cache`
- `event:clear`
- `permission:cache-reset`

### Worker and scheduler

- `queue:restart`
- `schedule:clear-cache`

### Security and cleanup

- `auth:clear-resets`
- `sanctum:prune-expired`

### Backup

- `backup:monitor`
- `backup:list`
- `backup:run`
- `backup:db`

Backup diberi batas waktu/lock lebih panjang dan UI menjelaskan bahwa proses dapat memerlukan waktu lebih lama.

### Diagnostics

- `migrate:status`
- `queue:failed`

## Interface

- Menu baru `System Maintenance` menggunakan permission `system-maintenance` dan hanya diberikan kepada role `super-admin`.
- Halaman mengikuti layout admin yang sedang digunakan.
- Command dikelompokkan berdasarkan fungsi, masing-masing menampilkan nama, penjelasan dampak, dan tombol eksekusi.
- Command yang mengubah state meminta konfirmasi SweetAlert; diagnostics dapat dijalankan langsung.
- Hasil terakhir ditampilkan dalam panel yang membedakan sukses dan gagal menggunakan teks serta ikon, bukan warna saja.
- Tombol dinonaktifkan selama request berlangsung dan dapat digunakan dengan keyboard.

## Implementation approach

Pendekatan yang dipilih adalah eksekusi sinkron melalui `Artisan::call()` untuk command singkat, dengan allowlist terpusat pada service. Backup tetap melalui request yang sama tetapi menggunakan lock dan timeout lebih panjang. Pendekatan queue ditunda sampai terdapat bukti backup melampaui batas request production.

## Data and deployment

- Tidak memerlukan tabel baru; audit operasional menggunakan application log.
- Data migration membuat menu dan permission secara idempotent serta memberikannya hanya kepada role `super-admin`.
- Rollback menghapus menu dan assignment yang dibuat fitur tanpa menghapus role.

## Verification

- Feature test memastikan guest dan role lain ditolak.
- Feature/unit test memastikan hanya identifier allowlist yang dapat dijalankan.
- Test memastikan route eksekusi hanya POST dan menggunakan middleware super-admin.
- Test memastikan menu/permission migration hanya memberi akses ke super-admin.
- Seluruh test suite dan kompilasi Blade harus berhasil.
