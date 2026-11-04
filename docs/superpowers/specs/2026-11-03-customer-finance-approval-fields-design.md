# Customer Finance Approval Fields Design

## Intent

Memastikan PIC Finance pada division FAT menetapkan nilai komersial customer saat memberikan approval, tanpa mengubah alur approval vendor maupun PIC dari organisasi lain.

## Rules

- Kondisi khusus aktif hanya jika `company_informations.type` adalah `customer`.
- Actor harus terhubung ke division bernama `FAT` dan department bernama `Finance`.
- Pada aksi `approve`, `term_of_payment` dan `credit_limit` wajib dikirim dan valid.
- Pada aksi `reject`, kedua field tidak wajib dan nilai perusahaan tidak diubah.
- PIC FAT–Finance boleh mengganti nilai yang sebelumnya diisi melalui form public.
- `term_of_payment` berupa string non-kosong maksimal 255 karakter.
- `credit_limit` berupa angka minimal 0 dan harus sesuai kapasitas kolom database integer.
- Pemeriksaan kondisi dan penyimpanan diulang di service dalam transaksi approval; visibilitas field di UI bukan mekanisme keamanan.

## Data Master

- Migration data idempotent membuat division `FAT` jika belum ada.
- Department `Finance` yang sudah ada dihubungkan ke division FAT.
- Jika department Finance tidak ditemukan, migration membuatnya lalu menghubungkannya ke FAT.
- Rollback hanya menghapus/mengembalikan data yang dibuat atau diubah oleh migration tanpa menghapus division/departemen yang telah memiliki ketergantungan lain.
- `MasterDivisionSeeder` dan `MasterDepartements` diubah menjadi idempotent agar instalasi baru menghasilkan relasi yang sama.

## Backend Flow

1. Controller menentukan apakah actor dan company membutuhkan financial input.
2. Halaman action menerima flag tersebut dan menampilkan dua field dengan nilai company saat ini sebagai default.
3. Controller menerapkan rule kondisional ketika action adalah approve.
4. Controller meneruskan nilai tervalidasi ke `ApprovalService::processApprovalAction()`.
5. Service mengunci actor, company, process, dan stage; menentukan ulang kondisi FAT–Finance/customer.
6. Jika kondisi aktif dan aksi approve, service menolak nilai kosong/tidak valid lalu memperbarui company sebelum menyelesaikan tahap.
7. Jika penyimpanan nilai atau approval gagal, seluruh transaksi dibatalkan.

## UI

- Dua input muncul di form decision hanya untuk customer dan PIC FAT–Finance.
- Nilai lama ditampilkan dan dapat diubah.
- Keterangan menjelaskan bahwa nilai akan menggantikan data customer saat approval.
- Credit limit menggunakan input number dengan nilai minimum 0; term of payment memakai input teks.
- SweetAlert konfirmasi yang sudah ada tetap digunakan.

## Verification

- Vendor tidak pernah diwajibkan mengisi field finansial.
- Customer yang diproses PIC non-FAT/Finance tidak diwajibkan mengisi field finansial.
- Customer yang di-approve PIC FAT–Finance gagal jika salah satu nilai kosong atau tidak valid.
- Reject oleh PIC FAT–Finance tidak membutuhkan dan tidak mengubah nilai finansial.
- Approve yang valid menyimpan kedua nilai dan menyelesaikan tahap dalam transaksi yang sama.
- Request palsu dari PIC lain tidak dapat mengubah nilai finansial.
- Data master FAT dan Finance dibuat serta dihubungkan secara idempotent.

