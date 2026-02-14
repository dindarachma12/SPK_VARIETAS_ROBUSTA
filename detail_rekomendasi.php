<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['nama']) && !isset($_SESSION['level'])) {
    header("Location: index.php");
    exit;
}

// Debugging: cek apakah session ada
if (!isset($_SESSION['hasil_perhitungan'])) {
    header("Location: rekomendasi.php");
    exit;
}

$level = $_SESSION['level'];
$data = $_SESSION['hasil_perhitungan'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Detail Rekomendasi</title>
    <link rel="icon" type="image/x-icon" href="assets/img/logo_robustaku.png" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="css/detail.css" rel="stylesheet" />
</head>

<body>
    <!-- NAVBAR -->
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
                    <li class="nav-item"><a class="nav-link active" href="rekomendasi.php">Rekomendasi</a></li>
                    <?php if ($level == 'admin'): ?>
                    <li class="nav-item"><a class="nav-link unactive" href="kriteria.php">Kriteria</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="subkriteria.php">Subkriteria</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="varietas.php">Varietas</a></li>
                    <li class="nav-item"><a class="nav-link unactive" href="pengguna.php">Pengguna</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link unactive" href="profil.php">Profil</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container container-content">
        <div class="col-lg-6">
            <h1 class="hero-title">Detail Rekomendasi</h1>
        </div>
        <?php
        $nama = $_SESSION['username'];
        $query = mysqli_query($koneksi, "SELECT varietas.nama_varietas AS nama_varietas FROM peringkat JOIN varietas ON peringkat.id_varietas = varietas.id_varietas WHERE peringkat.username = '$nama' ORDER BY peringkat.nilai_peringkat DESC LIMIT 1");
        $data_rekomendasi = mysqli_fetch_array($query);
        ?>

        <!-- Tabel Hasil Rekomendasi -->
        <p class="description-text">
            Berdasarkan hasil perhitungan Sistem Pendukung Keputusan dengan menggunakan metode TOPSIS dan CRITIC, didapatkan bahwa "<?php echo $data_rekomendasi['nama_varietas']; ?>" adalah varietas kopi robusta yang paling direkomendasikan untuk lahan Anda
        </p>
        <!-- 1. KONDISI LAHAN PETANI -->
        <div class="section">
            <h2>Kondisi Lahan Petani (Input)</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kriteria</th>
                            <th>Nilai Kondisi Lahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($data['kondisi_lahan'] as $id_krit => $nilai): 
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= isset($data['kriteria_names'][$id_krit]) ? $data['kriteria_names'][$id_krit] : "Kriteria $id_krit" ?></td>
                                <td><strong><?= $nilai ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. MATRIKS KEPUTUSAN -->
        <div class="section">
            <h2>Matriks Keputusan</h2>
            <p class="info-text">Nilai kebutuhan optimal setiap varietas untuk masing-masing kriteria</p>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Varietas</th>
                            <?php foreach ($data['kriteria_data'] as $krit): ?>
                                <th><?= $krit['nama_kriteria'] ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($data['matriks_keputusan'] as $i => $row): 
                            $id_var = $data['varietas_ids'][$i];
                            $nama_var = isset($data['varietas_names'][$id_var]) ? $data['varietas_names'][$id_var] : "Varietas $id_var";
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= $nama_var ?></strong></td>
                                <?php foreach ($row as $val): ?>
                                    <td><?= number_format($val, 4) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. METODE CRITIC -->
        <div class="section">
            <h2>Metode CRITIC (Pembobotan Kriteria)</h2>
            
            <!-- 3.1. Matriks Awal CRITIC -->
            <h3>Matriks Data Awal</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Varietas</th>
                            <?php foreach ($data['kriteria_data'] as $krit): ?>
                                <th><?= $krit['nama_kriteria'] ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($data['matriks_critic'] as $i => $row): 
                            $id_var = $data['varietas_ids'][$i];
                            $nama_var = isset($data['varietas_names'][$id_var]) ? $data['varietas_names'][$id_var] : "Varietas $id_var";
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= $nama_var ?></strong></td>
                                <?php foreach ($row as $val): ?>
                                    <td><?= number_format($val, 4) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- 3.2. Normalisasi CRITIC -->
            <h3>Normalisasi Matriks CRITIC</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Varietas</th>
                            <?php foreach ($data['kriteria_data'] as $krit): ?>
                                <th><?= $krit['nama_kriteria'] ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($data['norm_critic'] as $i => $row): 
                            $id_var = $data['varietas_ids'][$i];
                            $nama_var = isset($data['varietas_names'][$id_var]) ? $data['varietas_names'][$id_var] : "Varietas $id_var";
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= $nama_var ?></strong></td>
                                <?php foreach ($row as $val): ?>
                                    <td><?= number_format($val, 4) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- 3.3. Deviasi Standar -->
            <h3>Deviasi Standar</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kriteria</th>
                            <th>Deviasi Standar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($data['std_dev'] as $j => $std): 
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data['kriteria_data'][$j]['nama_kriteria'] ?></td>
                                <td><?= number_format($std, 4) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- 3.4. Matriks Korelasi -->
            <h3>Matriks Korelasi</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <?php foreach ($data['kriteria_data'] as $krit): ?>
                                <th><?= $krit['nama_kriteria'] ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['corr_matrix'] as $j1 => $row): ?>
                            <tr>
                                <td><strong><?= $data['kriteria_data'][$j1]['nama_kriteria'] ?></strong></td>
                                <?php foreach ($row as $val): ?>
                                    <td><?= number_format($val, 4) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- 3.5. Informasi Kriteria (Cj) -->
            <h3>Nilai Informasi Kriteria (Cj)</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kriteria</th>
                            <th>Cj</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($data['Cj'] as $j => $cj): 
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data['kriteria_data'][$j]['nama_kriteria'] ?></td>
                                <td><?= number_format($cj, 4) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- 3.6. Bobot Akhir -->
            <h3>Bobot Kriteria (Hasil CRITIC)</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kriteria</th>
                            <th>Bobot</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($data['weights'] as $j => $w): 
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data['kriteria_data'][$j]['nama_kriteria'] ?></td>
                                <td><?= number_format($w, 4) ?></td>
                                <td><strong><?= number_format($w * 100, 2) ?>%</strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. METODE TOPSIS -->
        <div class="section">
            <h2>Metode TOPSIS (Perangkingan)</h2>
            
            <!-- 4.1. Normalisasi TOPSIS -->
            <h3>Matriks Ternormalisasi (R)</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Varietas</th>
                            <?php foreach ($data['kriteria_data'] as $krit): ?>
                                <th><?= $krit['nama_kriteria'] ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($data['R'] as $i => $row): 
                            $id_var = $data['varietas_ids'][$i];
                            $nama_var = isset($data['varietas_names'][$id_var]) ? $data['varietas_names'][$id_var] : "Varietas $id_var";
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= $nama_var ?></strong></td>
                                <?php foreach ($row as $val): ?>
                                    <td><?= number_format($val, 4) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- 4.2. Matriks Terbobot -->
            <h3>Matriks Terbobot (Y)</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Varietas</th>
                            <?php foreach ($data['kriteria_data'] as $krit): ?>
                                <th><?= $krit['nama_kriteria'] ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($data['Y'] as $i => $row): 
                            $id_var = $data['varietas_ids'][$i];
                            $nama_var = isset($data['varietas_names'][$id_var]) ? $data['varietas_names'][$id_var] : "Varietas $id_var";
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= $nama_var ?></strong></td>
                                <?php foreach ($row as $val): ?>
                                    <td><?= number_format($val, 4) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- 4.3. Solusi Ideal -->
            <h3>Solusi Ideal Positif dan Negatif</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kriteria</th>
                            <th>Y+ (Ideal Positif)</th>
                            <th>Y- (Ideal Negatif)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($data['y_plus'] as $j => $val): 
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data['kriteria_data'][$j]['nama_kriteria'] ?></td>
                                <td><?= number_format($val, 4) ?></td>
                                <td><?= number_format($data['y_minus'][$j], 4) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- 4.4. Jarak Separasi -->
            <h3>Jarak Separasi</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Varietas</th>
                            <th>D+ (Jarak ke Ideal Positif)</th>
                            <th>D- (Jarak ke Ideal Negatif)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($data['D_plus'] as $i => $val): 
                            $id_var = $data['varietas_ids'][$i];
                            $nama_var = isset($data['varietas_names'][$id_var]) ? $data['varietas_names'][$id_var] : "Varietas $id_var";
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= $nama_var ?></strong></td>
                                <td><?= number_format($val, 4) ?></td>
                                <td><?= number_format($data['D_minus'][$i], 4) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- 4.5. Nilai Preferensi -->
            <h3>4.5. Nilai Preferensi (Skor Akhir)</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Peringkat</th>
                            <th>Kode Varietas</th> 
                            <th>Nama Varietas</th>
                            <th>Nilai Preferensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Urutkan berdasarkan skor tertinggi
                        $ranked_data = [];
                        foreach ($data['scores'] as $i => $score) {
                            $id_var = $data['varietas_ids'][$i];
                            
                            // Ambil kode dan nama varietas dari database
                            $q_var = mysqli_query($koneksi, "SELECT kode_varietas, nama_varietas FROM varietas WHERE id_varietas = '$id_var'");
                            $varietas_info = mysqli_fetch_array($q_var);
                            
                            $ranked_data[] = [
                                'id_varietas' => $id_var,
                                'kode' => $varietas_info ? $varietas_info['kode_varietas'] : "V-$id_var",
                                'nama' => $varietas_info ? $varietas_info['nama_varietas'] : "Varietas $id_var",
                                'score' => $score
                            ];
                        }
                        usort($ranked_data, function($a, $b) {
                            return $b['score'] <=> $a['score'];
                        });
                        
                        $rank = 1;
                        foreach ($ranked_data as $item):
                            $row_class = '';
                            if ($rank == 1) $row_class = 'rank-1';
                            elseif ($rank == 2) $row_class = 'rank-2';
                            elseif ($rank == 3) $row_class = 'rank-3';
                        ?>
                            <tr class="<?= $row_class ?>">
                                <td><span><?= $rank++ ?></span></td>
                                <td><strong><?= $item['kode'] ?></strong></td>
                                <td><?= $item['nama'] ?></td>
                                <td><strong><?= number_format($item['score'], 6) ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>