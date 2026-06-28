# Product Requirement Document (PRD)

# Website Customer Journey
## Koperasi Coosae

---

# 1. Overview

Website Customer Journey merupakan aplikasi internal yang digunakan oleh divisi Marketing untuk mengelola seluruh perjalanan customer mulai dari Prospek Customer hingga menjadi Customer Aktif.

Sistem juga digunakan untuk:

- mencatat seluruh aktivitas follow up
- menyimpan dokumen customer
- memonitor performa marketing
- memberikan reminder apabila customer tidak di-follow up lebih dari 30 hari.

---

# 2. Tujuan

Membantu tim Marketing agar:

- seluruh prospek tercatat
- history interaksi tidak hilang
- semua dokumen tersimpan
- manager dapat memonitor seluruh aktivitas marketing
- tidak ada customer yang terlupakan.

---

# 3. Tech Stack

Backend

- Laravel 12

Frontend

- Blade
- Tailwind CSS
- DaisyUI

Authentication

- Laravel Breeze

Database

- MySQL

Storage

- Laravel Storage

Deployment

- Linux VPS
- Nginx
- PHP 8.3

---

# 4. User Role

## Marketing

Hak akses:

- Dashboard
- Kelola Prospek
- Update Status Customer
- Input Follow Up
- Upload Dokumen
- Melihat History Customer miliknya

---

## Manager Marketing

Hak akses:

- Dashboard
- Melihat seluruh Prospek
- Melihat seluruh Customer Aktif
- Melihat seluruh History
- Melihat performa setiap Marketing
- Monitoring reminder

Manager tidak boleh mengubah data customer.

---

# 5. Business Flow

Prospek Customer

↓

Prospek Customer

↓

Negosiasi

↓

Customer Aktif

↓

Selesai

Selama proses tersebut Marketing dapat melakukan follow up berkali-kali.

---

# 6. Status Customer

Status terdiri dari:

- Prospek Customer
- Negosiasi
- Customer Aktif

Rules:

Status hanya boleh maju.

Prospek Customer

↓

Negosiasi

↓

Customer Aktif

Jika status menjadi Customer Aktif maka otomatis muncul pada menu Customer Aktif.

---

# 7. Menu Sistem

## Dashboard

### Marketing

Menampilkan:

- Total Prospek
- Total Negosiasi
- Total Customer Aktif
- Customer belum Follow Up
- Follow Up bulan ini

Grafik:

- Customer per bulan
- Customer berdasarkan produk
- Customer berdasarkan lokasi

---

### Dashboard Manager

Menampilkan:

- Total seluruh Marketing
- Total Prospek
- Total Customer Aktif
- Marketing paling aktif
- Marketing dengan customer terbanyak

Grafik:

- Aktivitas Marketing
- Sebaran Customer
- Customer Aktif per Marketing

---

# 8. Modul Prospek Customer

Menu ini digunakan untuk memasukkan customer baru.

### Tabel

Kolom:

- Nama Customer
- Produk
- Nomor WhatsApp
- Lokasi
- Marketing
- Tanggal Dibuat
- Action

Action

- Detail
- Edit
- Hapus

Button

Tambah Prospek Customer

---

## Form Tambah Prospek

Field

Nama Customer

Produk yang diminati

Pilihan:

- Program Pelatihan Pertanian
- Layanan Konsultasi Pertanian
- Produk Hortikultura

Nomor WhatsApp

Lokasi

Keterangan

Submit

---

# 9. Modul Status Perjalanan Customer

Berisi seluruh customer yang masih dalam proses.

Kolom:

Nama Customer

Produk

Status

Last Follow Up

Action

- Update
- History

---

## Form Update Status

Readonly

Nama Customer

Produk

Nomor WA

Lokasi

Marketing

Editable

Status Customer

Pilihan:

- Prospek Customer
- Negosiasi
- Customer Aktif

Riwayat Interaksi

Pilihan:

- Telepon
- WhatsApp / Email
- Meeting
- Presentasi Produk
- Kunjungan Lapangan

Tanggal Interaksi

Keterangan

Jenis Dokumen

Pilihan:

- Form Pendaftaran
- Form Kunjungan
- Catatan Survey
- Proposal Kerjasama
- Kontrak
- Dokumen Lainnya

Upload Dokumen

Submit

---

Business Rule

Setiap submit akan membuat History baru.

Tidak boleh menghapus history.

---

# 10. History Follow Up

History terdiri dari:

Tanggal

Marketing

Status Saat Itu

Jenis Interaksi

Catatan

Dokumen

Semua history bersifat read only.

---

# 11. Customer Aktif

Customer otomatis muncul ketika status berubah menjadi Customer Aktif.

Kolom

Nama Customer

Produk

Tanggal Aktif

Download Dokumen

History

---

Dokumen dapat terdiri dari:

Form Pendaftaran

Form Kunjungan

Catatan Survey

Proposal

Kontrak

Dokumen lainnya

---

# 12. Monitoring Reminder

Sistem akan mengecek:

Last Follow Up

Jika lebih dari 30 hari

dan status:

- Prospek Customer
- Negosiasi

maka muncul Reminder.

Reminder tampil:

Dashboard Marketing

Dashboard Manager

Badge merah pada menu.

---

# 13. Laporan Manager

Manager dapat melihat aktivitas setiap Marketing.

Contoh:

Marketing

Jumlah Prospek

Jumlah Customer Aktif

Jumlah Follow Up

Customer Belum Follow Up

History

---

Klik nama Marketing

↓

Menampilkan seluruh customer milik Marketing tersebut.

---

# 14. Database

