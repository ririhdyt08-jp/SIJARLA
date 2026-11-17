# SIJARLA

SIJARLA (Sistem Informasi Jaga Rimba dan Lahan) adalah portal edukasi karhutla Indonesia untuk tema **Isu Lingkungan Global**. Situs memuat beranda, tentang, artikel dengan detail per kejadian, kontak, dan peta GIS.

## Nama tim dan anggota

- Nama tim: BLARATAN
- Anggota: Fies Medhali Anugrahsyah, Risma Hidayati
- 

Ganti `namaTim` pada nama folder/repositori `webdesign_namaTim_technoversary_vol.II` sebelum pengumpulan.

## Instalasi dan cara menjalankan

1. Siapkan PHP 8.1+ (PHP CLI atau PHP dari XAMPP) dan koneksi internet untuk Bootstrap, font, Leaflet, dan peta dasar.
2. Buka terminal pada folder proyek.
3. Jalankan `php -S 127.0.0.1:8077 -t public`.
4. Buka [http://127.0.0.1:8077](http://127.0.0.1:8077). Hentikan server lokal dengan Ctrl+C.

Jika PHP belum ada di PATH, gunakan terminal Laragon atau panggil `php.exe` dengan path instalasi lengkap. Contoh PowerShell untuk Laragon pada komputer ini:

```powershell
& "C:/laragon/bin/php/php-8.3.33-Win32-vs16-x64/php.exe" -S 127.0.0.1:8077 -t public
```

Untuk hosting PHP, arahkan document root ke folder `public/`.

Isi variabel lingkungan `SIJARLA_EMAIL` dengan alamat email tim sebelum menjalankan PHP untuk menampilkannya di halaman Kontak.

## Struktur proyek

- `app/` berisi konfigurasi, fungsi pemformatan dan komponen PHP bersama.
- `data/incidents.json` berisi arsip kejadian pilihan dengan tautan laporan BNPB.
- `data/report-details.json` memuat kronologi, dampak dan penanganan untuk 13 artikel.
- `data/regional-articles.json` memuat artikel tentang penyebaran asap lintas negara dari sumber ASMC dan NEA.
- `public/` berisi halaman PHP terpisah dan aset CSS, gambar, serta JavaScript.
- `public/assets/js/map.js` mengatur peta dasar. `hotspots.js` memuat deteksi per bulan dan mengelompokkan titik berdekatan dengan Supercluster.

## Data, integrasi, dan aset

- Peta Leaflet memakai Esri World Imagery dan tile vektor harian VIIRS NOAA-20 dari [NASA FIRMS/GIBS](https://firms.modaps.eosdis.nasa.gov/content/tutorials/gibs/). Pilih bulan untuk memuat deteksi Januari hingga 16 November 2026 (UTC) pada area Indonesia dan sekitarnya. Warna menunjukkan kategori keyakinan deteksi. Popup menampilkan waktu, koordinat, dan nama wilayah terdekat dari [OpenStreetMap/Nominatim](https://www.openstreetmap.org/copyright) jika tersedia.
- Bootstrap (MIT), Leaflet (BSD-2-Clause), DM Sans dan Barlow Condensed (SIL Open Font License). Gambar utama dibuat dengan ImageGen dan ditandai sebagai ilustrasi pada beranda.
