-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Sep 2025 pada 05.44
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ujian_app`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_ujian`
--

CREATE TABLE `hasil_ujian` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `formasi` varchar(50) NOT NULL,
  `score` int(11) DEFAULT 0,
  `jawaban_salah` int(3) NOT NULL DEFAULT 0,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `duration` int(11) NOT NULL DEFAULT 0,
  `status` enum('proses','selesai') DEFAULT 'proses',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jawaban_user`
--

CREATE TABLE `jawaban_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `soal_id` int(10) UNSIGNED NOT NULL,
  `jawaban` enum('A','B','C','D') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` enum('A','B','C','D') NOT NULL,
  `formasi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `questions`
--

INSERT INTO `questions` (`id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`, `formasi`) VALUES
(115, 'Doxazosin adalah jenis obat antihipertensi yang bekerja dengan cara menghambat hormon ... agar tidak mengikat reseptor alfa.', 'Adrenalin', 'Katekolamin', 'Dopamin', 'Serotonin', 'B', 'Dokter'),
(116, 'Obat OAT yang dapat menimbulkan warna kemerahan pada urin adalah …', 'INH', 'Rifampisin', 'Pirazinamid', 'Streptomycin', 'B', 'Dokter'),
(117, 'Apa nama penyakit yang ditandai dengan peningkatan tekanan darah pada pembuluh darah arteri ...', 'Diabetes mellitus', 'Asma', 'Hipertensi', 'Hipotensi', 'C', 'Dokter'),
(118, 'Apa yang dimaksud dengan stimulus kontekstual menurut teori Roy Adaptation Model?', 'Stimulus yang secara langsung dapat menyebabkan keadaan sakit dan ketidakseimbangan yang dialami saat ini', 'Stimulus yang dapat menunjang terjadinya sakit (faktor presipitasi)', 'Sikap, keyakinan dan pemahaman individu yang dapat mempengaruhi terjadinya keadaan tidak sehat (faktor predisposisi), sehingga terjadi kondisi sakit', 'Keadaan proses mental dalam tubuh manusia berupa pengalaman, kemampuan emosional, kepribadian dan Proses stressor biologis yang berasal dari dalam tubuh individu', 'B', 'Dokter'),
(119, 'Apa fungsi utama dari alat Spirometer...', 'Mendiagnosis penyakit paru-paru', 'Memeriksa tekanan darah', 'Memonitor denyut jantung', 'Menilai fungsi ginjal', 'C', 'Dokter'),
(120, 'Seorang laki-laki 19 tahun datang dengan keluhan benjolan pada jempol, telunjuk tangan kanan, dan kiri. Benjolan dirasakan sejak 6 bulan lalu. Awalnya muncul benjolan kecil pada telunjuk tangan kiri yang lama kelamaan membesar dan menyebar ke jempol kanan, jempol kiri, dan telunjuk kanan. Jika digunakan untuk menulis terasa sakit, namun jika tidak disentuh tidak terasa sakit. Pemeriksaan dermatologis mendapatkan papul verukosa abu abu sampai kehitaman, multiple, bentuk bulat, batas tegas, dengan distribusi bilateral. Diagnosis pada pasien tersebut adalah ...', 'Veruka vulgaris', 'Keratosis seboroik', 'Nevus verukosus', 'Moluskum kontagiosum', 'A', 'Dokter'),
(121, 'Pengukuran tekanan darah normal manusia dewasa adalah ….', '120/80 mmHg', '140/90 mmHg', '130/80 mmHg', '130/90 mmHg', 'A', 'Dokter'),
(122, 'Apa yang dimaksud dengan triase dalam konteks penanganan gawat darurat di rumah sakit ...', 'Proses pembersihan luka', 'Penyaringan dan klasifikasi pasien berdasarkan tingkat kegawatan', 'Pemberian obat untuk mengendalikan nyeri', 'Proses pemberian cairan intravena', 'B', 'Dokter'),
(123, 'Unsur-unsur subsistem upaya kesehatan terdiri dari hal-hal berikut, kecuali …', 'Tenaga kesehatan', 'Upaya kesehatan', 'Fasilitas pelayanan kesehatan', 'Sumber daya upaya kesehatan', 'A', 'Dokter'),
(124, 'Laki-laki 15 tahun dibawa ke IGD dengan keluhan pingsan setelah bermain voli. Pada pemeriksaan fisik dan penunjang lain tidak didapatkan kelainan. Apakah diagnosis yang sesuai?', 'Reaksi Konversi', 'Gangguan Somatisasi', 'Gangguan Hipokondriasis', 'Gangguan Cemas menyeluruh', 'A', 'Dokter'),
(125, 'Berdasarkan prinsip ALARA (As Low As Reasonably Achievable), apa tujuan utama dari pendekatan ini dalam radiologi?', 'Mengurangi penggunaan teknologi pencitraan', 'Memaksimalkan kualitas gambar pencitraan', 'Meminimalkan dosis radiasi sambil tetap mendapatkan hasil yang efektif', 'Meningkatkan waktu paparan untuk hasil gambar yang lebih baik', 'C', 'Radiologi'),
(126, 'Dalam radiologi, parameter apakah yang digunakan untuk mengukur jumlah radiasi yang diserap oleh jaringan tubuh pasien?', 'Sievert', 'Gray', 'Becquerel', 'Coulomb', 'B', 'Radiologi'),
(127, 'Apa perbedaan utama antara CT Scan dan MRI dalam penggunaan teknologi pencitraan?', 'CT Scan menggunakan radiasi sinar-X, sedangkan MRI menggunakan medan magnet', 'CT Scan lebih aman digunakan pada ibu hamil dibandingkan MRI', 'MRI menghasilkan gambar dua dimensi, sedangkan CT Scan menghasilkan gambar tiga dimensi', 'CT Scan lebih baik untuk jaringan lunak, sementara MRI lebih baik untuk tulang', 'A', 'Radiologi'),
(128, 'Apa tujuan penggunaan kontras dalam prosedur pencitraan seperti CT atau MRI?', 'Mengurangi waktu prosedur', 'Meningkatkan resolusi gambar tulang', 'Membantu memperjelas pembuluh darah atau organ tertentu', 'Mengurangi dosis radiasi yang diterima pasien', 'C', 'Radiologi'),
(129, 'Saat melakukan radiografi dada, pasien diminta untuk menahan napas. Apa tujuan dari tindakan ini?', 'Mencegah radiasi mengenai paru-paru', 'Mengurangi gerakan yang dapat mengaburkan gambar', 'Mempercepat proses pencitraan', 'Menghasilkan gambar lebih gelap', 'B', 'Radiologi'),
(130, 'Apa tujuan dari penggunaan grid pada radiografi?', 'Mengurangi dosis radiasi', 'Meningkatkan ketajaman gambar dengan mengurangi radiasi hamburan', 'Meningkatkan kecepatan proses pencitraan', 'Mengurangi waktu eksposur', 'B', 'Radiologi'),
(131, 'Apa yang dimaksud dengan istilah “penyinaran posterior-anterior” dalam radiografi dada?', 'Sinar-X diarahkan dari depan ke belakang tubuh', 'Sinar-X diarahkan dari belakang ke depan tubuh', 'Sinar-X diarahkan dari sisi kiri ke sisi kanan tubuh', 'Sinar-X diarahkan dari sisi kanan ke sisi kiri tubuh', 'B', 'Radiologi'),
(132, 'Pada pemeriksaan MRI, istilah “T1-weighted” dan “T2-weighted” merujuk pada apa?', 'Teknik pengaturan dosis radiasi', 'Pengaturan frekuensi magnet yang digunakan', 'Jenis pemuatan gambar berdasarkan waktu relaksasi jaringan', 'Tipe kontras yang digunakan', 'C', 'Radiologi'),
(133, 'Dalam pemilihan parameter teknik pencitraan sinar-X, apa fungsi dari pengaturan kV (kilovolt)?', 'Mengatur kontras gambar dengan mengontrol energi sinar-X', 'Mengontrol durasi penyinaran', 'Mengukur dosis radiasi yang diserap oleh pasien', 'Mengatur resolusi gambar', 'A', 'Radiologi'),
(134, 'Protokol keamanan radiasi pada staf radiologi melibatkan konsep “waktu, jarak, dan perlindungan”. Apa fungsi dari konsep “jarak” dalam hal ini?', 'Mengurangi paparan radiasi dengan mempersingkat waktu paparan', 'Mengurangi paparan radiasi dengan meningkatkan jarak dari sumber radiasi', 'Meningkatkan kontras gambar dengan mendekatkan alat pencitraan', 'Mengurangi efek samping radiasi pada pasien', 'B', 'Radiologi'),
(135, 'Penggunaan kontras barium biasanya dilakukan pada pemeriksaan radiologi apa?', 'USG', 'CT Scan kepala', 'Rontgen gastrointestinal', 'MRI otak E. Angiografi jantung', 'C', 'Radiologi'),
(136, 'Seorang pasien datang ke rumah sakit dengan keluhan sesak napas dan batuk berdahak. Setelah dilakukan pemeriksaan, dokter mencatat diagnosis “Pneumonia Bakterial” dalam rekam medis pasien. Berdasarkan informasi ini, jenis data apa yang tercatat dalam rekam medis pasien tersebut?', 'Data pemeriksaan fisik', 'Data diagnosis medis', 'Data obat yang diberikan', 'Data terapi yang dilakukan', 'B', 'Rekam Medis'),
(137, 'Dalam suatu rumah sakit, terdapat sebuah sistem rekam medis elektronik yang memungkinkan akses informasi medis pasien oleh dokter, perawat, dan tenaga medis lainnya. Namun, sistem tersebut rawan diretas. Apa yang harus dilakukan rumah sakit untuk mengatasi masalah ini?', 'Mengurangi jumlah tenaga medis yang dapat mengakses data', 'Memperkenalkan penggunaan kata sandi yang lebih sederhana', 'Menggunakan enkripsi data dan pembatasan akses berdasarkan otorisasi', 'Membuka akses data untuk semua staf rumah sakit', 'B', 'Rekam Medis'),
(138, 'Seorang pasien menerima pengobatan untuk hipertensi. Setelah beberapa minggu, pasien mengeluhkan efek samping berupa pusing dan mual. Berdasarkan kasus ini, langkah pertama yang harus dilakukan oleh petugas rekam medis adalah…', 'Memberikan resep obat lain tanpa berkonsultasi dengan dokter', 'Menyalahkan pasien karena tidak mengikuti petunjuk pengobatan', 'Mencatat keluhan pasien dalam rekam medis dan memberitahukan dokter', 'Mengabaikan keluhan karena merupakan efek samping biasa', 'C', 'Rekam Medis'),
(139, 'Ketika menyusun laporan rekam medis pasien, petugas harus memastikan bahwa data yang tercatat adalah akurat dan lengkap. Apa risiko yang mungkin terjadi jika ada kesalahan pencatatan dalam rekam medis?', 'Proses pengobatan menjadi lebih efisien', 'Data pasien bisa disalahartikan, mempengaruhi keputusan medis, dan berpotensi merugikan pasien', 'Tidak ada konsekuensi karena data medis bisa diperbarui kapan saja', 'Data pasien akan terlindungi dari akses yang tidak sah', 'B', 'Rekam Medis'),
(140, 'Sistem informasi rumah sakit (SIR) memungkinkan integrasi antara berbagai departemen, seperti administrasi, farmasi, dan rekam medis. Apa manfaat utama dari integrasi ini dalam hal pengelolaan rekam medis?', 'Memungkinkan akses data yang lebih cepat dan akurat antar departemen', 'Memperburuk kecepatan pengambilan keputusan medis', 'Meningkatkan biaya operasional rumah sakit', 'Mengurangi jumlah pasien yang dilayani', 'A', 'Rekam Medis'),
(141, 'Dalam dunia rekam medis, sistem pengkodean memainkan peran yang sangat penting dalam mendokumentasikan informasi medis pasien. Salah satu sistem pengkodean yang paling umum digunakan adalah ICD (International Classification of Diseases). Sistem ini digunakan untuk berbagai tujuan administratif, termasuk klaim asuransi dan laporan statistik kesehatan. Apa tujuan utama penggunaan kode ICD dalam rekam medis?', 'Menentukan biaya pengobatan pasien', 'Mengidentifikasi prosedur medis yang dilakukan', 'Menyusun jadwal perawatan pasien', 'Mengklasifikasikan dan mengkodekan diagnosis penyakit', 'D', 'Rekam Medis'),
(142, 'Seorang pasien yang baru saja dirawat di rumah sakit diberi resep obat oleh dokter. Petugas rekam medis harus mencatat semua obat yang diberikan dalam rekam medis. Apa yang harus dicatat selain nama obat?', 'Nama dokter yang meresepkan obat', 'Jumlah obat yang dibutuhkan', 'Efek samping yang mungkin terjadi', 'Semua informasi terkait dosis, frekuensi, dan cara pemberian obat', 'D', 'Rekam Medis'),
(143, 'Dalam konteks hukum yang berlaku di banyak negara, rekam medis pasien diperlakukan sebagai informasi yang bersifat sangat sensitif dan harus dijaga kerahasiaannya. Apa yang dimaksud dengan rekam medis yang bersifat “rahasia” dalam konteks hukum dan mengapa perlindungan ini sangat penting?', 'Data medis yang dilindungi oleh hukum untuk mencegah kebocoran informasi', 'Semua data medis pasien yang hanya dapat dibaca oleh pasien itu sendiri', 'Informasi medis yang dapat dibagikan kepada siapa saja yang memintanya', 'Rekam medis yang hanya dapat diakses oleh dokter yang merawat pasien', 'A', 'Rekam Medis'),
(144, 'Bagaimana seharusnya petugas rekam medis menangani kesalahan dalam data rekam medis yang sudah tercatat?', 'Menghapus data yang salah dan mencatat data baru', 'Menyembunyikan kesalahan agar tidak mempengaruhi proses medis', 'Melaporkan kesalahan kepada atasan dan memperbaiki dengan prosedur yang sesuai', 'Mengabaikan kesalahan karena tidak mempengaruhi perawatan pasien', 'C', 'Rekam Medis'),
(145, 'Jika seorang pasien mengalami alergi terhadap obat tertentu, bagaimana informasi ini seharusnya dicatat dalam rekam medis?', 'Sebagai catatan pengingat untuk dokter yang merawat', 'Hanya di bagian akhir rekam medis tanpa penjelasan', 'Sebagai informasi yang dapat dibaca oleh semua petugas medis', 'Di bagian yang mencatat alergi obat pasien secara khusus', 'D', 'Rekam Medis'),
(146, 'Seorang pasien berusia 65 tahun datang ke puskesmas dengan keluhan pusing, mual, dan lemas. Dari hasil pemeriksaan diketahui bahwa tekanan darahnya adalah 90/60 mmHg dan ia baru saja pulang dari perjalanan jauh. Pasien mengaku hanya minum sedikit air selama perjalanan. Berdasarkan data tersebut, kemungkinan besar kondisi pasien disebabkan oleh:', 'Infeksi bakteri', 'Kekurangan kalium dalam darah', 'Dehidrasi ringan hingga sedang', 'Gangguan fungsi jantung', 'C', 'Perawat'),
(147, 'Seorang anak berusia 10 tahun dibawa ke IGD dengan demam tinggi selama 3 hari, nyeri sendi, dan muncul ruam merah di kulit. Pemeriksaan darah menunjukkan penurunan trombosit menjadi 90.000/mm³. Grafik suhu tubuh menunjukkan peningkatan suhu secara konsisten. Berdasarkan informasi tersebut, diagnosis awal yang paling mungkin adalah:', 'Infeksi saluran pernapasan akut', 'DBD (Demam Berdarah Dengue)', 'Demam tifoid', 'Influenza biasa', 'B', 'Perawat'),
(148, 'Jika semua pasien yang mengalami syok hipovolemik memiliki riwayat kehilangan cairan yang signifikan, dan beberapa pasien yang mengalami syok hipovolemik membutuhkan transfusi darah, maka manakah pernyataan yang paling tepat berdasarkan informasi tersebut?', 'Semua pasien yang kehilangan cairan akan mengalami syok hipovolemik.', 'Semua pasien yang mengalami syok hipovolemik membutuhkan transfusi darah.', 'Pasien yang membutuhkan transfusi darah pasti kehilangan cairan yang signifikan.', 'Beberapa pasien dengan syok hipovolemik mungkin tidak membutuhkan transfusi darah.', 'D', 'Perawat'),
(149, 'Di sebuah desa terjadi wabah diare yang menyebabkan banyak warga, terutama anak-anak dan lansia, dirawat. Kepala puskesmas setempat meminta tim medis untuk segera menurunkan angka kejadian dengan cepat. Tindakan awal yang paling tepat untuk dilakukan adalah:', 'Memberikan antibiotik massal kepada semua warga desa.', 'Mengadakan seminar tentang pentingnya kebersihan lingkungan.', 'Menyediakan cairan rehidrasi oral (oralit) dan memastikan warga mendapat akses air bersih.', 'Merujuk seluruh pasien ke rumah sakit besar.', 'C', 'Perawat'),
(150, 'Seorang pasien anak dengan berat badan 20 kg mendapatkan resep obat parasetamol dengan dosis 10 mg/kgBB per 8 jam. Sediaan obat berupa sirup dengan konsentrasi 250 mg/5 mL. Berapa mL obat yang harus diberikan setiap kali minum?', '2 mL', '3 mL', '4 mL', '5 mL', 'D', 'Perawat'),
(151, 'Seorang pasien memiliki riwayat tekanan darah normal 120/80 mmHg. Setelah menjalani pemeriksaan pasca-operasi, tekanan darahnya menjadi 100/70 mmHg. Berapa persentase penurunan tekanan darah sistolik pasien tersebut?', '12%', '16.67%', '20%', '25%', 'B', 'Perawat'),
(152, 'Dalam satu minggu, sebuah klinik mencatat hasil pengukuran suhu tubuh pasien sebagai berikut: 37.5°C, 38.0°C, 37.2°C, 38.3°C, dan 37.8°C. Berapakah rata-rata suhu tubuh pasien tersebut?', '37.56°C', '37.76°C', '37.92°C', '38.10°C', 'B', 'Perawat'),
(153, 'Seorang perawat perlu memberikan cairan infus sebanyak 1.5 liter dalam waktu 6 jam. Berapa mL per jam yang harus diatur pada alat infus tersebut?', '100 mL/jam', '150 mL/jam', '200 mL/jam', '250 mL/jam', 'D', 'Perawat'),
(154, 'Bacalah kutipan berikut:\r\n\r\n“Dehidrasi adalah kondisi yang terjadi ketika tubuh kehilangan lebih banyak cairan daripada yang dikonsumsi, sehingga fungsi organ terganggu. Pada kasus dehidrasi berat, dapat terjadi penurunan tekanan darah, hilangnya kesadaran, hingga syok hipovolemik. Oleh karena itu, penting untuk mengidentifikasi tanda awal dehidrasi agar tidak berlanjut ke kondisi yang lebih serius.”\r\n\r\nApa tujuan utama paragraf tersebut?', 'Menjelaskan penyebab utama dehidrasi', 'Menguraikan efek samping pengobatan pada dehidrasi', 'Menyampaikan urgensi pencegahan dehidrasi', 'Menggambarkan proses fisiologis tubuh dalam mengatur cairan', 'C', 'Perawat'),
(155, 'Bacalah pernyataan berikut:\r\n\r\n“Dalam pelaksanaan triase di IGD, perawat harus mengidentifikasi kondisi pasien berdasarkan tingkat keparahan. Pasien dengan kondisi kritis mendapat prioritas penanganan utama, sementara pasien dengan keluhan ringan menunggu hingga ada sumber daya yang tersedia.”\r\n\r\nSimpulan yang paling sesuai dengan pernyataan di atas adalah:', 'Semua pasien di IGD mendapatkan penanganan yang sama cepatnya.', 'Triase bertujuan membagi pasien berdasarkan keluhan kesehatan.', 'Prosedur triase mengutamakan pasien yang mampu menunggu lebih lama.', 'Pasien dengan kondisi kritis memiliki prioritas tertinggi dalam penanganan.', 'D', 'Perawat'),
(156, 'Seorang analis laboratorium sedang memeriksa hasil preparat darah dari pasien dengan gejala demam tinggi dan nyeri otot. Dalam pengamatan mikroskopis, ia menemukan bentuk cincin di dalam eritrosit. Organisme apa yang paling mungkin ditemukan?', 'Giardia lamblia', 'Plasmodium spp.', 'Entamoeba histolytica', 'Toxoplasma gondii', 'B', 'ATLM'),
(157, 'Dalam pemeriksaan glukosa darah, seorang mahasiswa salah menghitung pengenceran sampel. Nilai glukosa yang terbaca menjadi jauh lebih tinggi dari normal. Kesalahan ini berkaitan dengan:', 'Teknik pengambilan spesimen', 'Proses sentrifugasi', 'Perhitungan konsentrasi larutan', 'Kondisi enzim glukosa oksidase', 'C', 'ATLM'),
(158, 'Seorang teknisi menggunakan mikroskop binokuler untuk mengamati jaringan yang telah diwarnai Hematoxylin-Eosin. Tujuan utama pewarnaan ini adalah:', 'Mempermudah fiksasi', 'Menentukan komposisi kimia', 'Visualisasi struktur sel dan jaringan', 'Pencegahan kontaminasi', 'C', 'ATLM'),
(159, 'Pemeriksaan hematologi menunjukkan jumlah trombosit yang rendah. Kondisi ini dikenal sebagai:', 'Leukositosis', 'Anemia', 'Eritrositosis', 'Trombositopenia', 'D', 'ATLM'),
(160, 'Untuk membuat 100 mL larutan NaCl 0,45% dari stok 0,9%, berapa volume stok yang dibutuhkan?', '25 mL', '35 mL', '45 mL', '50 mL', 'D', 'ATLM'),
(161, 'Seorang pasien dirujuk untuk uji fungsi ginjal. Parameter kimia klinik yang digunakan adalah:', 'AST dan ALT', 'Glukosa dan kolesterol', 'Ureum dan kreatinin', 'LDH dan bilirubin', 'C', 'ATLM'),
(162, 'Sterilisasi alat gelas laboratorium dilakukan dengan suhu dan tekanan tinggi. Alat yang digunakan adalah:', 'Inkubator', 'Autoklaf', 'Sentrifuge', 'Spektrofotometer', 'B', 'ATLM'),
(163, 'Dalam pengamatan feses, ditemukan telur berbentuk lonjong dan berdinding tebal. Parasit yang mungkin adalah:', 'Plasmodium vivax', 'Ascaris lumbricoides', 'Entamoeba coli', 'Fasciola hepatica', 'B', 'ATLM'),
(164, 'Kadar hemoglobin pasien berada di bawah nilai normal. Kemungkinan kondisi yang terjadi:', 'Infeksi virus', 'Hiperkolesterolemia', 'Anemia', 'Hiperglikemia', 'C', 'ATLM'),
(165, 'Metode ELISA digunakan untuk pemeriksaan serologi. Prinsip dasar metode ini adalah:', 'Pewarnaan mikroorganisme', 'Deteksi enzim dalam urin', 'Reaksi antigen-antibodi dengan sinyal warna', 'Pengendapan partikel', 'C', 'ATLM');

