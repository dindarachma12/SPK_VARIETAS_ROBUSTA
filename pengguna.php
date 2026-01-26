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

// Query untuk mengambil data pengguna
$query = mysqli_query($koneksi, "SELECT * FROM pengguna ORDER BY id_pengguna ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Pengguna</title>
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
                    <li class="nav-item"><a class="nav-link unactive" href="subkriteria.php">Subkriteria</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="varietas.php">Varietas</a></li>
                    <li class="nav-item"><a class="nav-link active" href="pengguna.php">Pengguna</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="profil.php">Profil</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container container-content">
        <h2>Data Pengguna</h2>

        <?php
        if ($validasi == "sukses-tambah") {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Data pengguna berhasil ditambahkan!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        } elseif ($validasi == "sukses-perbarui") {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Data pengguna berhasil diperbarui!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        } elseif ($validasi == "sukses-hapus") {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Data pengguna berhasil dihapus!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        } elseif ($validasi == "error") {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Proses gagal!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        } elseif ($validasi == "warning") {
            echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                    Username telah digunakan!
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

        <!-- Tabel Data Pengguna -->
        <div class="table-container">
            <table class="table table-hover mb-0" id="dataTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Level</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($data = mysqli_fetch_array($query)) {
                        echo "<tr>
                                <td>{$no}</td>
                                <td>{$data['nama']}</td>
                                <td>{$data['username']}</td>
                                <td>{$data['level']}</td>
                                <td>
                                    <button class='btn-edit' onclick='editData({$data['id_pengguna']}, \"{$data['nama']}\", \"{$data['username']}\", \"{$data['level']}\")'>
                                        <i class='fas fa-edit'></i>
                                    </button>
                                    <button class='btn-hapus' onclick='hapusData({$data['id_pengguna']}, \"{$data['nama']}\")'>
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
                    <h5 class="modal-title">Tambah Data Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="proses.php" method="post" class="mt-3">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="inputEmail" class="form-label">Nama Lengkap</label>
                            <input name="nama" class="form-control" id="inputEmail" type="text" placeholder="Masukkan nama lengkap pengguna" pattern="[A-Za-z]+( [A-Za-z]+)*" title="Inputan hanya berupa huruf" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="inputEmail" class="form-label">Username</label>
                            <input name="user" class="form-control" id="inputEmail" type="text" placeholder="Buat username pengguna" pattern="[A-Za-z0-9]+" title="Inputan hanya boleh huruf dan angka" minlength="5" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Level</label>
                            <select name="level" class="form-control" required>
                                <option value="">Pilih Level</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="inputPassword" class="form-label">Sandi</label>
                            <input name="pass" class="form-control" id="inputPassword" type="password" placeholder="Buat sandi" pattern="[^&#34;&#39;&#60;&#62;]+" minlength="5" required autocomplete="off" />
                        </div>

                        <div class="mb-3">
                            <label for="inputPassword" class="form-label">Konfirmasi Sandi</label>
                            <input name="konfir" class="form-control" id="inputPassword" type="password" placeholder="Konfirmasi sandi" pattern="[^&#34;&#39;&#60;&#62;]+" minlength="5" required autocomplete="off" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah_pengguna" class="btn btn-primary">Tambah</button>
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
                    <h5 class="modal-title">Ubah Data Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="proses.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" id="edit_nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="user" id="edit_user" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Level</label>
                            <select name="level" id="edit_level" class="form-control" autocomplete="on">
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="edit_pengguna" class="btn btn-primary">Perbarui</button>
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
                    <h5 class="modal-title">Hapus Data Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="hapus_pengguna.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="hapus_id">
                        <p>Apakah Anda yakin ingin menghapus pengguna <strong id="hapus_nama"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="hapus_pengguna" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editData(id, nama, username, level) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_user').value = username;
            document.getElementById('edit_level').value = level;
            new bootstrap.Modal(document.getElementById('modalEdit')).show();
        }

        function hapusData(id, nama) {
            document.getElementById('hapus_id').value = id;
            document.getElementById('hapus_nama').textContent = nama;
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