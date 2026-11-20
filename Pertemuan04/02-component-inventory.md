# 02 - Inventaris Komponen UI

Komponen dibuat sebagai Blade anonymous component di `aplikasi/resources/views/components/`. Gaya ada di `aplikasi/public/css/components.css`. Semua state dapat dilihat di halaman uji `/komponen`.

| Komponen | Berkas | Parameter | State | Dipakai di |
|---|---|---|---|---|
| Layout | `layout.blade.php` | `title`, `active`, `admin` | pengguna, petugas, pesan sukses (flash) | Semua halaman |
| Navbar | `navbar.blade.php` | `active`, `admin` | menu aktif (`aria-current`), header petugas berwarna gelap, navigasi bawah di mobile | Layout |
| Button | `button.blade.php` | `variant` (primary/secondary/danger), `href`, `type`, `disabled`, `block` | default, hover/focus, disabled, loading (`.is-loading`) | Semua halaman |
| FormField | `form-field.blade.php` | `name`, `label`, `type` (text/date/select/textarea/file), `options`, `placeholder`, `required`, `hint`, `error` | empty, focus, filled (`old()`), error (`@error` dari validasi Laravel, `aria-invalid`) | Beranda, Detail, Lapor, Petugas |
| ItemCard | `item-card.blade.php` | `item`, `selected` | normal, selected, sudah diklaim (tombol nonaktif) | Beranda, Hasil, halaman uji |
| StatusBadge | `status-badge.blade.php` | `status` | Menunggu, Menunggu verifikasi, Terverifikasi, Disetujui, Ditolak, Dikembalikan, Sudah diklaim | Kartu, Detail, Riwayat, Petugas |
| Alert | `alert.blade.php` | `type` (success/warning/danger) | 3 varian | Detail, Lapor, Petugas, Layout |
| EmptyState | `empty-state.blade.php` | `title`, `text`, slot aksi | hasil kosong, belum ada laporan/klaim, tidak ditemukan | Hasil, Riwayat, Petugas, 404 |

## Kriteria pemeriksaan (sesuai modul)

| Komponen | Kriteria | Hasil |
|---|---|---|
| Button | Label berupa kata kerja dan mudah ditemukan | Ya: "Cari Barang", "Kirim Klaim", "Kirim Laporan", "Setujui", "Tolak" |
| Form field | Label, placeholder, dan pesan galat jelas | Ya: pesan galat dalam Bahasa Indonesia di bawah kolom |
| Card | Informasi utama dan aksi tidak membingungkan | Ya: nama, lokasi, tanggal, status, satu tombol |
| Status badge | Warna bukan satu-satunya pembeda | Ya: selalu ada teks |

## Komponen yang paling mengurangi pengulangan

`FormField`: satu komponen menangani label, kontrol, teks bantuan, pesan galat Laravel, dan atribut aksesibilitas. Form Lapor memakai 9 field tanpa menulis ulang markup galat.

## Selisih dengan prototipe Pertemuan 3

| Bagian | Prototipe P3 | Implementasi P4 | Alasan |
|---|---|---|---|
| Form klaim di mobile | Bottom sheet | Panel di bawah detail (satu kolom) | Bottom sheet butuh JavaScript; ditunda agar tetap jalan tanpa JS |
| Riwayat di mobile | Dua tab | Dua bagian bertumpuk | Lebih sederhana; tab dapat ditambahkan nanti |
| Layar petugas di mobile | Halaman rincian terpisah | Daftar lalu panel rincian di bawahnya | Satu route; tetap bisa dipakai di layar sempit |
| Login | Halaman Masuk | Belum ada; pengguna "Dina" dan "Pak Slamet" disimulasikan | Autentikasi dijadwalkan di Pertemuan 6 |
| Data | Di memori browser | Data contoh di `App\Support\DemoData`; keputusan belum tersimpan | Penyimpanan dijadwalkan di Pertemuan 5 |
