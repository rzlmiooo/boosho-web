# Web Toko Buku BooSho

BooSho adalah platform toko buku digital berbasis web yang dikembangkan menggunakan framework Laravel 11. Proyek ini dibangun untuk memenuhi standar arsitektur perangkat lunak yang memisahkan hak akses pengguna (Role-Based Access Control), menyediakan jalur RESTful API yang terdokumentasi dengan rapi, dan menghadirkan pengalaman pengguna yang interaktif dengan integrasi peta digital serta sistem notifikasi.

---

## A. Identitas Pengembang

**Kelompok 4**

* **Ketua**: Rizal Maulana (2305101018)
* **Anggota**:
  1. Reza Fairul Nizam (2305101022)
  2. M. Wahyue Nesaputro (2305102243)
  3. Sofyan Dwi Saputro (2305101003)
  4. Rivan Wahyu Mardoni (2305101014)

---

## B. Spesifikasi Teknologi

### Bahasa Pemrograman

- **PHP** (Backend & Framework Engine)
- **JavaScript** (Interaktivitas Client-Side)
- **SQL** (Manajemen Database MySQL)
- **HTML & CSS** (Struktur & Desain UI)

### Framework, Library, & API

- **Framework**: Laravel 11 (MVC PHP Framework)
- **CSS Utility**: Tailwind CSS (via CDN)
- **JS Framework**: Alpine.js (State management UI & Dropdown)
- **Library Peta**: LeafletJS (Peta interaktif OpenStreetMap)
- **API Geocoding**: OpenStreetMap Nominatim Reverse Geocoding API
- **Notifikasi**: SweetAlert2 (Pop-up interaktif & konfirmasi)
- **Database Driver**: Eloquent ORM & Query Builder

---

## C. Fungsi & Fitur Proyek

### 1. Sistem Autentikasi & Otorisasi (RBAC)

- Register akun baru untuk pelanggan.
- Login dan Logout aman dengan proteksi session.
- Perbedaan antarmuka dan otorisasi secara ketat antara **Admin** dan **User**.

### 2. Panel Admin (Manajemen Buku & Transaksi)

- **Dashboard Stat**: Melihat total buku, total stok, taksiran nilai aset, dan rilisan buku terbaru.
- **CRUD Buku**: Menambah, mengubah, dan menghapus buku (termasuk upload cover buku).
- **Batch Discount**: Menerapkan diskon massal secara terjadwal ke beberapa buku sekaligus.
- **Manajemen Transaksi**:
  - Merilis kode pembayaran (Virtual Account) untuk pelanggan yang mengajukan pembelian.
  - Memproses pengiriman barang dengan menginput nomor resi resmi.

### 3. Panel Pelanggan (User)

- **Katalog Interaktif**: Fitur pencarian buku berdasarkan judul/penulis, filter harga, filter stok, dan pengurutan (harga, terbaru, judul).
- **Rekomendasi Pintar**: Rekomendasi dinamis berdasarkan 3 kategori/genre buku terakhir yang dilihat oleh user.
- **Keranjang Belanja**: Menambah kuantitas, mengurangi kuantitas, menghapus item, dan kalkulasi subtotal instan.
- **Checkout dengan Pinpoint Map**:
  - Deteksi posisi otomatis pelanggan via **GPS Geolocation API**.
  - Drop marker / geser pinpoint pada **OpenStreetMap Leaflet** untuk menentukan titik lokasi pengiriman yang akurat.
  - Mengambil alamat jalan secara otomatis dari koordinat titik peta menggunakan **Nominatim API** untuk mengisi kolom alamat pengiriman secara instan.
- **Riwayat Transaksi**: Simulasi pembayaran Virtual Account, konfirmasi terima barang, dan pelacakan status pesanan.
- **Review & Rating Buku**:
  - Menulis ulasan dan rating 1-5 bintang pada buku yang telah dibeli.
  - Membaca ulasan dengan analisis sentimen otomatis (Positif & Negatif) dan filter berbasis sentimen.

### 4. Sistem Notifikasi Terintegrasi

- **Navbar Bell Icon Dropdown**: Lonceng notifikasi interaktif yang melacak lencana belum dibaca (*unread count*).
- **Notifikasi Transaksi**: Status order (Checkout sukses, Kode VA terbit, Pembayaran sukses, Pengiriman resi, Pesanan selesai).
- **Notifikasi Promo & Rekomendasi**: Broadcast otomatis notifikasi diskon saat admin membuat diskon massal, dan saran buku baru berdasarkan kategori pembelian saat pesanan selesai.

---

## D. Kelebihan Proyek

1. **Akurasi Alamat Pengiriman**: Integrasi LeafletJS + Nominatim API membuat pengisian alamat pengiriman menjadi otomatis dan sangat akurat, meminimalisir kesalahan input manual oleh pembeli.
2. **Sistem Notifikasi Real-time Client-Side**: Notifikasi dikelola dengan *view composer* global sehingga lonceng notifikasi diperbarui secara mulus di seluruh halaman aplikasi.
3. **Penyajian Data Responsif**: Desain premium berbasis Tailwind CSS yang responsif untuk perangkat mobile maupun desktop.
4. **Analisis Sentimen Ulasan**: Mempermudah calon pembeli dalam memilah komentar positif dan negatif dari pembeli sebelumnya.

---

## E. Kekurangan Proyek (Bug/Warning/Limitasi)

1. **Simulasi Transaksi Non-Komersial**: Fitur pembayaran saat ini masih menggunakan skema simulasi perubahan status internal di database (belum dihubungkan ke Payment Gateway pihak ketiga seperti Midtrans/Xendit secara nyata).
2. **Ketergantungan CDN Eksternal**: File CSS Tailwind, AlpineJS, LeafletJS, dan SweetAlert2 dimuat menggunakan CDN eksternal. Aplikasi memerlukan koneksi internet aktif agar tampilan dan fungsi peta/notifikasi dapat dirender dengan benar.
3. **Limitasi API Nominatim**: Layanan reverse geocoding OpenStreetMap Nominatim memiliki batas kecepatan (*rate limit*) untuk akses publik. Jika digunakan dalam frekuensi sangat tinggi secara terus-menerus, API dapat mengembalikan respon lambat atau diblokir sementara.

---

## F. Dokumentasi Proyek

### Rancangan ERD Database

`[Tempat ERD Proyek / Placeholder Gambar ERD]`

### Halaman Utama / Katalog Buku

`[Tempat Screenshot Katalog / Placeholder Gambar Katalog]`

### Keranjang Belanja & Fitur Pinpoint Alamat Otomatis

`[Tempat Screenshot Peta Pengiriman / Placeholder Gambar Peta]`

### Dropdown Notifikasi & Dashboard Admin

`[Tempat Screenshot Notifikasi & Admin / Placeholder Gambar Notifikasi]`