## users

- id
- name
- email
- password
- role

---

## products

- id
- nama

---

## customers

- id
- marketing_id
- product_id
- nama
- whatsapp
- lokasi
- keterangan
- status
- created_at

---

## followups

- id
- customer_id
- user_id
- status
- jenis_interaksi
- tanggal_interaksi
- hasil_interaksi
- created_at

---

## documents

- id
- customer_id
- followup_id
- jenis
- file
- created_at

---

# 15. Relasi

User

1

↓

Customer

Customer

1

↓

FollowUp

FollowUp

1

↓

Document

Product

1

↓

Customer

---

# 16. Validasi

Nomor WhatsApp unik.

Nama customer wajib.

Produk wajib.

Status wajib.

Tanggal follow up wajib.

Dokumen maksimal 10 MB.

Format:

pdf

doc

docx

jpg

jpeg

png

---

# 17. UI

Style

Modern Dashboard

Framework

DaisyUI

Layout

Sidebar

Navbar

Content

Card Dashboard

Responsive

Desktop

Tablet

Mobile

Warna

Primary

Emerald

Secondary

Sky

Accent

Amber

Danger

Red

---

# 18. Authentication

Menggunakan Laravel Breeze.

Role:

Marketing

Manager

Redirect setelah login:

Marketing → Dashboard Marketing

Manager → Dashboard Manager

---

# 19. Future Feature

- Notifikasi WhatsApp otomatis
- Export Excel
- Export PDF
- Import Customer
- Dashboard Analytics
- Integrasi Google Maps
- Timeline Customer
- Reminder melalui Email
- Activity Log
- Audit Log

# 20. Enhancement Features

Berikut merupakan fitur tambahan yang direkomendasikan untuk meningkatkan efektivitas sistem Customer Journey.

---

## 20.1 Timeline Customer

### Deskripsi

Selain History Follow Up berbentuk tabel, sistem menyediakan tampilan Timeline sehingga seluruh perjalanan customer dapat dilihat secara kronologis.

Contoh Timeline:

📅 02 Juni 2026
- Customer ditambahkan sebagai Prospek.

📞 05 Juni 2026
- Follow Up melalui Telepon.

🤝 10 Juni 2026
- Meeting dengan Customer.

📄 15 Juni 2026
- Upload Proposal Kerjasama.

✅ 25 Juni 2026
- Status berubah menjadi Customer Aktif.

### Tujuan

- Mempermudah melihat perjalanan customer.
- Memudahkan Manager melakukan monitoring.
- Memudahkan marketing melanjutkan follow up.

---

## 20.2 Last Follow Up Indicator

### Deskripsi

Setiap customer memiliki informasi terakhir kapan dilakukan follow up.

Ditampilkan pada tabel:

- Last Follow Up
- Jumlah Hari Tidak Di-follow Up

Contoh:

| Customer | Last Follow Up | Hari |
|-----------|---------------|------|
| Budi | 2 hari lalu | 2 |
| Andi | 34 hari lalu | 34 |

### Warna Status

Hijau

0–14 Hari

Kuning

15–29 Hari

Merah

≥30 Hari

### Tujuan

Marketing dapat mengetahui customer mana yang harus segera dihubungi.

---

## 20.3 Dashboard Analytics

### Deskripsi

Dashboard menampilkan grafik performa secara realtime.

Widget yang ditampilkan:

- Total Prospek
- Total Negosiasi
- Total Customer Aktif
- Customer Baru Bulan Ini
- Follow Up Hari Ini
- Reminder Customer

Grafik:

- Customer per Bulan
- Customer berdasarkan Produk
- Customer berdasarkan Lokasi
- Customer berdasarkan Status
- Aktivitas Marketing

### Tujuan

Memberikan informasi cepat kepada Marketing dan Manager.

---

## 20.4 Customer Detail Page

### Deskripsi

Setiap customer memiliki halaman detail tersendiri.

Informasi yang ditampilkan:

Data Customer

Riwayat Status

Timeline Customer

History Follow Up

Daftar Dokumen

Catatan Marketing

Last Follow Up

Tanggal Customer Dibuat

Marketing Penanggung Jawab

### Tujuan

Semua informasi customer dapat dilihat dalam satu halaman tanpa berpindah menu.

---

## 20.5 Soft Delete & Activity Log

### Soft Delete

Customer yang dihapus tidak langsung hilang dari database.

Data dipindahkan ke status:

Trash

Manager dapat:

- Restore Data
- Hapus Permanen

---

### Activity Log

Sistem mencatat seluruh aktivitas pengguna.

Aktivitas yang dicatat:

- Login
- Logout
- Tambah Customer
- Edit Customer
- Update Status
- Upload Dokumen
- Download Dokumen
- Restore Data
- Delete Data

Informasi log:

- User
- Aktivitas
- Waktu
- IP Address

### Tujuan

- Audit aktivitas pengguna.
- Mengetahui siapa yang melakukan perubahan data.
- Meningkatkan keamanan sistem.

---

# 21. Future Development

Versi berikutnya sistem dapat dikembangkan dengan fitur:

- Notifikasi WhatsApp otomatis menggunakan WhatsApp API.
- Export laporan ke Excel dan PDF.
- Import data customer dari file Excel.
- Integrasi Google Maps untuk visualisasi lokasi customer.
- Reminder melalui Email.
- Integrasi Google Calendar untuk jadwal follow up.
- Dashboard KPI Marketing.
- Target penjualan setiap Marketing.
- Penugasan customer kepada Marketing tertentu.
- Sistem komentar internal antar Marketing dan Manager.