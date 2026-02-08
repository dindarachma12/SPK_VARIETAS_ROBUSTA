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

    <!-- HERO -->
    <header class="hero-section">
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