<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['nama']) || !isset($_SESSION['level'])) {
    header("Location: index.php");
    exit;
}

$nama_session = $_SESSION['nama'];
$id = $_SESSION['id'];

$level = $_SESSION['level'];
$validasi = isset($_GET['validasi']) ? trim($_GET['validasi']) : "";

/* Ambil data user */
$query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id_pengguna = '$id'");
$data = mysqli_fetch_array($query);

/* Proses update */
if (isset($_POST['edit_profil'])) {
    $nama     = htmlspecialchars($_POST['nama']);
    $password = $_POST['password'];
    $pass_new = $_POST['pass_new'];

    if (!empty($password) && empty($pass_new)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($koneksi, "UPDATE pengguna SET nama='$nama' WHERE id_pengguna='$data[id_pengguna]'");
        $_SESSION['nama'] = $nama;
        header("Location: profil.php?validasi=sukses");
        exit;
    }
    else if (!empty($password) && !empty($pass_new)) {
        if (password_verify($password, $data['password'])) {
            // Hash password baru
            $password_hash = password_hash($pass_new, PASSWORD_DEFAULT);
            mysqli_query($koneksi, "UPDATE pengguna SET nama='$nama', password='$password_hash' WHERE id_pengguna='$data[id_pengguna]'");
            $_SESSION['nama'] = $nama;
            header("Location: profil.php?validasi=sukses");
            exit;
        } else {
            // Password lama salah
            header("Location: profil.php?validasi=error");
            exit;
        }
    }

}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Profil</title>
    <link rel="icon" type="image/x-icon" href="assets/img/logo_robustaku.png" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" />
</head>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<body>
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
                    <li class="nav-item"><a class="nav-link unactive" href="rekomendasi.php">Rekomendasi</a></li>
                    <?php if ($level == 'admin'): ?>
                    <li class="nav-item"><a class="nav-link unactive" href="kriteria.php">Kriteria</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="subkriteria.php">Subkriteria</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link unactive" href="varietas.php">Varietas</a></li>
                    <?php if ($level == 'admin'): ?>
                    <li class="nav-item"><a class="nav-link unactive" href="pengguna.php">Pengguna</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link active" href="profil.php">Profil</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="modal fade" id="keluar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Keluar dari Sistem</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin Keluar?</p>
                    </div>
                    <div class="modal-footer">
                        <a href="profil.php" class="btn-primary">Tidak</a>
                        <a href="keluar.php" class="btn-danger">Ya</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container page-wrap">  
    <div class="row w-100 justify-content-center gx-5 align-items-center">
            <!-- Left: login card -->
            <div class="col-lg-5">
                <div class="auth-card">
                    <h3 class="card-title">Profil Saya</h3>
                    <?php
                    if ($validasi == "sukses") {
                        echo "
                            <div class='alert alert-success alert-dismissible fade show mb-3' role='alert'>
                                Data Profil berhasil diperbarui!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>
                            ";
                    } else if ($validasi == "error") {
                        echo "
                            <div class='alert alert-danger alert-dismissible fade show mb-3' role='alert'>
                                Proses gagal!
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>
                            ";
                    }?>

        <form action="proses.php" method="post" class="mt-3">
            <div class="mb-3">
                <input type="hidden" name="id" value="<?= $id; ?>">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input name="nama" value="<?= isset($data['nama']) ? $data['nama'] : ''; ?>" class="form-control" id="nama" type="text" placeholder="Nama Lengkap" pattern="[A-Za-z]+( [A-Za-z]+)*" oninvalid="this.setCustomValidity('Inputan hanya berupa huruf')"  oninput="setCustomValidity('')" required autocomplete="off" />
            </div>
            <div class="mb-3">
                <input type="hidden" name="id" value="<?= $id; ?>">
                <label for="user" class="form-label">Username</label>
                <input name="user" value="<?= $data['username'] ?>" class="form-control" oninput="setCustomValidity('')" autocomplete="off" readonly />
            </div>
            <div class="mb-3">
                <input type="hidden" name="id" value="<?= $id; ?>">
                <label for="password" class="form-label">Sandi Lama</label>
                <input name="password" class="form-control" id="password" type="password" placeholder="Masukkan sandi lama" pattern="[^&#34;&#39;&#60;&#62;]+" minlength="5" autocomplete="off" />
            </div>
            <div class="mb-3">
                <input type="hidden" name="id" value="<?= $id; ?>">
                <label for="pass_new" class="form-label">Sandi Baru</label>
                <input name="pass_new" class="form-control" id="pass_new" type="password" placeholder="Buat sandi baru" pattern="[^&#34;&#39;&#60;&#62;]+" minlength="5" autocomplete="off" />
            </div>
            <div class="mb-3">
                <input type="hidden" name="id" value="<?= $id; ?>">
                <label for="level" class="form-label">Level</label>
                <input name="level" value="<?= isset($data['level']) ? $data['level'] : ''; ?>" class="form-control" oninput="setCustomValidity('')" autocomplete="off" readonly />
            </div>

            <div class="btn-wrap">
                <button type="submit" name="edit_profil" class="btn-primary">Simpan</button>
                <a href="#" class="btn-danger" data-bs-toggle="modal" data-bs-target="#keluar">Keluar</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>

