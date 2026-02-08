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

$query = mysqli_query($koneksi, "SELECT * FROM varietas ORDER BY id_varietas ASC");
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Varietas</title>
    <link rel="icon" type="image/x-icon" href="assets/img/logo_robustaku.png" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
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
                    <?php if ($level == 'admin'): ?>
                    <li class="nav-item"><a class="nav-link unactive" href="kriteria.php">Kriteria</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="subkriteria.php">Subkriteria</a></li>
                    <li class="nav-item"><a class="nav-link active" href="varietas.php">Varietas</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="pengguna.php">Pengguna</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link unactive" href="profil.php">Profil</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container container-content">
        <h2>Varietas Tanaman Kopi Robusta</h2>

        <?php
        if ($validasi == "sukses-tambah") {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Varietas berhasil ditambahkan!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        } elseif ($validasi == "sukses-perbarui") {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Varietas berhasil diperbarui!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        } elseif ($validasi == "sukses-hapus") {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Varietas berhasil dihapus!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        } elseif ($validasi == "error") {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Proses gagal!
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

        <!-- Tabel Data Varietas -->
        <div class="table-container">
            <table class="table table-hover mb-0" id="dataTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Varietas</th>
                        <th>Nama Varietas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($data = mysqli_fetch_array($query)) {
                        // Ambil data subkriteria untuk varietas ini dari tabel matriks
                        $id_var = $data['id_varietas'];
                        $query_sub_edit = mysqli_query($koneksi, "SELECT id_subkriteria FROM matriks WHERE id_varietas = '$id_var' ORDER BY id_kriteria ASC");
                        $sub_array = [];
                        while ($sub_row = mysqli_fetch_array($query_sub_edit)) {
                            $sub_array[] = $sub_row['id_subkriteria'];
                        }
                        $sub_json = json_encode($sub_array);
                        
                        echo "<tr>
                                <td>{$no}</td>
                                <td>{$data['kode_varietas']}</td>
                                <td>{$data['nama_varietas']}</td>
                                <td>
                                    <button class='btn-view' onclick='viewData({$data['id_varietas']}, \"{$data['kode_varietas']}\", \"{$data['nama_varietas']}\", {$sub_json})'>
                                        <i class='fas fa-eye'></i>
                                    </button>
                                    <button class='btn-edit' onclick='editData({$data['id_varietas']}, \"{$data['kode_varietas']}\", \"{$data['nama_varietas']}\", {$sub_json})'>
                                        <i class='fas fa-edit'></i>
                                    </button>
                                    <button class='btn-hapus' onclick='hapusData({$data['id_varietas']}, \"{$data['kode_varietas']}\")'>
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
                    <h5 class="modal-title">Tambah Varietas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="proses.php" method="post" class="mt-3">
                    <div class="modal-body">
                        <div class="mb-3">  
                            <label for="inputEmail" class="form-label">Kode Varietas</label>
                            <input name="kode_varietas" class="form-control" id="inputEmail" type="text" placeholder="Masukkan kode varietas" pattern="[A-Za-z0-9]+" title="Inputan hanya berupa huruf dan angka" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="inputEmail" class="form-label">Nama Varietas</label>
                            <input name="nama_varietas" class="form-control" id="inputEmail" type="text" placeholder="Masukkan nama varietas" pattern="[A-Za-z0-9 ]+" title="Inputan hanya berupa huruf dan angka" required autocomplete="off">
                        </div>
                        <?php
                            $query = mysqli_query($koneksi, "SELECT * FROM kriteria");
                            while ($baris = mysqli_fetch_array($query)) {
                                $id_kriteria = $baris['id_kriteria'];
                        ?>
                        <div class="mb-3">
                            <label class="form-label"><?= $baris['nama_kriteria']; ?></label>
                            <input type="hidden" name="kriteria[]" value="<?= $id_kriteria; ?>">
                            <select name="subkriteria[]" class="form-control" required>
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah_varietas" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal View -->
    <div class="modal fade" id="modalView" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Data Varietas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="proses.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id_varietas" id="view_id">
                        <div class="mb-3">
                            <label class="form-label">Kode Varietas</label>
                            <input type="text" name="kode_varietas" id="view_kode_varietas" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Varietas</label>
                            <input type="text" name="nama_varietas" id="view_nama_varietas" class="form-control" readonly>
                        </div>
                        <?php
                        $query_sub = mysqli_query($koneksi, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");
                        $idx = 0;
                        while ($baris_sub = mysqli_fetch_array($query_sub)) {
                        ?>
                            <div class="mb-3">
                                <label class="form-label"><?= $baris_sub['nama_kriteria']; ?></label>
                                <input type="text" class="form-control" id="view_subkriteria_<?= $idx ?>" readonly>
                            </div>
                        <?php
                        $idx++;
                        }
                        ?>
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
                    <h5 class="modal-title">Ubah Data Varietas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="proses.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id_varietas" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Kode Varietas</label>
                            <input type="text" name="kode_varietas" id="edit_kode_varietas" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Varietas</label>
                            <input type="text" name="nama_varietas" id="edit_nama_varietas" class="form-control" required>
                        </div>
                        <?php
                        $query_sub = mysqli_query($koneksi, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");
                        $idx = 0;
                        while ($baris_sub = mysqli_fetch_array($query_sub)) {
                            $id_kriteria = $baris_sub['id_kriteria'];
                        ?>
                            <div class="mb-3">
                                <label class="form-label"><?= $baris_sub['nama_kriteria']; ?></label>
                                <input type="hidden" name="kriteria[]" value="<?= $id_kriteria; ?>">
                                <select name="subkriteria[]" class="form-control edit-sub" id="edit_subkriteria_<?= $id_kriteria ?>" autocomplete="on">
                                    <option value="">Pilih <?= $baris_sub['nama_kriteria']; ?></option>
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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" name="edit_varietas" class="btn btn-primary">Perbarui</button>
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
                        <h5 class="modal-title">Hapus Varietas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="hapus_varietas.php" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="id_varietas" id="hapus_id">
                            <p>Apakah Anda yakin ingin menghapus varietas <strong id="hapus_kode_varietas"></strong>?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" name="hapus_varietas" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <body class="page-varietas">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/function.js"></script>
    </body>
    
</body>
</html>




        