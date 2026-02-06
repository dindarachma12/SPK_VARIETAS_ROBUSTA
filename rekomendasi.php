<?php
session_start();
include "koneksi.php";

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
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Rekomendasi Varietas</title>
    <link rel="icon" type="image/x-icon" href="assets/img/logo_robustaku.png" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
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
            color: rgba(0,0,0,0.45);
            font-weight: 500;
            padding: 8px 12px;
            transition: all 0.3s ease;
        }
        .navbar .nav-link.active {
            color: var(--robusta-brown);
            font-weight: 600;
        }
        .navbar .nav-link:hover {
            color: var(--robusta-brown);
            font-weight: 600;
        }

        /* HERO */
        .masthead {
            min-height: calc(100vh - 72px);
            display: flex;
            align-items: center;
            position: relative;
            padding: 60px 0;
        }
        .masthead .container { 
            position: relative; 
            z-index: 1; 
            max-width: 1140px; 
        }

        /* Left column text */
        .hero-title {
            font-size: 2.4rem;
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }
        .divider {
            width: 88px;
            height: 6px;
            background: var(--robusta-brown);
            border-radius: 6px;
            margin: 14px 0 20px 0;
            border: none;
        }
        .hero-sub {
            color: rgba(46,42,40,0.85);
            font-size: 1.05rem;
            font-weight: 300;
            margin-bottom: 1.25rem;
            max-width: 640px;
            line-height: 1.6;
        }

        /* Right column - Form card */
        .form-card {
            background: #fff;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.06);
        }

        .form-card .form-label {
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-card .form-control {
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-card .form-control:focus {
            border-color: var(--robusta-brown);
            box-shadow: 0 0 0 0.2rem rgba(122, 75, 42, 0.15);
        }

        .form-card .form-control::placeholder {
            color: #999;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--robusta-brown);
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-primary:hover { 
            background: var(--robusta-brown-dark); 
            color:#fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(122, 75, 42, 0.3);
        }

        /* Responsive tweaks */
        @media (max-width: 991px) {
            .hero-title { font-size: 2rem; }
            .masthead { padding: 40px 0; }
            .form-card { margin-top: 30px; }
        }
        @media (max-width: 767px) {
            .masthead { padding: 30px 0; }
            .hero-title { font-size: 1.6rem; }
            .form-card { padding: 24px; }
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
                    <li class="nav-item"><a class="nav-link unactive" href="homepage.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="rekomendasi.php">Rekomendasi</a></li>
                    <?php if ($level == 'admin'): ?>
                    <li class="nav-item"><a class="nav-link unactive" href="kriteria.php">Kriteria</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="subkriteria.php">Subkriteria</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="varietas.php">Varietas</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="pengguna.php">Pengguna</a></li>
                    <?php endif; ?>
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
                    <h1 class="hero-title">Varietas yang sesuai lahan Anda</h1>
                    <hr class="divider" />
                    <p class="hero-sub">Masukkan kondisi lingkungan lahan budidaya kopi untuk mendapatkan rekomendasi varietas kopi robusta yang paling sesuai</p>
                </div>

                <!-- RIGHT: Form rekomendasi -->
                <div class="col-lg-6">
                    <div class="form-card">
                        <?php
                        if ($validasi == "sukses") {
                            echo "
                            <div class='alert alert-success alert-dismissible fade show mb-3' role='alert'>
                                Rekomendasi berhasil didapatkan!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>
                            ";
                        } else if ($validasi == "error") {
                            echo "
                            <div class='alert alert-danger alert-dismissible fade show mb-3' role='alert'>
                                Gagal mendapatkan rekomendasi, silakan coba lagi!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>
                            ";
                        }
                        ?>

                        <form action="proses_rekomendasi.php" method="post">
                            <div class="modal-body">
                            <?php
                                $query = mysqli_query($koneksi, "SELECT * FROM kriteria");
                                while ($baris = mysqli_fetch_array($query)) {
                                    $id_kriteria = $baris['id_kriteria'];
                            ?>  
                            <div class="mb-3">
                            <label class="form-label"><?= $baris['nama_kriteria']; ?></label>
                            <input type="hidden" name="kriteria[]" value="<?= $id_kriteria; ?>">
                            <select name="subkriteria[<?= $id_kriteria ?>]" class="form-control" required>
                                <option value="">Pilih <?= $baris['nama_kriteria']; ?></option>
                                <?php
                                $select = mysqli_query($koneksi, "SELECT * FROM subkriteria WHERE id_kriteria = '$id_kriteria'");
                                while ($option = mysqli_fetch_array($select)) {
                                    echo "
                                    <option value='" . $option['id_subkriteria'] . "'>" . $option['nama_subkriteria'] . "</option>
                                    ";
                                }
                                ?>
                            </select>
                        </div>
                        <?php
                            }
                        ?>
                        </div>
                    <div class="d-grid">
                            <button name="masuk" class="btn btn-primary">Lihat Rekomendasi</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>