<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['nama']) || !isset($_SESSION['level'])) {
    header("Location: index.php");
    exit;
}

$nama_session = $_SESSION['nama'];
$level = $_SESSION['level'];

$validasi = isset($_GET['validasi']) ? trim($_GET['validasi']) : "";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Subkriteria</title>
    <link rel="icon" type="image/x-icon" href="assets/img/logo.png" />
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

        h4 {
            font-weight: 600;
            color: var(--text-dark);
            margin-top: 30px;
            margin-bottom: 15px;
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

        .table-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 30px;
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
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <div class="brand-wrap">
                <a href="homepage.php"><img src="assets/img/logo.png" alt="RobustaKu Logo"></a>
            </div>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navMenu">
                <ul class="navbar-nav align-items-center me-3">
                    <li class="nav-item"><a class="nav-link unactive" href="homepage.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="rekomendasi.php">Rekomendasi</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="kriteria.php">Kriteria</a></li>
                    <li class="nav-item"><a class="nav-link active" href="subkriteria.php">Subkriteria</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="varietas.php">Varietas</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="pengguna.php">Pengguna</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="profil.php">Profil</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container container-content">
        <h2>Data Subkriteria Lingkungan Kopi Robusta</h2>

        <?php
        if ($validasi == "sukses-tambah") {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Data subkriteria berhasil ditambahkan!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        } elseif ($validasi == "sukses-perbarui") {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Data subkriteria berhasil diperbarui!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        } elseif ($validasi == "sukses-hapus") {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Data subkriteria berhasil dihapus!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        } elseif ($validasi == "error") {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Proses gagal!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        }
        ?>

        <?php
        // Loop setiap kriteria
        $query_kriteria = mysqli_query($koneksi, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");
        while ($kriteria = mysqli_fetch_array($query_kriteria)) {
            $id_kriteria = $kriteria['id_kriteria'];
        ?>

        <!-- Judul Kriteria -->
        <h4><?= $kriteria['nama_kriteria'] ?></h4>
        
        <!-- Tombol Tambah Data -->
        <button class="btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah<?= $id_kriteria ?>">
            <i class="fas fa-plus"></i> Tambah Data
        </button>

        <!-- Tabel Data Subkriteria per Kriteria -->
        <div class="table-container">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Subkriteria</th>
                        <th>Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query_sub = mysqli_query($koneksi, "SELECT * FROM subkriteria WHERE id_kriteria = '$id_kriteria' ORDER BY id_subkriteria ASC");
                    
                    if (mysqli_num_rows($query_sub) > 0) {
                        while ($sub = mysqli_fetch_array($query_sub)) {
                            echo "<tr>
                                    <td>{$no}</td>
                                    <td>{$sub['nama_subkriteria']}</td>
                                    <td>{$sub['nilai_subkriteria']}</td>
                                    <td>
                                        <button class='btn-edit' onclick='editData({$sub['id_subkriteria']}, \"{$sub['nama_subkriteria']}\", \"{$sub['nilai_subkriteria']}\")'>
                                            <i class='fas fa-edit'></i>
                                        </button>
                                        <button class='btn-hapus' onclick='hapusData({$sub['id_subkriteria']}, \"{$sub['nama_subkriteria']}\")'>
                                            <i class='fas fa-trash'></i>
                                        </button>
                                    </td>
                                  </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>Belum ada data subkriteria</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Modal Tambah per Kriteria -->
        <div class="modal fade" id="modalTambah<?= $id_kriteria ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Subkriteria - <?= $kriteria['nama_kriteria'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="proses.php" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="id_kriteria" value="<?= $id_kriteria ?>">
                            <div class="mb-3">
                                <label class="form-label">Nama Subkriteria</label>
                                <input name="nama_subkriteria" class="form-control" type="text" placeholder="Masukkan nama subkriteria" required autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nilai Subkriteria</label>
                                <input name="nilai_subkriteria" class="form-control" type="number" step="0.01" placeholder="Masukkan nilai subkriteria" required autocomplete="off">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" name="tambah_subkriteria" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php } ?>

    </div>

    <!-- Modal Edit (Global) -->
    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Data Subkriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="proses.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id_subkriteria" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Nama Subkriteria</label>
                            <input type="text" name="nama_subkriteria" id="edit_nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nilai Subkriteria</label>
                            <input type="number" step="0.01" name="nilai_subkriteria" id="edit_nilai" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="edit_subkriteria" class="btn btn-primary">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus (Global) -->
    <div class="modal fade" id="modalHapus" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data Subkriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="hapus_subkriteria.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id_subkriteria" id="hapus_id">
                        <p>Apakah Anda yakin ingin menghapus subkriteria <strong id="hapus_nama"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="hapus_subkriteria" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editData(id, nama, nilai) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_nilai').value = nilai;
            new bootstrap.Modal(document.getElementById('modalEdit')).show();
        }

        function hapusData(id, nama) {
            document.getElementById('hapus_id').value = id;
            document.getElementById('hapus_nama').textContent = nama;
            new bootstrap.Modal(document.getElementById('modalHapus')).show();
        }
    </script>
</body>
</html>