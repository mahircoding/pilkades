# Product Requirement Document (PRD)
## Sistem Informasi Pemilihan Kepala Desa (SIPILKADES)

| | |
|---|---|
| **Versi Dokumen** | 1.0 |
| **Tanggal** | 30 Juli 2026 |
| **Stack Utama** | Laravel 13 (PHP 8.3+) |
| **Status** | Draft untuk pembahasan |

---

## 1. Ringkasan Eksekutif

SIPILKADES adalah aplikasi web untuk mendigitalkan proses pemilihan kepala desa (Pilkades), mulai dari pendaftaran pemilih dan calon, kampanye, pemungutan suara (e-voting) atau rekapitulasi manual terbantu sistem, hingga penghitungan suara dan publikasi hasil secara transparan dan dapat diaudit.

Tujuan utama:
- Mempercepat dan mengurangi kesalahan pada proses administrasi Pilkades.
- Meningkatkan transparansi hasil penghitungan suara.
- Menyediakan jejak audit (audit trail) yang kuat untuk setiap tahapan.
- Memudahkan panitia (Panitia Pemilihan Kepala Desa/P2KD) mengelola data pemilih dan calon.

Catatan penting: e-voting daring untuk pemilu resmi memiliki risiko keamanan dan hukum yang tinggi (integritas suara, anonimitas, non-repudiation, potensi serangan). Dokumen ini menyediakan dua mode implementasi:
1. **Mode Rekomendasi (Hybrid)** — pemungutan suara tetap dilakukan secara fisik di TPS (Tempat Pemungutan Suara) desa, sistem digunakan untuk pendataan pemilih, penghitungan suara, dan rekapitulasi digital (mengurangi risiko e-voting jarak jauh).
2. **Mode Lanjutan (Full e-Voting)** — pemilih dapat memberikan suara dari perangkat sendiri, memerlukan lapisan keamanan tambahan (dijelaskan di bagian 9).

Disarankan memulai dari Mode Hybrid, karena ini yang paling umum diadopsi desa-desa di Indonesia dan lebih mudah dipertanggungjawabkan secara hukum.

---

## 2. Latar Belakang & Masalah

Proses Pilkades konvensional umumnya menghadapi kendala:
- Pendataan pemilih manual (DPS/DPT) rawan duplikasi dan tidak sinkron dengan data desa.
- Rekapitulasi suara manual berjenjang memakan waktu dan rawan salah hitung.
- Minimnya transparansi real-time bagi masyarakat dan saksi calon.
- Dokumentasi (berita acara, C1, dsb.) masih berbasis kertas dan sulit diarsipkan.

---

## 3. Tujuan Produk (Goals)

1. Panitia dapat mengelola tahapan Pilkades dalam satu sistem (jadwal, pendaftaran calon, DPS/DPT, TPS, hasil).
2. Pemilih dapat mengecek status terdaftar (cek DPT) secara mandiri.
3. Hasil rekapitulasi per-TPS dapat diinput oleh petugas dan otomatis terakumulasi ke tingkat desa.
4. Publik dapat memantau hasil sementara secara real-time melalui halaman publik.
5. Setiap perubahan data penting tercatat dalam log audit yang tidak dapat diubah/dihapus oleh user biasa.

### Non-Goals (di luar cakupan versi awal)
- Integrasi dengan Dukcapil/NIK nasional secara langsung (kecuali API tersedia dan diizinkan).
- E-voting biometrik/blockchain penuh (dicatat sebagai roadmap jangka panjang).

---

## 4. Peran Pengguna (Roles)

| Role | Deskripsi | Hak Akses Utama |
|---|---|---|
| **Super Admin** | Admin sistem tingkat kabupaten/kecamatan atau developer | Kelola seluruh desa, konfigurasi sistem |
| **Panitia (P2KD)** | Panitia pemilihan tingkat desa | Kelola tahapan, calon, DPS/DPT, TPS, hasil |
| **Petugas TPS (KPPS)** | Petugas di tiap TPS | Input hasil penghitungan suara per-TPS |
| **Saksi Calon** | Perwakilan tiap calon | Lihat & verifikasi hasil, unggah bukti keberatan |
| **Pemilih/Warga** | Masyarakat desa | Cek status DPT, lihat profil calon, lihat hasil publik |
| **Pengawas (BPD/Kecamatan)** | Pengawas eksternal | Lihat seluruh laporan & log audit (read-only) |

