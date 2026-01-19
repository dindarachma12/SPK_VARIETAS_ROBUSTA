<?php
session_start();
include 'koneksi.php';
include 'base_url.php';
if (isset($_SESSION['nama']) && isset($_SESSION['level'])) {
    header("Location: homepage.php?validasi=sukses");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>RobustaKu</title>
    <link rel="icon" type="image/x-icon" href="assets/img/logo.png" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Poppins font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root{
            --robusta-brown: #7a4b2a;
            --robusta-brown-dark: #6b3f22;
            --text-dark: #2e2a28;
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
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            background: transparent;
            padding: 18px 0;
        }
        .brand-wrap {
            display:flex;
            align-items:center;
            gap:12px;
        }
        .brand-wrap img {
            width:200px;
            height:auto;
            display:block;
        }
        .brand-text {
            font-weight:700;
            color:#fff;
            letter-spacing:0.2px;
            font-size:1.05rem;
        }
        .btn-masuk {
            background: var(--robusta-brown);
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight:600;
        }
        .btn-masuk:hover { background: var(--robusta-brown-dark); color:#fff; }

        /* HERO / MASTHEAD */
        .masthead{ min-height:100vh;
            background-image: url('assets/img/coffeepattern.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
            display:flex;
            align-items:center;
            color:#fff;
        }
        .masthead::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, var(--overlay), rgba(0,0,0,0.6));
            z-index: 0;
        }
        .masthead .container { position: relative; z-index: 1; }

        /* Left column text */
        .hero-title {
            font-size: 2.6rem;
            font-weight: 700;
            line-height: 1.06;
            margin-bottom: 0.6rem;
            color: #080808;
        }
        .divider {
            width: 96px;
            height: 6px;
            background: var(--robusta-brown);
            border-radius: 6px;
            margin: 14px 0;
            border: none;
            box-shadow: 0 6px 14px rgba(122,75,42,0.18);
        }
        .hero-sub {
            color: #080808;
            font-size: 1.05rem;
            font-weight: 300;
            margin-bottom: 1.25rem;
            max-width: 640px;
        }
        .btn-cta {
            background: rgba(255,255,255,0.95);
            color: #3b2a20;
            border-radius: 8px;
            padding: 10px 18px;
            font-weight: 600;
            box-shadow: 0 8px 18px rgba(0,0,0,0.18);
            border: none;
        }
        .btn-cta:hover { opacity: 0.95; }

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
            display:block;
            transition: transform .45s ease;
        }
        .image-grid .img-wrap:hover img { transform: scale(1.03); }

        /* Responsive */
        @media (max-width: 991px) {
            .hero-title { font-size: 1.6rem; }
            .image-grid img { height: 120px; }
            .masthead { padding-top: 80px; }
        }
        @media (max-width: 576px) {
            .image-grid { grid-template-columns: repeat(2, 1fr); gap:10px; }
            .hero-title { font-size: 1.35rem; }
            .hero-sub { font-size: 0.98rem; }
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <div class="brand-wrap">
                <!-- Ganti logo.png sesuai aset Anda -->
                <img src="assets/img/logo.png" alt="RobustaKu Logo">
            </div>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navMenu">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a class="btn btn-masuk ms-2" href="masuk.php">Masuk</a>
                    </li>
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
                </div>

                <!-- RIGHT: grid gambar 2x2 -->
                <div class="col-lg-6">
                    <div class="image-grid">
                        <div class="img-wrap">
                            <!-- Ganti nama file sesuai aset: harvest.jpg -->
                            <img src="assets/img/Rectangle 1.png" alt="Panen kopi">
                        </div>
                        <div class="img-wrap">
                            <!-- Ganti nama file sesuai aset: inspect.jpg -->
                            <img src="assets/img/Rectangle 3.png" alt="Inspeksi tanaman">
                        </div>
                        <div class="img-wrap">
                            <!-- Ganti nama file sesuai aset: roasted.jpg -->
                            <img src="assets/img/Rectangle 4.png" alt="Biji panggang">
                        </div>
                        <div class="img-wrap">
                            <!-- Ganti nama file sesuai aset: cherries.jpg -->
                            <img src="assets/img/kopi 2.png" alt="Buah kopi">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
