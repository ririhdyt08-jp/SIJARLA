# SIJARLA 2026
### Sistem Informasi Jaga Rimba dan Lahan

> **Karya Desain dan Pengembangan Web untuk TECHNO VERSARY VOL. II**
>
> Himpunan Mahasiswa Teknik Informatika (HIMA-TI), Fakultas Ilmu Komputer, Universitas Kuningan (UNIKU)
>
> **Tema utama** — EcoDigital for Environment: Web Design Competition
>
> **Subtema 8** — Isu Lingkungan Global (Kebakaran Hutan dan Lahan di Indonesia)

**Website** — [sijarla.vercel.app](https://sijarla.vercel.app/)

---

## 📌 Identitas Tim dan Anggota

- **Nama tim** — BLARATAN
- **Nama folder pengumpulan** — `webdesign_BLARATAN_technoversary_vol.II`
- **Anggota** — Fies Medhali Anugrahsyah dan Risma Hidayati, Politeknik Negeri Indramayu
- **Periode proyek** — November 2026

---

## 📖 Deskripsi Website

**SIJARLA** adalah portal edukasi independen tentang kebakaran hutan dan lahan (karhutla) di Indonesia. Pengunjung dapat mengikuti laporan kejadian, menelusuri deteksi panas satelit melalui peta interaktif, dan menemukan kanal resmi untuk melaporkan asap atau titik api.

### Mengapa Karhutla Menjadi Isu Lingkungan Global?

1. **Kabut asap lintas negara.** Angin dapat membawa asap ke negara tetangga dan menurunkan kualitas udara di wilayah yang jauh dari lokasi kebakaran.
2. **Emisi dari hutan dan gambut.** Kebakaran melepaskan karbon yang tersimpan di vegetasi dan tanah gambut, sehingga turut berkontribusi terhadap perubahan iklim.
3. **Kerusakan ekosistem.** Kebakaran mengurangi habitat satwa dan mengganggu kehidupan masyarakat di sekitar kawasan terdampak.

---

## 🎨 Palet Warna dan Desain Visual

| Warna | Kode | Penggunaan |
| --- | --- | --- |
| Hitam | `#151515` | Latar utama, navigasi, dan footer |
| Abu-abu gelap | `#202020`, `#42403E` | Kartu dan garis pembatas |
| Putih hangat dan abu-abu terang | `#F5F3F0`, `#CBC5BF` | Judul, isi, dan teks pendukung |
| Oranye | `#FF7436` | Aksen bara api, tombol utama, dan penanda navigasi |

Foto karhutla menjadi latar judul halaman Tentang, Artikel, dan Kontak. Ikon garis dan tata letak responsif digunakan di seluruh situs.

---

## 🛠️ Teknologi, Data, dan Kredit

- **Backend** — PHP 8.1+ dengan komponen template modular dan data JSON.
- **Frontend** — Bootstrap 5.3.9, CSS khusus, JavaScript, dan ikon garis SVG yang dirender oleh aplikasi.
- **Peta** — Leaflet 1.9.4, Esri World Imagery untuk citra satelit, dan OpenStreetMap untuk peta jalan. Nominatim membantu menampilkan wilayah terdekat jika tersedia.
- **Deteksi panas** — VIIRS NOAA-20 dari [NASA FIRMS/GIBS](https://firms.modaps.eosdis.nasa.gov/content/tutorials/gibs/). Tile vektor dibaca dengan VectorTile dan Pbf, lalu dikelompokkan dengan Supercluster.
- **Arsip laporan** — Sumber laporan BNPB untuk kejadian lapangan serta ASMC dan NEA untuk artikel regional. Rujukan tiap kasus tercantum pada artikelnya.
- **Tipografi** — Barlow Condensed dan DM Sans melalui Google Fonts.
- **Deployment** — Vercel, dengan runtime `vercel-php@0.8.0` yang didefinisikan di `vercel.json`.

---

## 📂 Struktur Direktori Proyek

```text
SIJARLA/
├── api/
│   └── index.php                # Router halaman untuk Vercel
├── app/
│   ├── bootstrap.php            # Konfigurasi, helper, dan pemuatan arsip kejadian
│   ├── header.php               # Metadata, stylesheet, dan navigasi
│   ├── footer.php               # Footer dan pemuatan skrip per halaman
│   ├── cards.php                # Komponen kartu laporan
│   └── about-topic.php          # Isi lengkap topik Tentang
├── data/
│   ├── incidents.json           # Arsip kejadian karhutla pilihan
│   ├── report-details.json      # Kronologi, dampak, dan penanganan kejadian
│   └── regional-articles.json   # Artikel kabut asap lintas negara
├── public/
│   ├── index.php                # Beranda dan laporan terbaru
│   ├── tentang.php              # Kartu topik dan halaman bacaan lengkap
│   ├── artikel.php              # Arsip, pencarian, dan detail artikel
│   ├── peta.php                 # Peta interaktif dan rekap data karhutla
│   ├── kontak.php               # Kanal resmi dan kontak tim SIJARLA
│   └── assets/
│       ├── css/site.css         # Tema, tata letak, dan animasi
│       ├── js/                  # Peta, deteksi panas, video, dan gulir kartu
│       ├── img/                 # Foto, ilustrasi, dan logo
│       └── video/               # Video latar beranda
├── vercel.json                  # Routing dan runtime deployment
└── README.md
```

---

## 🚀 Instalasi dan Cara Menjalankan

### Prasyarat

- **PHP 8.1 atau lebih baru** dengan ekstensi `mbstring` aktif untuk halaman detail artikel.
- Browser modern dan koneksi internet untuk memuat pustaka, font, citra peta, dan data satelit.

### Menjalankan dengan server bawaan PHP

1. Buka terminal di folder proyek yang berisi `README.md`.
2. Jalankan perintah berikut.

   ```bash
   php -S 127.0.0.1:8077 -t public
   ```

3. Buka [127.0.0.1:8077](http://127.0.0.1:8077) di browser. Tekan **Ctrl+C** di terminal untuk menghentikan server.

### Alternatif dengan PHP dari XAMPP atau Laragon

Jika perintah `php` belum dikenali, buka terminal di folder proyek dan panggil `php.exe` dengan lokasi instalasinya. Untuk XAMPP, contoh perintah PowerShell berikut menggunakan lokasi instalasi bawaan.

```powershell
& 'C:\xampp\php\php.exe' -S 127.0.0.1:8077 -t public
```

Untuk hosting PHP sendiri, arahkan document root ke `public/`.

---

## 🌟 Fitur Unggulan

1. **Peta deteksi panas interaktif**
   - Pilih bulan pengamatan dari Januari hingga 16 November 2026 (UTC), ganti lapisan satelit atau jalan, dan filter deteksi berkeyakinan tinggi.
   - Lingkaran bernomor menunjukkan jumlah deteksi dalam grup. Klik lingkaran untuk memperbesar peta, atau klik titik untuk melihat waktu, koordinat, dan wilayah terdekat jika tersedia.
   - Tombol **Buka di Peta Satelit** pada laporan yang memiliki koordinat menandai lokasi kejadian di peta.

2. **Arsip dan rekap kejadian**
   - Cari laporan berdasarkan lokasi atau kata kunci dan gunakan filter kategori, pulau, atau bulan.
   - Rekap di bawah peta merangkum laporan terbaru, perkiraan luas terbakar, dan jumlah kejadian per provinsi dari arsip SIJARLA.

3. **Materi edukasi dan kanal pelaporan**
   - Tujuh kartu di halaman Tentang membuka bacaan lengkap mengenai SIJARLA, karhutla, penyebab, dampak, asap lintas negara, pencegahan, dan metodologi data.
   - Halaman Kontak menyediakan tautan ke kanal resmi pelaporan dan email tim [timsijarla@gmail.com](mailto:timsijarla@gmail.com).

4. **Beranda responsif dan interaktif**
   - Baris informasi dan kartu laporan terbaru bergulir otomatis. Animasi mengikuti preferensi pengurangan gerak di perangkat.

> Deteksi satelit menunjukkan anomali panas yang memerlukan verifikasi lapangan. Rekap provinsi dihitung dari kejadian yang tercatat di arsip SIJARLA.
