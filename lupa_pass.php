<?php
session_start();
if (isset($_SESSION['nama']) && isset($_SESSION['level'])) {
    header("Location: homepage.php?validasi=sukses");
    exit;
}
$validasi = isset($_GET['validasi']) ? trim($_GET['validasi']) : "";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Lengkapi Informasi</title>
    <link rel="icon" type="image/x-icon" href="assets/img/logo_robustaku.png" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <div class="brand-wrap">
                <!-- Ganti logo.png sesuai aset Anda -->
                <img src="assets/img/logo_robustaku.png" alt="RobustaKu Logo">
            </div>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>  
    <div class="container page-wrap">  
    <div class="row w-100 justify-content-center gx-5 align-items-center">
            <!-- Left: login card -->
            <div class="col-lg-5">
                <div class="auth-card">
                    <h3 class="card-title">Lengkapi Informasi</h3>
                    <?php
                    if ($validasi == "error") {
                        echo "
                        <div class='alert alert-danger alert-dismissible fade show mb-3' role='alert'>
                            Nama dan username tidak valid!
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                        ";
                    }
                    ?>

                    <form action="proses.php" method="post" class="mt-3">
                        <div class="mb-3">
                            <label for="inputEmail" class="form-label">Nama Lengkap</label>
                            <input name="nama" class="form-control" id="inputEmail" type="text" placeholder="Masukkan nama lengkap Anda" pattern="[A-Za-z]+( [A-Za-z]+)*" title="Inputan hanya boleh huruf" required autocomplete="off" />
                        </div>
                        <div class="mb-3">
                            <label for="inputEmail" class="form-label">Username</label>
                            <input name="user" class="form-control" id="inputEmail" type="text" placeholder="Buat username Anda" pattern="[A-Za-z0-9]+" title="Inputan hanya boleh simbol" minlength="5" required autocomplete="off" />
                        </div>
                        <div class="d-grid">
                            <button name="lupa_pass" class="btn btn-primary">Lanjut</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (for alert close and responsive) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script type="text/javascript">
        (function() {
            var view = document.getElementById('view');
            var pass = document.getElementById('pass');
            if (view && pass) {
                view.addEventListener('click', function() {
                    pass.type = view.checked ? "text" : "password";
                });
            }
        })();
    </script>
</body>

</html>