-- --------------------------------------------------------

--
-- Struktur dari tabel `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `formasi` varchar(50) NOT NULL,
  `score` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `soal`
--

CREATE TABLE `soal` (
  `id` int(10) UNSIGNED NOT NULL,
  `pertanyaan` text NOT NULL,
  `opsi_a` varchar(255) NOT NULL,
  `opsi_b` varchar(255) NOT NULL,
  `opsi_c` varchar(255) NOT NULL,
  `opsi_d` varchar(255) NOT NULL,
  `jawaban` enum('A','B','C','D') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `formasi` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `is_logged_in` tinyint(1) NOT NULL DEFAULT 0,
  `status_login` enum('belum','sudah') NOT NULL DEFAULT 'belum'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `username`, `fullname`, `password`, `formasi`, `role`, `is_logged_in`, `status_login`) VALUES
(1, '', 'admin', '', '$2y$10$v00TgP6MCM.YayVTFZohoOvBEvLnjrYulwZ8T4nOWA/YK/lsc.bA2', '', 'admin', 1, 'belum'),
(2, 'Administrator', 'admin', 'Admin', '$2y$10$0FFyfgn/Q33h1FqltvkDJeEAY5sXI5yj7yChF/KUukYZcsFKKm4My', 'admin', 'admin', 0, 'belum'),
(37, 'Arneulita Oktaviani', 'Ariavia', '', '$2y$10$XeEEEU43S0km9KzpBoxBFuqaN72VtBdAMyfO2FDFGPbyro47jiuV2', 'Perawat', 'user', 1, 'belum'),
(38, 'Ade', 'Ade gustandi', '', '$2y$10$F8lgCL8cWWHvmTNwVK2EQOOSSqxw5mGVddFeYIZHPJVhPz28hFFhm', 'Perawat', 'user', 1, 'belum'),
(39, 'Testing Uhuy', 'Uhuy', '', '$2y$10$zQlfgwJeSwiMr08TwWr6teR70nj/JCQCibELjExgdUr9r1mUHnzXy', 'Radiologi', 'user', 1, 'belum');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `hasil_ujian`
--
ALTER TABLE `hasil_ujian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indeks untuk tabel `jawaban_user`
--
ALTER TABLE `jawaban_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_soal_unique` (`user_id`,`soal_id`),
  ADD KEY `fk_jawaban_soal` (`soal_id`);

--
-- Indeks untuk tabel `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `soal`
--
ALTER TABLE `soal`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `hasil_ujian`
--
ALTER TABLE `hasil_ujian`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `jawaban_user`
--
ALTER TABLE `jawaban_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT untuk tabel `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `soal`
--
ALTER TABLE `soal`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `hasil_ujian`
--
ALTER TABLE `hasil_ujian`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jawaban_user`
--
ALTER TABLE `jawaban_user`
  ADD CONSTRAINT `fk_jawaban_soal` FOREIGN KEY (`soal_id`) REFERENCES `soal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jawaban_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
