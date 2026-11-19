# Pertemuan 3 - Arsitektur Informasi, User Flow, Wireframe, dan Prototipe Awal

**Proyek:** UNY Lost and Found - pelaporan barang hilang dan ditemukan di lingkungan UNY
**Mata kuliah:** Praktik Aplikasi Web (INF60295) - Dosen: Zaenal Mustofa, M.Kom.
**Pengerjaan:** perorangan
**Tanggal revisi:** 20 November 2026

## Anggota dan Kontribusi

| Nama | NIM | Kontribusi |
|---|---|---|
| Arifan Ismail | 24051130061 | Seluruh artefak Pertemuan 3 (scope, sitemap, user flow, wireframe, prototipe, dokumentasi) |

## Tautan

- Prototipe klik: `prototipe/index.html` (buka lewat server lokal, lihat di bawah)
- Repositori: https://github.com/arifanismail69/uny-lost-and-found
- Desain: wireframe berupa gambar vektor di dalam `05-wireframe-desktop.pdf` dan `06-wireframe-mobile.pdf`

## Daftar Artefak

| Berkas | Isi |
|---|---|
| 01-scope-canvas.pdf | Keputusan 14 user story masuk/tidak dan scope canvas |
| 02-sitemap.pdf | Sitemap dan daftar halaman (H-01 sampai H-08) |
| 03-user-flow-pengguna.pdf | User flow pemilik barang + skenario |
| 04-user-flow-admin.pdf | User flow petugas + skenario |
| 05-wireframe-desktop.pdf | 6 wireframe desktop, inventaris layar, keterhubungan ke acceptance criteria |
| 06-wireframe-mobile.pdf | 6 wireframe mobile berpasangan, perbandingan desktop dan mobile |
| 07-usability-walkthrough.pdf | Hasil walkthrough (oleh Claude), 5 temuan beserta revisi, dan lembar uji kosong untuk teman |
| 08-keputusan-desain.md | 12 keputusan desain beserta alasan |
| 09-refleksi-individu.pdf | Kerangka refleksi (jawaban ditulis sendiri) |
| prototipe/index.html | Prototipe klik satu file |

## Cara Menjalankan Prototipe

Dari folder `prototipe`, jalankan `php -S localhost:8092`, lalu buka `http://localhost:8092/index.html`. (Membuka berkas langsung juga bisa pada sebagian browser.) Data hanya di memori; muat ulang halaman mengembalikan data awal.

Akun contoh (tombol "Isi akun" tersedia di halaman Masuk):

- Pengguna: `dina@mhs.uny.ac.id` / `dina123`
- Petugas: `slamet@uny.ac.id` / `petugas123`

Happy path: Masuk (pengguna) > Cari Barang (kategori Dompet) > Lihat Detail "Dompet kulit hitam" > isi ciri pembuktian > Kirim Klaim > lihat status di Riwayat. Lalu Keluar, masuk sebagai petugas, pilih klaim Menunggu, dan Setujui atau Tolak.

Jalur gagal: kirim klaim dengan isian kosong; cari kata yang tidak ada (hasil kosong); Tolak tanpa alasan; setujui klaim kedua untuk barang yang sudah punya klaim disetujui (tombol nonaktif).

## Yang Masih Perlu Dilakukan

- Minta persetujuan dosen untuk topik di luar studi kasus utama.
- Uji prototipe dengan satu mahasiswa lain tanpa petunjuk, isi Bagian C di `07-usability-walkthrough.pdf`.
- Tulis refleksi individu (`09-refleksi-individu.pdf`).
- Buat folder ini di repositori GitHub, commit (contoh: `docs: add sitemap and responsive wireframes`), push, dan isi tautan di atas.
