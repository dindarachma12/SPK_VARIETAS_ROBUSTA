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
    <title>Lupa Sandi</title>
    <link rel="icon" type="image/x-icon" href="assets/img/logo_robustaku.png" />
    <!-- Poppins font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap (only for grid and basic components) -->
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
                    <h3 class="card-title">Lupa Sandi</h3>
                    <?php
                    if ($validasi == "error") {
                        echo "
                        <div class='alert alert-danger alert-dismissible fade show mb-3' role='alert'>
                            Konfirmasi sandi salah!
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                        ";
                    }
                    ?>

                    <form action="proses.php" method="post" class="mt-3">
                        <div class="mb-3">
                            <label for="inputPassword" class="form-label">Sandi Baru</label>
                            <input name="pass" class="form-control" id="inputPassword" type="password" placeholder="Buat sandi baru" pattern="[^&#34;&#39;&#60;&#62;]+" minlength="5" required autocomplete="off" />
                        </div>
                        <div class="mb-3">
                            <label for="inputPassword" class="form-label">Konfirmasi Sandi</label>
                            <input name="konfir" class="form-control" id="inputPassword" type="password" placeholder="Konfirmasi sandi baru" pattern="[^&#34;&#39;&#60;&#62;]+" minlength="5" required autocomplete="off" />
                        </div>
                        <div class="d-grid">
                            <button name="pass_new" class="btn btn-primary">Ubah</button>
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