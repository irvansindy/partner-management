# Snapshot wilayah dan kode pos

Data ini dipakai `RegionPostalCodeSeeder` melalui `ValteraRegionImportService`. Tidak ada download saat `php artisan db:seed` dijalankan.

- Sumber utama: https://github.com/danikz/valtera-indonesia-regions, commit `3010d74241350cccfc59f4b2356bf9da65d27e60`.
- Pelengkap: https://github.com/mrayhanfadil/kodepos-id, commit `33d362f827030606ab15a4f29a42c22c2eba3c7b`.
- `kodepos-papua-barat-daya.csv` hanya memuat 1.009 baris dengan `provinsi == Papua Barat Daya` dari `kodepos_parsed.csv`; kolom sumber dipertahankan.
- Lisensi masing-masing sumber disertakan pada `LICENSE.valtera` dan `LICENSE.kodepos-id`.
- `manifest.json` mencatat commit, jumlah baris file dan SHA-256 snapshot yang dibundel.

Setelah normalisasi Papua Barat Daya, hasil validasi adalah 38 provinsi, 514 kabupaten/kota, 7.272 kecamatan dan 83.559 baris desa berkode pos valid. Sebanyak 204 baris desa tanpa kode pos valid dilewati; tidak dibuatkan kode pos perkiraan. Jumlah record database dapat lebih kecil karena kombinasi kode pos yang sama tidak disisipkan dua kali.

Snapshot digunakan offline dan tidak otomatis mengikuti perubahan GitHub. Jika diperbarui, sertakan kembali lisensi, metadata dan hasil pengujian importer. Folder ini harus ikut dalam commit atau salinan proyek ke laptop baru.
