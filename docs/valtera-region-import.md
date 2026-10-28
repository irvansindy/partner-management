# Import Wilayah dan Kode Pos Indonesia

Sistem memakai snapshot lokal dari [`danikz/valtera-indonesia-regions`](https://github.com/danikz/valtera-indonesia-regions). Browser public form tidak mengambil data langsung dari CDN pihak ketiga.

## Sumber yang dibutuhkan

Direktori input wajib berisi:

- `provinces.csv`
- `regencies.csv`
- `districts.csv`
- `villages.csv`

Ambil snapshot sumber dan catat commit yang digunakan:

```bash
git clone https://github.com/danikz/valtera-indonesia-regions.git /tmp/valtera-indonesia-regions
git -C /tmp/valtera-indonesia-regions rev-parse HEAD
```

Valtera belum menyediakan nama kecamatan dan kode pos Papua Barat Daya. Unduh snapshot pelengkap [`mrayhanfadil/kodepos-id`](https://github.com/mrayhanfadil/kodepos-id):

```bash
curl -L https://raw.githubusercontent.com/mrayhanfadil/kodepos-id/main/kodepos_parsed.csv \
  -o /tmp/kodepos_parsed.csv
```

## Menjalankan import

Jalankan migrasi terlebih dahulu:

```bash
php artisan migrate
```

Validasi seluruh relasi dan format tanpa menulis ke database:

```bash
php artisan regions:import-valtera /tmp/valtera-indonesia-regions/dist/csv \
  --supplement=/tmp/kodepos_parsed.csv \
  --dry-run
```

Jika validasi berhasil, jalankan import:

```bash
php artisan regions:import-valtera /tmp/valtera-indonesia-regions/dist/csv \
  --supplement=/tmp/kodepos_parsed.csv
```

Perintah dapat dijalankan ulang. Provinsi dan kabupaten/kota dihubungkan melalui kode wilayah, sedangkan kode pos menggunakan insert idempotent.

## Koreksi Papua Barat Daya

Snapshot Valtera saat integrasi dibuat hanya memuat 37 provinsi dan enam nama kabupaten/kota berkode `92xx` kosong. Importer melakukan koreksi berikut sebelum data ditulis:

- Menambahkan provinsi `96`, Papua Barat Daya.
- Memindahkan Kabupaten Sorong, Sorong Selatan, Raja Ampat, Tambrauw, Maybrat, dan Kota Sorong dari kode lama `92xx` ke `96xx`.
- Menyesuaikan prefix kode kecamatan dan desa turunannya.
- Mengisi nama kecamatan dan kode pos yang kosong dari snapshot pelengkap.

Baris kosong lain yang tidak termasuk koreksi tersebut ditolak. Periksa ulang koreksi ini ketika mengganti snapshot sumber.

## Rollback

Rollback migrasi hanya menghapus kolom kode wilayah. Data provinsi, kabupaten/kota, dan kode pos tidak dihapus otomatis:

```bash
php artisan migrate:rollback --step=1
```