---

## 5. Alur Proses Utama (High-Level Flow)

```
1. Setup Tahapan Pilkades (Panitia)
        │
2. Pendaftaran & Verifikasi Bakal Calon (Panitia)
        │
3. Penetapan Calon Tetap (Panitia + Berita Acara)
        │
4. Import/Verifikasi Data Pemilih (DPS → DPT)
        │
5. Masa Kampanye (opsional: publikasi profil & visi-misi)
        │
6. Hari-H Pemungutan Suara di TPS
        │  ├─ Pemilih datang → dicek di DPT → mencoblos fisik
        │  └─ (Opsional Mode Lanjutan) Pemilih login → e-voting
        │
7. Penghitungan Suara per-TPS (input oleh KPPS + saksi)
        │
8. Rekapitulasi Berjenjang (TPS → Desa)
        │
9. Penetapan & Publikasi Hasil Resmi (Panitia, dengan Berita Acara digital)
        │
10. Arsip & Laporan Akhir
```

---

## 6. Kebutuhan Fungsional (Functional Requirements)

### 6.1 Modul Autentikasi & Manajemen Pengguna
- Login multi-role dengan Laravel (Fortify/Breeze) + 2FA untuk Panitia & Super Admin.
- Manajemen hak akses berbasis role & permission (disarankan paket `spatie/laravel-permission`).
- Log aktivitas login (siapa, kapan, dari IP mana).

### 6.2 Modul Master Data Desa & Tahapan
- CRUD data desa, dusun/RT/RW, dan periode Pilkades.
- Pengaturan tahapan & tenggat waktu (timeline) tiap tahap.

### 6.3 Modul Calon Kepala Desa
- Pendaftaran bakal calon: biodata, foto, dokumen persyaratan (upload PDF/scan KTP, ijazah, dll).
- Status verifikasi berkas: Diajukan → Diverifikasi → Ditetapkan/Ditolak.
- Nomor urut calon otomatis/manual setelah penetapan.
- Halaman publik profil & visi-misi calon.

### 6.4 Modul Data Pemilih (DPS/DPT)
- Import data pemilih massal via Excel/CSV (gunakan Laravel Excel/Maatwebsite).
- Deteksi duplikasi (berdasarkan NIK).
- Status: DPS (Sementara) → DPT (Tetap) setelah masa sanggah.
- Fitur cek DPT mandiri oleh warga (input NIK, tanpa login).
- Penetapan pemilih ke TPS tertentu.

### 6.5 Modul TPS & Petugas
- CRUD data TPS dan penugasan petugas KPPS/saksi per-TPS.
- Kapasitas pemilih per TPS.

### 6.6 Modul Pemungutan & Penghitungan Suara
**Mode Hybrid (direkomendasikan):**
- Form input hasil hitung suara per-TPS oleh KPPS (jumlah suara sah per calon, suara tidak sah, jumlah pemilih hadir).
- Verifikasi ganda: saksi tiap calon harus menyetujui (approve) input sebelum dikunci.
- Upload foto/scan formulir C1 (berita acara TPS) sebagai bukti.
- Rekapitulasi otomatis dari seluruh TPS ke level desa, dengan grafik real-time.

**Mode Lanjutan (opsional, e-voting jarak jauh):**
- Pemilih login dengan token unik sekali pakai (dikirim terenkripsi/QR saat verifikasi identitas di TPS/loket).
- Satu akun hanya bisa memberi 1 suara (idempotent, dicek di level DB dengan constraint).
- Suara dienkripsi sebelum disimpan; pemisahan antara identitas pemilih dan pilihan (misal dengan token anonim) agar hasil vote tidak bisa ditelusuri balik ke individu.

### 6.7 Modul Publikasi & Transparansi
- Halaman publik: profil calon, jadwal tahapan, hasil sementara & final per-TPS dan rekap desa.
- Grafik hasil (bar chart) menggunakan Chart.js/ApexCharts.
- Unduh Berita Acara & rekap dalam PDF (gunakan `barryvdh/laravel-dompdf` atau `spatie/laravel-pdf`).

