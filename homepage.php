<?php
session_start();
if (!isset($_SESSION['nama']) && !isset($_SESSION['level'])) {
    header("Location: index.php");
    exit;
}
$validasi = isset($_GET['validasi']) ? trim($_GET['validasi']) : "";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>RobustaKu - Home</title>
    <link rel="icon" type="image/x-icon" href="assets/img/logo_robustaku.png" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Poppins font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --robusta-brown: #7a4b2a;
            --robusta-brown-dark: #6b3f22;
            --text-dark: #2e2a28;
            --overlay: rgba(0,0,0,0.45);
        }

        html, body {
            height: 100%;
            margin: 0;
            font-family: "Poppins", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            color: var(--text-dark);
            background-color: #ffffff;
            background-image: url('assets/img/coffeepattern.png');
            background-repeat: repeat;
            background-position: center;
            background-size: 100%;
        }


        /* NAVBAR */
        .navbar-custom {
            background: #fff;
            padding: 14px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .navbar .container {
            max-width: 1140px;
        }
        .brand-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand-wrap img {
            width: 140px;
            height: auto;
            display: block;
        }
        .nav-link {
            color: var(--robusta-brown);
            font-weight: 600;
            padding: 8px 12px;
        }
        .navbar .nav-link.active:hover {
            color: var(--robusta-brown);
            font-weight: 600;
        }
        .navbar .nav-link.unactive {
            color: rgba(0,0,0,0.45);
            font-weight: 500;
            padding: 8px 12px;
        }
        .navbar .nav-link.unactive:hover {
            color: var(--robusta-brown);
            font-weight: 600;
        }

        /* HERO */
        .masthead {
            min-height: calc(100vh - 72px);
            display: flex;
            align-items: center;
            position: relative;
            padding: 80px 0;
        }
        .masthead .container { position: relative; z-index: 1; max-width: 1140px; }

        /* Left column text */
        .hero-title {
            font-size: 2.4rem;
            font-weight: 600;
            line-height: 1.06;
            margin-bottom: 0.6rem;
            color: var(--text-dark);
        }
        .divider {
            width: 88px;
            height: 6px;
            background: var(--robusta-brown);
            border-radius: 6px;
            margin: 14px 0;
            border: none;
        }
        .hero-sub {
            color: rgba(46,42,40,0.85);
            font-size: 1.05rem;
            font-weight: 300;
            margin-bottom: 1.25rem;
            max-width: 640px;
        }

        .btn-primary {
            background: var(--robusta-brown);
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight:500;
        }
        .btn-primary:hover { background: var(--robusta-brown-dark); color:#fff; }

        /* Right column image grid */
        .image-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }
        .image-grid .img-wrap {
            overflow: hidden;
            border-radius: 12px;
            border: 1px solid rgba(46,42,40,0.04);
            background: #fff;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }
        .image-grid img {
            width: 100%;
            height: 170px;
            object-fit: cover;
            display: block;
            transition: transform .45s ease;
        }
        .image-grid .img-wrap:hover img { transform: scale(1.03); }

        /* Footer small spacing under hero */
        .hero-bottom-space { height: 40px; }

        /* Responsive tweaks */
        @media (max-width: 991px) {
            .hero-title { font-size: 1.8rem; }
            .image-grid img { height: 120px; }
            .masthead { padding: 60px 0; }
        }
        @media (max-width: 767px) {
            .masthead { padding: 40px 0; }
            .image-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
            .image-grid img { height: 110px; }
            .hero-title { font-size: 1.4rem; }
        }
    </style>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <div class="brand-wrap">
                <a href="homepage.php"><img src="assets/img/logo_robustaku.png" alt="RobustaKu Logo"></a>
            </div>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navMenu">
                <ul class="navbar-nav align-items-center me-3">
                    <li class="nav-item"><a class="nav-link active" href="homepage.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="rekomendasi.php">Rekomendasi</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="kriteria.php">Kriteria</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="subkriteria.php">Subkriteria</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="varietas.php">Varietas</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="pengguna.php">Pengguna</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="profil.php">Profil</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <header class="masthead">
        <div class="container">
            <div class="row gx-4 gx-lg-5 align-items-center">
                <!-- LEFT: teks -->
                <div class="col-lg-6">
                    <h1 class="hero-title">Temukan Varietas Robusta Sesuai dengan Lahan Anda!</h1>
                    <hr class="divider" />
                    <p class="hero-sub">Sistem Pendukung Keputusan untuk membantu petani Jember menentukan varietas kopi robusta yang paling sesuai dengan kondisi lingkungan lahan</p>
                    <div class="mt-3">
                        <a href="rekomendasi.php" class="btn btn-primary">Lihat Rekomendasi</a>
                    </div>
                </div>

                <!-- RIGHT: grid gambar 2x2 -->
                <div class="col-lg-6">
                    <div class="image-grid">
                        <div class="img-wrap">
                            <img src="assets/img/Rectangle 1.png" alt="Panen kopi">
                        </div>
                        <div class="img-wrap">
                            <img src="assets/img/Rectangle 3.png" alt="Inspeksi tanaman">
                        </div>
                        <div class="img-wrap">
                            <img src="assets/img/Rectangle 4.png" alt="Biji panggang">
                        </div>
                        <div class="img-wrap">
                            <img src="assets/img/kopi 2.png" alt="Buah kopi">
                        </div>
                    </div>
                </div>
            </div>

            <div class="hero-bottom-space"></div>
        </div>
    </header>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
