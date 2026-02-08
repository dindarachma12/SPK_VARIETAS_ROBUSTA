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

// Query untuk mengambil data kriteria
$query = mysqli_query($koneksi, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Kriteria</title>
    <link rel="icon" type="image/x-icon" href="assets/img/logo_robustaku.png" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="css/style.css" rel="stylesheet" />
</head>

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
                    <li class="nav-item"><a class="nav-link active" href="kriteria.php">Kriteria</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="subkriteria.php">Subkriteria</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="varietas.php">Varietas</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="pengguna.php">Pengguna</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="profil.php">Profil</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container container-content">
        <h2>Data Kriteria Lingkungan Kopi Robusta</h2>

        <?php
        if ($validasi == "sukses-tambah") {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Data kriteria berhasil ditambahkan!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        } elseif ($validasi == "sukses-perbarui") {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Data kriteria berhasil diperbarui!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        } elseif ($validasi == "sukses-hapus") {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Data kriteria berhasil dihapus!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        } elseif ($validasi == "error") {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Proses gagal!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        } elseif ($validasi == "warning") {
            echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                    Kode kriteria telah digunakan!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        }
        ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah Data
            </button>
            <div class="search-box">
                <input type="text" id="searchInput" class="form-control" placeholder="Masukkan pencarian">
                <i class="fas fa-search"></i>
            </div>
        </div>

        <!-- Tabel Data Kriteria -->
        <div class="table-container">
            <table class="table table-hover mb-0" id="dataTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Kriteria</th>
                        <th>Nama Kriteria</th>
                        <th>Jenis</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($data = mysqli_fetch_array($query)) {
                        echo "<tr>
                                <td>{$no}</td>
                                <td>{$data['kode_kriteria']}</td>
                                <td>{$data['nama_kriteria']}</td>
                                <td>{$data['jenis_kriteria']}</td>
                                <td>
                                    <button class='btn-edit' onclick='editData({$data['id_kriteria']}, \"{$data['kode_kriteria']}\", \"{$data['nama_kriteria']}\", \"{$data['jenis_kriteria']}\")'>
                                        <i class='fas fa-edit'></i>
                                    </button>
                                    <button class='btn-hapus' onclick='hapusData({$data['id_kriteria']}, \"{$data['kode_kriteria']}\")'>
                                        <i class='fas fa-trash'></i>
                                    </button>
                                </td>
                              </tr>";
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="proses.php" method="post" class="mt-3">
                    <div class="modal-body">
                        <div class="mb-3">  
                            <label for="inputEmail" class="form-label">Kode Kriteria</label>
                            <input name="kode_kriteria" class="form-control" id="inputEmail" type="text" placeholder="Masukkan kode kriteria" pattern="[A-Za-z0-9]+" title="Inputan hanya berupa huruf dan angka" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="inputEmail" class="form-label">Nama Kriteria</label>
                            <input name="nama_kriteria" class="form-control" id="inputEmail" type="text" placeholder="Masukkan nama kriteria" pattern="[A-Za-z]+( [A-Za-z]+)*" title="Inputan hanya berupa huruf" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Kriteria</label>
                            <select name="jenis_kriteria" class="form-control" required>
                                <option value="">Pilih Jenis Kriteria</option>
                                <option value="benefit">Benefit</option>
                                <option value="cost">Cost</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah_kriteria" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal Edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Data Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="proses.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id_kriteria" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Kode Kriteria</label>
                            <input type="text" name="kode_kriteria" id="edit_kode_kriteria" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Kriteria</label>
                            <input type="text" name="nama_kriteria" id="edit_nama_kriteria" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Kriteria</label>
                            <select name="jenis_kriteria" id="edit_jenis_kriteria" class="form-control" autocomplete="on">
                                <option value="">Pilih Jenis Kriteria</option>
                                <option value="benefit">Benefit</option>
                                <option value="cost">Cost</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="edit_kriteria" class="btn btn-primary">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div class="modal fade" id="modalHapus" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="hapus_kriteria.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id_kriteria" id="hapus_id">
                        <p>Apakah Anda yakin ingin menghapus kriteria <strong id="hapus_kode_kriteria"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="hapus_kriteria" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <body class="page-kriteria">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/function.js"></script>
    </body>

</body>
</html>