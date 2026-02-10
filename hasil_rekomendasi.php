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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

            <?php if ($level == 'admin'): ?>
            <div class="mt-4 text-center">
                <a href="detail_rekomendasi.php" class="btn btn-primary">
                    Lihat Detail Perhitungan
                </a>
            </div>
            <?php endif; ?>

        </div>
    </div>

    