### 6.8 Modul Audit & Log
- Setiap perubahan data penting (calon, DPT, hasil suara) tercatat di tabel audit (siapa, kapan, data lama/baru).
- Log tidak bisa dihapus melalui aplikasi (soft-protect / append-only).
- Disarankan gunakan `spatie/laravel-activitylog`.

### 6.9 Modul Notifikasi
- Notifikasi email/WhatsApp (via gateway) untuk pengumuman tahapan penting.
- Notifikasi ke saksi saat ada input hasil TPS yang perlu diverifikasi.

### 6.10 Modul Laporan
- Ekspor rekap suara, DPT, dan laporan tahapan ke Excel/PDF.
- Dashboard statistik: partisipasi pemilih, progres tahapan, status verifikasi calon.

---

## 7. Kebutuhan Non-Fungsional

| Kategori | Kebutuhan |
|---|---|
| **Keamanan** | HTTPS wajib, hashing password (bcrypt/argon2), proteksi CSRF/XSS bawaan Laravel, rate limiting login, 2FA untuk role sensitif |
| **Skalabilitas** | Mendukung minimal 1 desa dengan ±5.000 pemilih tanpa penurunan performa signifikan |
| **Ketersediaan** | Target uptime 99% saat hari-H pemungutan suara; siapkan mode maintenance-aware |
| **Auditability** | Semua transaksi krusial harus tercatat dan dapat direkonstruksi |
| **Aksesibilitas** | Desain responsif (mobile-first) karena banyak warga akses via HP |
| **Localization** | Bahasa Indonesia sebagai default |
| **Backup** | Backup database otomatis harian + backup khusus sebelum & sesudah hari-H |
| **Kompatibilitas** | PHP 8.3+, MySQL 8/PostgreSQL, browser modern |

---

## 8. Rancangan Teknologi (Tech Stack)

| Layer | Teknologi |
|---|---|
| **Framework Backend** | Laravel 13 (PHP 8.3+) |
| **Autentikasi** | Laravel Fortify / Breeze + 2FA |
| **Otorisasi** | spatie/laravel-permission |
| **Database** | MySQL 8.x atau PostgreSQL 15+ |
| **Frontend** | Blade + Alpine.js + Tailwind CSS (atau Livewire 3 untuk interaktivitas real-time) |
| **Real-time** | Laravel Reverb (WebSocket) untuk update hasil suara live |
| **Queue** | Laravel Queue (database/redis) untuk proses import data & notifikasi |
| **Excel Import/Export** | maatwebsite/excel |
| **PDF** | spatie/laravel-pdf atau barryvdh/laravel-dompdf |
| **Activity Log** | spatie/laravel-activitylog |
| **Chart** | Chart.js / ApexCharts |
| **Hosting** | VPS Linux (Ubuntu) + Nginx + Supervisor, atau Laravel Forge/Cloud |
| **Testing** | Pest / PHPUnit untuk unit & feature test khususnya modul penghitungan suara |

> Catatan: Laravel 13 dirilis 17 Maret 2026, mewajibkan PHP minimal 8.3, dan membawa fitur baru seperti PHP Attributes untuk konfigurasi model/controller serta Laravel AI SDK (stabil). Untuk sistem ini, fitur AI SDK bersifat opsional (misal untuk chatbot bantuan warga), bukan komponen inti.

---

## 9. Pertimbangan Keamanan Khusus Pemilu

1. **Anonimitas suara**: pisahkan tabel "siapa yang sudah memilih" dari tabel "isi pilihan suara" — jangan simpan foreign key langsung dari suara ke identitas pemilih.
2. **Non-repudiation tanpa deanonimisasi**: gunakan token acak sekali pakai per pemilih, bukan ID pemilih langsung.
3. **Integritas data**: gunakan checksum/hash pada setiap rekap yang dikunci (locked) agar perubahan setelah penetapan bisa terdeteksi.
4. **Segregation of duties**: input hasil oleh KPPS, approval oleh saksi — tidak satu role bisa mengubah hasil sendirian tanpa jejak.
5. **Penetration testing** sebelum hari-H, khususnya jika memilih Mode Lanjutan (e-voting jarak jauh).
6. **Kepatuhan hukum**: koordinasikan dengan Peraturan Daerah/Peraturan Bupati setempat tentang keabsahan mekanisme elektronik untuk Pilkades — di banyak daerah, hasil resmi tetap harus berbasis Berita Acara fisik yang ditandatangani.

