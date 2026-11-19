# Keputusan Desain - UNY Lost and Found (Pertemuan 3)

| No | Keputusan | Dasar Persona/User Story | Alternatif | Alasan Dipilih | Perlu Diuji Lagi? |
|---|---|---|---|---|---|
| 1 | Pencarian ditaruh di Beranda dengan empat filter sebaris, disertai dua tombol cepat "Lapor Kehilangan" dan "Lapor Temuan" | Persona Dina (ingin tahu cepat apakah barangnya sudah ditemukan); US-04, US-02, US-03 | Beranda berisi daftar barang saja, pencarian di halaman terpisah | Jalur terpendek ke tindakan utama; filter sedikit sehingga muat satu baris | Ya |
| 2 | Satu form "Lapor Barang" untuk kehilangan dan temuan, dibedakan pilihan jenis | US-02 dan US-03 punya isian hampir sama | Dua form terpisah | Mengurangi jumlah halaman; hierarki tetap maksimal tiga tingkat | Ya (apakah jenis laporan jelas) |
| 3 | "Ciri rahasia" dipisah dari "Ciri barang"; hanya petugas yang melihatnya | Pain point Dina (takut barang diambil pengaku palsu) dan Pak Slamet (sulit verifikasi); AC-03.5, AC-04.4 | Semua ciri ditampilkan di publik | Pengklaim harus menyebut ciri yang hanya diketahui pemilik asli | Ya (apakah pengguna paham arti "ciri rahasia") |
| 4 | Form klaim berupa panel pada Detail Barang (bottom sheet di mobile), bukan halaman baru | Prinsip arsitektur informasi: konten yang bisa menjadi state tidak dijadikan halaman | Halaman "Form Klaim" terpisah | Pengguna tetap melihat barang saat mengisi bukti; satu halaman lebih sedikit | Tidak |
| 5 | Barang yang sudah diklaim tetap tampil, tombolnya nonaktif, diurutkan terakhir | AC-05.3; temuan walkthrough no. 4 | Menyembunyikan barang yang sudah diklaim | Pengguna tahu barang itu ada dan sudah diurus; tidak membuka detail sia-sia | Tidak |
| 6 | Status ditampilkan sebagai badge bertuliskan teks (Menunggu, Disetujui, dst.) pada Riwayat | US-06; warna tidak boleh menjadi satu-satunya pembeda | Halaman terpisah per status | Mengurangi halaman; tetap terbaca bagi pengguna buta warna | Tidak |
| 7 | Penolakan laporan dan klaim wajib disertai alasan | AC-08.2, AC-09.3, AC-09.4 | Alasan opsional | Transparansi; mengurangi pertanyaan ulang ke petugas | Ya (apakah memperlambat petugas) |
| 8 | Layar petugas memakai pola master-detail satu layar, dengan perbandingan ciri rahasia dan bukti berdampingan | Persona Pak Slamet (banyak item per shift); US-07, US-08, US-09 | Halaman detail terpisah untuk tiap item | Mengurangi perpindahan halaman; perbandingan langsung memudahkan verifikasi | Ya |
| 9 | Peringatan "sudah ada klaim yang disetujui" dan tombol Setujui nonaktif | AC-09.2 (satu klaim disetujui per barang) | Mengizinkan lalu menampilkan galat setelah ditekan | Mencegah kesalahan sebelum terjadi | Ya |
| 10 | Mobile memakai bottom navigation dan tombol aksi yang menetap di bawah | Persona Dina memakai ponsel satu tangan; target sentuh 44 px | Menu hamburger di atas | Terjangkau ibu jari; aksi utama selalu terlihat | Ya |
| 11 | Label filter tanggal diubah dari "Tanggal" menjadi "Sejak tanggal" | Temuan walkthrough no. 3 | Tetap "Tanggal" | Menghilangkan ambiguitas tanggal hilang/ditemukan | Ya |
| 12 | Setelah login, tujuan sebelumnya hanya dipakai bila pengguna berhak atas halaman itu; isian klaim disimpan saat dialihkan ke Masuk | Temuan walkthrough no. 1 dan 2 | Selalu kembali ke tujuan semula, tanpa menyimpan isian | Menghindari jalan buntu dan kehilangan isian | Tidak |

## Catatan

- Keputusan no. 5 sampai 12 sebagian lahir dari pemeriksaan prototipe (lihat `07-usability-walkthrough.pdf`). Walkthrough dilakukan Claude (asisten AI) karena proyek perorangan; belum ada uji dengan pengguna nyata.
- Aturan penyamaran nomor identitas (AC-03.3) belum digambar di wireframe; dicatat sebagai aturan implementasi untuk pertemuan berikutnya.
