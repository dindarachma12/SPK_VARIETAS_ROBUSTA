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

    <style>
        :root{
            --robusta-brown: #7a4b2a;
            --robusta-brown-dark: #6b3f22;
            --text-dark: #2e2a28;
            --overlay: rgba(0,0,0,0.45);
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

        /* Centering layout */
        .page-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 60px 0;
        }

        /* Left card */
        .auth-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(46,42,40,0.08);
            border: 1px solid rgba(46,42,40,0.04);
            padding: 28px;
        }
        .auth-card .card-title {
            font-weight: 600;
            font-size: 1.35rem;
            color: var(--text-dark);
            margin-bottom: 6px;
        }
        .auth-card .form-label {
            color: rgba(46,42,40,0.85);
            font-weight: 500;
        }
        .auth-card .form-control {
            border-radius: 8px;
            border: 1px solid rgba(46,42,40,0.08);
            padding: 12px 14px;
            background: #fff;
            color: var(--text-dark);
        }
        .auth-card .form-control:focus {
            box-shadow: 0 6px 18px rgba(122,75,42,0.06);
            border-color: var(--robusta-brown);
            outline: none;
        }
        .auth-card .btn-primary {
            background: var(--robusta-brown);
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 500;
        }
        .auth-card .btn-primary:hover {
            background: var(--robusta-brown-dark);
        }
        .auth-footer {
            margin-top: 14px;
            font-size: 0.95rem;
            color: rgba(46,42,40,0.8);
        }
        .auth-footer a { color: var(--robusta-brown); font-weight:600; text-decoration:none; }
        .auth-footer a:hover { text-decoration:underline; }

        /* NAVBAR */
        .navbar-custom {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            background: transparent;
            padding: 18px 0;
        }
        .brand-wrap {
            display:flex;
            align-items:center;
            gap:12px;
        }
        .brand-wrap img {
            width:200px;
            height:auto;
            display:block;
        }
        /* Logo above card (optional) */
        .logo-wrap {
            display:flex;
            align-items:center;
            gap:12px;
            margin-bottom: 18px;
        }
        .logo-wrap img {
            width: 140px;
            height: auto;
            display:block;
        }
        .logo-title {
            font-weight:700;
            color: var(--text-dark);
            font-size:1.25rem;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .image-grid img { height: 120px; }
            .auth-card { padding: 20px; }
        }
        @media (max-width: 767px) {
            body { background-size: 200%; } /* optional tweak for small screens */
            .image-grid { grid-template-columns: repeat(2, 1fr); gap:10px; }
            .image-grid img { height: 110px; }
            .logo-wrap { justify-content:center; }
        }
    </style>
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