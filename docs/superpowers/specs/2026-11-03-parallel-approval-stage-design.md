# Parallel Approval Stage Design

## Intent

Mengubah setiap tahap approval dari tepat satu PIC menjadi satu atau beberapa PIC dengan aturan OR: tindakan pertama dari salah satu PIC menyelesaikan tahap, membatalkan kebutuhan tindakan PIC sejawat, dan membuka tahap berikutnya.

## Goals

- Satu `step_ordering` dapat dimiliki beberapa user pada template dan proses approval.
- Semua PIC pada tahap aktif menerima status `WAITING`.
- Approval atau rejection pertama pada tahap aktif menjadi keputusan tahap tersebut.
- Setelah approval, PIC lain pada tahap yang sama berstatus `SKIPPED` dan seluruh PIC pada tahap berikutnya menjadi `WAITING`.
- Setelah rejection, proses langsung ditolak dan PIC sejawat tidak dapat mengambil tindakan lagi.
- Template lama dengan satu PIC per tahap tetap bekerja tanpa konversi data.
- Form pengaturan dapat menambah/menghapus tahap dan memilih beberapa PIC unik per tahap.

## Non-goals

- Tidak menambahkan quorum, voting, atau kewajiban seluruh PIC menyetujui.
- Tidak mengubah aturan role approver yang saat ini dibatasi ke `admin` dan `super-user`.
- Tidak mengubah histori proses approval yang sudah selesai.

## Constraints

- Tetap memakai `approval_template_details` dan `approval_process_steps`.
- `step_ordering` yang sama menandai anggota satu tahap.
- User tidak boleh muncul lebih dari sekali dalam satu template.
- Aksi approval harus tetap berada dalam transaksi dan memakai row locking untuk mencegah dua PIC memproses tahap yang sama secara bersamaan.

## Chosen Approach

Gunakan baris detail yang ada dan izinkan duplikasi `step_ordering`. Unique constraint `(approval_id, step_ordering)` pada template dan proses dihapus, sedangkan keunikan `(approval_id, user_id)` dipertahankan. Pendekatan tabel grup baru tidak dipilih karena menambah migrasi dan relasi tanpa kebutuhan perilaku tambahan. Penyimpanan user sebagai JSON juga tidak dipilih karena melemahkan integritas referensial dan menyulitkan query approval per user.

## Behavior

1. Form mengirim `approval_stages`, berupa array tahap yang masing-masing berisi array `user_ids`.
2. Controller memvalidasi minimal satu tahap dan satu PIC pada setiap tahap, meratakan seluruh ID untuk mendeteksi user ganda lintas tahap, lalu mengganti detail template secara atomik.
3. Pembuatan proses menyalin seluruh anggota tahap. Semua anggota tahap pertama berstatus `WAITING`; tahap berikutnya `PENDING`.
4. Saat satu anggota menyetujui, service mengunci proses dan seluruh anggota tahap aktif. Anggota tersebut menjadi `APPROVED`, anggota sejawat menjadi `SKIPPED`, dan seluruh anggota tahap berikutnya menjadi `WAITING`.
5. Jika tidak ada tahap berikutnya, proses dan perusahaan menjadi approved.
6. Saat satu anggota menolak, anggota tersebut menjadi `REJECTED`, anggota sejawat menjadi `SKIPPED`, lalu proses dan perusahaan menjadi rejected.
7. Request kedua yang datang bersamaan atau terlambat ditolak karena tahap sudah diselesaikan.

## UI

Modal mempertahankan gaya visual aplikasi. Setiap kartu mewakili satu tahap, berisi multi-select PIC, nomor tahap, dan tombol hapus. Tombol `Tambah tahap` menambah kartu, bukan satu PIC. Label dan bantuan singkat menjelaskan bahwa cukup satu PIC dalam tiap tahap. Kontrol tetap memiliki label, focus state, loading/error state, dan susunan responsif.

## Verification

- Test validasi payload grup dan penyimpanan beberapa PIC pada urutan yang sama.
- Test pembuatan proses yang mengaktifkan seluruh PIC tahap pertama.
- Test approval satu PIC menandai sejawat skipped dan mengaktifkan seluruh PIC tahap berikutnya.
- Test rejection menyelesaikan tahap/proses dan mencegah aksi PIC sejawat.
- Test kompatibilitas template satu PIC per tahap.
- Test markup dan JavaScript modal untuk struktur tahap multi-PIC.

