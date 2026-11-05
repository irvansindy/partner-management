# Cross-Department Submission Access Design

## Intent

Memberi assigned approver lintas office/department akses yang cukup untuk meninjau satu submission yang ditugaskan kepadanya, tanpa memberi akses ke pengaturan Form Link atau submission lain.

## Access Rules

- Super-admin tetap dapat mengakses seluruh resource.
- Pemilik office/department Form Link mempertahankan seluruh akses yang ada.
- User dengan role approval yang tercatat pada salah satu `approval_process_steps` milik company boleh membaca submission spesifik tersebut.
- Hak assigned approver tetap berlaku setelah step berstatus approved, rejected, atau skipped agar histori dan redirect tetap dapat dibuka.
- Assigned approver tidak memperoleh akses ke halaman Form Link, daftar seluruh submission, create/edit Form Link, toggle status, atau delete.
- Assigned approver tidak boleh membuka atau mengirim form edit submission.
- Assigned approver boleh melihat detail submission, membuka PDF inline, dan membaca/download attachment yang benar-benar milik company tersebut.
- User yang tidak terkait dengan Form Link maupun approval process tetap menerima 403.
- Mismatch antara `{formLink}` dan company tetap menghasilkan 404 agar tidak membocorkan keberadaan submission.

## Authorization Structure

- `FormLinkService::authorizeAccess()` tetap menjadi izin manajemen Form Link berdasarkan organisasi.
- Tambahkan policy/service method terpisah untuk read access submission, menerima `FormLink`, `CompanyInformation`, dan actor.
- Method tersebut memeriksa kepemilikan company terhadap Form Link sebelum memeriksa izin.
- Controller mengambil company melalui relasi Form Link, lalu memakai submission-read authorization untuk detail dan PDF.
- Route attachment memeriksa company pemilik attachment dan memakai aturan submission-read yang sama.
- Edit/update tetap memakai management authorization yang lama.

## Navigation

- PIC lintas department masuk melalui My Pending Approvals atau detail approval.
- Tombol “View full submission” membuka detail submission spesifik.
- Tombol kembali pada detail submission untuk assigned approver kembali ke detail approval atau daftar pending approval, bukan ke daftar submission Form Link yang tidak boleh ia buka.
- Tombol edit tidak ditampilkan kepada user yang hanya memiliki read access.

## Security Constraints

- Akses tidak hanya berdasarkan role; actor wajib benar-benar tercatat sebagai approver untuk company tersebut.
- Company ID dan attachment ID selalu diverifikasi hubungannya dengan Form Link/company di server.
- Status approval tidak dipakai sebagai satu-satunya syarat akses.
- Tidak ada perluasan query daftar Form Link atau daftar submission.

## Verification

- Assigned approver lintas department dapat membuka detail, PDF, dan attachment dari submission yang ditugaskan.
- Assigned approver tidak dapat membuka Form Link, daftar submission, edit, update, toggle, atau submission lain.
- User satu organisasi tetap memiliki akses lama.
- Super-admin tetap memiliki akses penuh.
- Company/Form Link mismatch menghasilkan 404.
- User tidak terkait memperoleh 403.