---

## 10. Rancangan Basis Data (Ringkasan Entitas)

- `villages` (desa)
- `election_periods` (periode/tahapan pilkades)
- `candidates` (calon: nama, foto, no urut, visi_misi, status_verifikasi)
- `voters` (pemilih: nik_hash, nama, dusun_id, tps_id, status_dpt)
- `tps` (tempat pemungutan suara)
- `officers` (petugas KPPS/saksi, relasi ke tps)
- `vote_results` (hasil suara per TPS per calon: jumlah_suara, status_lock)
- `vote_result_approvals` (approval saksi terhadap `vote_results`)
- `activity_logs` (audit trail, via package)
- `documents` (berkas persyaratan calon, berita acara, C1)
- `announcements` (pengumuman tahapan)

*(Skema detail kolom & relasi dapat dijabarkan lebih lanjut saat masuk tahap desain database/migration.)*

---

## 11. Wireframe / Struktur Halaman (Ringkasan)

**Publik (tanpa login):**
- Beranda: jadwal & info Pilkades
- Profil Calon
- Cek DPT (input NIK)
- Hasil Sementara/Final (grafik & tabel)

**Panitia/Admin (setelah login):**
- Dashboard
- Manajemen Tahapan
- Manajemen Calon
- Manajemen DPS/DPT
- Manajemen TPS & Petugas
- Input & Rekap Hasil
- Laporan & Ekspor
- Log Audit

---

## 12. Roadmap Pengembangan (Disarankan)

| Fase | Cakupan | Estimasi |
|---|---|---|
| **Fase 1** | Auth, master data desa, manajemen calon, manajemen DPT | 3–4 minggu |
| **Fase 2** | Manajemen TPS/petugas, input hasil suara, approval saksi | 3 minggu |
| **Fase 3** | Rekapitulasi otomatis, publikasi hasil real-time, laporan PDF/Excel | 2–3 minggu |
| **Fase 4** | Hardening keamanan, audit log, pengujian & simulasi hari-H | 2 minggu |
| **Fase 5 (opsional)** | Mode e-voting jarak jauh + enkripsi suara | 4+ minggu |

---

## 13. Risiko & Mitigasi

| Risiko | Mitigasi |
|---|---|
| Koneksi internet desa tidak stabil saat hari-H | Sediakan mode offline-first / input lokal lalu sinkron |
| Data pemilih ganda/tidak akurat | Validasi NIK & proses masa sanggah DPS sebelum DPT dikunci |
| Kecurigaan manipulasi hasil | Audit log lengkap + approval ganda (KPPS & saksi) + upload bukti C1 |
| Resistensi warga terhadap sistem digital | Sosialisasi & tetap sediakan proses manual paralel sebagai fallback |
| Serangan siber pada hari-H | Rate limiting, WAF, monitoring real-time, backup berkala |

---

## 14. Lampiran: Contoh Struktur Folder Laravel 13 (Ringkas)

```
app/
 ├─ Http/Controllers/
 │   ├─ Admin/
 │   ├─ Public/
 │   └─ Api/
 ├─ Models/
 ├─ Services/         (VoteCalculationService, DptImportService, dll.)
 ├─ Policies/
 └─ Notifications/
database/
 ├─ migrations/
 └─ seeders/
resources/
 ├─ views/ (Blade)
 └─ js/ (Alpine/Livewire components)
routes/
 ├─ web.php
 └─ api.php
```

---

*Dokumen ini adalah draft awal PRD dan dapat disesuaikan lebih lanjut sesuai peraturan daerah setempat, jumlah desa yang dilayani, dan keputusan mengenai mode pemungutan suara (Hybrid vs Full e-Voting).*
