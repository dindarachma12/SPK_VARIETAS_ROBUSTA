<?php
session_start();
if (!isset($_SESSION['nama']) && !isset($_SESSION['level'])) {
    header("Location: index.php");
    exit;
}
$level = $_SESSION['level'];
$validasi = isset($_GET['validasi']) ? trim($_GET['validasi']) : "";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Homepage</title>
    <link rel="icon" type="image/x-icon" href="assets/img/logo_robustaku.png" />
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Poppins font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" />
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
    <header class="hero-section">
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
