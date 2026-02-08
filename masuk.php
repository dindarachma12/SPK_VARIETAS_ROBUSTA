<?php
$validasi = isset($_GET['validasi']) ? trim($_GET['validasi']) : "";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Masuk Akun</title>
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
                    <h3 class="card-title">Masuk Akun</h3>
                    <?php
                    if ($validasi == "error") {
                        echo "
                        <div class='alert alert-danger alert-dismissible fade show mt-3' role='alert'>
                            Username atau Sandi Anda salah!
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                        ";
                    }
                    ?>

                    <form action="proses.php" method="post" class="mt-3">
                        <div class="mb-3">
                            <label for="inputEmail" class="form-label">Username</label>
                            <input name="user" class="form-control" id="inputEmail" type="text" placeholder="Masukkan username Anda" pattern="[A-Za-z0-9]+" title="Tidak boleh simbol" required autocomplete="off" />
                        </div>

                        <div class="mb-3">
                            <label for="pass" class="form-label">Sandi</label>
                            <input name="pass" class="form-control" id="pass" type="password" placeholder="Masukkan sandi Anda" pattern="[^&#34;&#39;&#60;&#62;]+" minlength="5" required autocomplete="off" />
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="auth-footer text-center mt-4">
                                <a href="lupa_pass.php" class="small">Lupa sandi?</a>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button name="masuk" class="btn btn-primary">Masuk</button>
                        </div>
                    </form>

                    <div class="auth-footer text-center mt-4">
                        <div>Belum punya akun? <a href="buat_akun.php">Buat Akun</a></div>
                    </div>
                </div>
            </div>

            <!-- Right: image grid -->
            <div class="col-lg-6">
                    <div class="image-grid">
                        <div class="img-wrap">
                            <!-- Ganti nama file sesuai aset: harvest.jpg -->
                            <img src="assets/img/Rectangle 1.png" alt="Panen kopi">
                        </div>
                        <div class="img-wrap">
                            <!-- Ganti nama file sesuai aset: inspect.jpg -->
                            <img src="assets/img/Rectangle 3.png" alt="Inspeksi tanaman">
                        </div>
                        <div class="img-wrap">
                            <!-- Ganti nama file sesuai aset: cherries.jpg -->
                            <img src="assets/img/kopi 2.png" alt="Buah kopi">
                        </div>
                        <div class="img-wrap">
                            <!-- Ganti nama file sesuai aset: roasted.jpg -->
                            <img src="assets/img/Rectangle 4.png" alt="Biji panggang">
                        </div>
                    </div>
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