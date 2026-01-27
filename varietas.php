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

        .btn-view {
            background: transparent;
            border: 1px solid #11454E;
            color: #11454E;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-view:hover {
            background: var(--robusta-brown);
            color: #fff;
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

    </style>
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
                    <li class="nav-item"><a class="nav-link unactive" href="kriteria.php">Kriteria</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="subkriteria.php">Subkriteria</a></li>
                    <li class="nav-item"><a class="nav-link active" href="varietas.php">Varietas</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="pengguna.php">Pengguna</a></li>
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

    
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function viewData(id, kode, nama, subkriteriaArray) {
                document.getElementById('view_id').value = id; 
                document.getElementById('view_kode_varietas').value = kode;
                document.getElementById('view_nama_varietas').value = nama;

                const inputs = document.querySelectorAll('#modalView input[id^="view_subkriteria_"]');
                inputs.forEach(input => input.value = '');

                if (subkriteriaArray && subkriteriaArray.length > 0) {
                    subkriteriaArray.forEach((subId, index) => {
                        const input = document.getElementById('view_subkriteria_' + index);

                        // ambil nama subkriteria dari select edit (sudah ada semua option)
                        const select = document.querySelectorAll('#modalEdit select[name="subkriteria[]"]')[index];
                        if (select) {
                            const option = select.querySelector(`option[value="${subId}"]`);
                            if (input && option) {
                                input.value = option.textContent;
                            }
                        }
                    });
                }

                new bootstrap.Modal(document.getElementById('modalView')).show();
            }


            function editData(id, kode, nama, subkriteriaArray) {
                document.getElementById('edit_id').value = id; 
                document.getElementById('edit_kode_varietas').value = kode;
                document.getElementById('edit_nama_varietas').value = nama;
                let selects = document.querySelectorAll('.edit-sub')
                selects.forEach(s => s.value = "")
                subkriteriaArray.forEach((s, i) => { if (selects[i]) selects[i].value = s })


                new bootstrap.Modal(document.getElementById('modalEdit')).show();
            }

            function hapusData(id, kode) {
                document.getElementById('hapus_id').value = id; 
                document.getElementById('hapus_kode_varietas').textContent = kode;

                new bootstrap.Modal(document.getElementById('modalHapus')).show();
            }

            // Search functionality
            document.getElementById('searchInput').addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('#dataTable tbody tr');
                
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchValue) ? '' : 'none';
                });
            });
        </script>
    </body>
    </html>




        