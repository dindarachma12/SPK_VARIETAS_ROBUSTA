<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['nama']) && !isset($_SESSION['level'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['validasi'])) {
    header("Location: 404.php");
    exit;
} else {
    $validasi = $_GET['validasi'];
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
    <title>Hasil Rekomendasi</title>
    <link rel="icon" type="image/x-icon" href="assets/img/logo_robustaku.png" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

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

        .container-content {
            padding: 40px 0;
        }

        h2 {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 30px;
        }

        .btn-primary {
            background: var(--robusta-brown);
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
        }
        .btn-primary:hover {
            background: var(--robusta-brown-dark);
        }

        .btn-secondary {
            background: transparent;
            border: 1px solid var(--robusta-brown);
            color: var(--robusta-brown);
            padding: 10px 22px;
            border-radius: 6px;
            font-weight: 500;
            text-decoration: none;
        }
        .btn-secondary:hover {
            background: var(--robusta-brown-dark);
        }

        .search-box {
            position: relative;
            max-width: 300px;
        }

        .search-box input {
            border-radius: 8px;
            border: 1px solid rgba(46,42,40,0.15);
            padding: 10px 40px 10px 16px;
            width: 100%;
        }

        .search-box i {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(46,42,40,0.5);
        }

        .table-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-top: 20px;
        }

        table {
            margin: 0;
        }

        thead {
            background: #11454E;
            color: #fff;
        }

        thead th {
            font-weight: 500;
            padding: 16px;
            border: none;
            text-align: center;
        }

        tbody td {
            padding: 14px 16px;
            vertical-align: center;
            text-align: center;
        }

        tbody tr {
            padding: 14px 16px;
            vertical-align: center;
        }

        tbody tr:hover {
            background-color: rgba(122,75,42,0.03);
        }

        .btn-edit {
            background: transparent;
            border: 1px solid #11454E;
            color: #11454E;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-edit:hover {
            background: var(--robusta-brown);
            color: #fff;
        }

        .btn-hapus {
            background: transparent;
            border: 1px solid #dc3545;
            color: #dc3545;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-hapus:hover {
            background: #dc3545;
            color: #fff;
        }

        .modal-content {
            border-radius: 12px;
        }

        .modal-header {
            background: #11454E;
            color: #fff;
            border-radius: 12px 12px 0 0;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .btn-danger {
            background: red;
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
        }

        .btn-danger:hover {
            background: darkred;
            color: #fff;
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

    <div class="container container-content">
        <h2>Hasil Rekomendasi</h2>

        <?php
        if ($validasi == "sukses-tambah") {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                   Rekomendasi berhasil didapatkan!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        } elseif ($validasi == "warning") {
            echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                    Gagal mendapatkan rekomendasi, silakan coba lagi!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        }
        ?>

        <?php
        $nama = $_SESSION['username'];
        $query = mysqli_query($koneksi, "SELECT varietas.nama_varietas AS nama_varietas FROM peringkat JOIN varietas ON peringkat.id_varietas = varietas.id_varietas WHERE peringkat.username = '$nama' ORDER BY peringkat.nilai_peringkat DESC LIMIT 1");
        $data_rekomendasi = mysqli_fetch_array($query);
        ?>

        <!-- Tabel Hasil Rekomendasi -->
        <p class="description-text">
            Berdasarkan hasil perhitungan Sistem Pendukung Keputusan dengan menggunakan metode TOPSIS dan CRITIC, didapatkan bahwa "<?php echo $data_rekomendasi['nama_varietas']; ?>" adalah varietas kopi robusta yang paling direkomendasikan untuk lahan Anda
        </p>
        <?php
        $query = mysqli_query($koneksi, "SELECT peringkat.*, varietas.kode_varietas, varietas.nama_varietas FROM peringkat JOIN varietas ON peringkat.id_varietas = varietas.id_varietas WHERE peringkat.username = '$nama' ORDER BY peringkat.nilai_peringkat DESC");
        ?>
        <div class="table-container">
            <table class="table table-hover mb-0" id="dataTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Varietas</th>
                        <th>Nama Varietas</th>
                        <th>Nilai Akhir</th>
                        <th>Peringkat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($data = mysqli_fetch_array($query)) {
                        echo "<tr>
                                <td>{$no}</td>
                                <td>{$data['kode_varietas']}</td>
                                <td>{$data['nama_varietas']}</td>
                                <td>" . number_format($data['nilai_peringkat'], 3, ',', '.') . "</td>
                                <td>{$no}</td>
                              </tr>";
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    
