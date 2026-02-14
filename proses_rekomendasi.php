<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['nama']) && !isset($_SESSION['level'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_POST['masuk'])) {
    header("Location: rekomendasi.php");
    exit;
}

// ========================================================================
// AMBIL DATA DARI FORM
// ========================================================================
$username = $_SESSION['username'];
$kriteria_ids = isset($_POST['kriteria']) ? $_POST['kriteria'] : [];
$subkriteria_ids = isset($_POST['subkriteria']) ? $_POST['subkriteria'] : [];

// Validasi: Cek apakah semua kriteria sudah diisi
$jumlah_kriteria = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM kriteria"));

if (empty($subkriteria_ids) || count($subkriteria_ids) != $jumlah_kriteria) {
    header("Location: rekomendasi.php?validasi=error");
    exit;
}

// Validasi: Cek apakah semua varietas sudah memiliki data lengkap
$query_varietas = mysqli_query($koneksi, "SELECT * FROM varietas");
$cek = 0;
while ($baris = mysqli_fetch_array($query_varietas)) {
    $id_varietas = $baris['id_varietas'];
    $query = mysqli_query($koneksi, "SELECT * FROM matriks WHERE id_varietas = '$id_varietas'");
    if (mysqli_num_rows($query) < $jumlah_kriteria) {
        $cek++;
    }
}

if ($cek > 0) {
    header("Location: rekomendasi.php?validasi=minus");
    exit;
}

// Hapus data lama untuk user ini
mysqli_query($koneksi, "DELETE FROM checked WHERE username = '$username'");
mysqli_query($koneksi, "DELETE FROM peringkat WHERE username = '$username'");

// ========================================================================
// AMBIL KONDISI LAHAN PETANI (INPUT USER)
// ========================================================================
$kondisi_lahan = [];

foreach ($subkriteria_ids as $id_krit => $id_subkrit) {
    $query_nilai_lahan = mysqli_query($koneksi, "
        SELECT nilai_subkriteria 
        FROM subkriteria 
        WHERE id_subkriteria = '$id_subkrit'
    ");
    
    if ($data = mysqli_fetch_array($query_nilai_lahan)) {
        $kondisi_lahan[$id_krit] = floatval($data['nilai_subkriteria']);
    } else {
        $kondisi_lahan[$id_krit] = 0;
    }
}

// ========================================================================
// AMBIL DATA VARIETAS
// ========================================================================
$query_all_varietas = mysqli_query($koneksi, "SELECT DISTINCT id_varietas FROM matriks WHERE id_varietas != 0 ORDER BY id_varietas");
$varietas_list = [];
while ($row = mysqli_fetch_array($query_all_varietas)) {
    $varietas_list[] = $row['id_varietas'];
}

if (empty($varietas_list)) {
    header("Location: rekomendasi.php?validasi=error");
    exit;
}

// Insert data checked untuk setiap varietas
foreach ($varietas_list as $id_var) {
    mysqli_query($koneksi, "INSERT INTO checked (id_varietas, username) VALUES ('$id_var', '$username')");
}

// Ambil data kriteria beserta jenisnya
$query_kriteria = mysqli_query($koneksi, "SELECT * FROM kriteria ORDER BY id_kriteria");
$kriteria_data = [];
$kriteria_types = [];
while ($k = mysqli_fetch_array($query_kriteria)) {
    $kriteria_data[] = $k;
    $kriteria_types[$k['id_kriteria']] = $k['jenis_kriteria'];
}

// ========================================================================
// BUAT MATRIKS KEPUTUSAN (NILAI ASLI VARIETAS - TANPA SELISIH)
// ========================================================================
$matriks = [];
$varietas_ids = [];

foreach ($varietas_list as $id_var) {
    $row_data = [];
    
    foreach ($kriteria_data as $krit) {
        $id_krit = $krit['id_kriteria'];
        
        // Ambil nilai kebutuhan varietas LANGSUNG (TANPA HITUNG SELISIH)
        $query_kebutuhan = mysqli_query($koneksi, "
            SELECT s.nilai_subkriteria 
            FROM matriks m 
            JOIN subkriteria s ON m.id_subkriteria = s.id_subkriteria 
            WHERE m.id_varietas = '$id_var' AND m.id_kriteria = '$id_krit'
        ");
        
        if ($data_kebutuhan = mysqli_fetch_array($query_kebutuhan)) {
            $kebutuhan_varietas = floatval($data_kebutuhan['nilai_subkriteria']);
            $row_data[] = $kebutuhan_varietas; // ← LANGSUNG PAKAI NILAI ASLI
        } else {
            $row_data[] = 1;
        }
    }
    
    $matriks[] = $row_data;
    $varietas_ids[] = $id_var;
}

// ========================================================================
// METODE CRITIC (Pembobotan Kriteria)
// ========================================================================
$matriks_critic = $matriks; // Sama dengan matriks keputusan

$m = count($matriks_critic);
$n = count($matriks_critic[0]);

// Langkah 1: Normalisasi CRITIC (Min-Max) - PAKAI BENEFIT/COST
$norm_critic = [];

for ($j = 0; $j < $n; $j++) {
    $col_values = array_column($matriks_critic, $j);
    $min_val = min($col_values);
    $max_val = max($col_values);
    $range = $max_val - $min_val;

    $id_krit = $kriteria_data[$j]['id_kriteria'];
    $jenis = $kriteria_types[$id_krit];
    
    for ($i = 0; $i < $m; $i++) {
        if ($range == 0) {
            $norm_critic[$i][$j] = 1.0;
        } else {
            if ($jenis == 'benefit') {
                $norm_critic[$i][$j] = ($matriks_critic[$i][$j] - $min_val) / $range;
            } else { // cost
                $norm_critic[$i][$j] = ($max_val - $matriks_critic[$i][$j]) / $range;
            }
        }
    }
}

// Langkah 2: Hitung Standar Deviasi
$std_dev = [];
for ($j = 0; $j < $n; $j++) {
    $col = array_column($norm_critic, $j);
    $mean = array_sum($col) / count($col);
    $variance = 0;
    foreach ($col as $val) {
        $variance += pow($val - $mean, 2);
    }
    $std_dev[$j] = sqrt($variance / max(1, (count($col) - 1)));
}

// Langkah 3: Hitung Korelasi
$corr_matrix = [];
for ($j1 = 0; $j1 < $n; $j1++) {
    for ($j2 = 0; $j2 < $n; $j2++) {
        if ($j1 == $j2) {
            $corr_matrix[$j1][$j2] = 1.0;
        } else {
            $col1 = array_column($norm_critic, $j1);
            $col2 = array_column($norm_critic, $j2);
            
            $mean1 = array_sum($col1) / count($col1);
            $mean2 = array_sum($col2) / count($col2);
            
            $numerator = 0;
            $sum_sq1 = 0;
            $sum_sq2 = 0;
            
            for ($i = 0; $i < count($col1); $i++) {
                $diff1 = $col1[$i] - $mean1;
                $diff2 = $col2[$i] - $mean2;
                $numerator += $diff1 * $diff2;
                $sum_sq1 += $diff1 * $diff1;
                $sum_sq2 += $diff2 * $diff2;
            }
            
            $denominator = sqrt($sum_sq1 * $sum_sq2);
            if ($denominator == 0) {
                $corr_matrix[$j1][$j2] = 0;
            } else {
                $corr_matrix[$j1][$j2] = $numerator / $denominator;
            }
        }
    }
}

// Langkah 4: Hitung Informasi Kriteria (Cj)
$Cj = [];
for ($j = 0; $j < $n; $j++) {
    $sum_one_minus_r = 0;
    for ($k = 0; $k < $n; $k++) {
        if ($j != $k) {
            $sum_one_minus_r += (1 - $corr_matrix[$j][$k]);
        }
    }
    $Cj[$j] = $std_dev[$j] * $sum_one_minus_r;
}

// Langkah 5: Hitung Bobot
$sum_Cj = array_sum($Cj);
$weights = [];
for ($j = 0; $j < $n; $j++) {
    if ($sum_Cj == 0) {
        $weights[$j] = 1.0 / $n;
    } else {
        $weights[$j] = $Cj[$j] / $sum_Cj;
    }
}

// ========================================================================
// METODE TOPSIS (Perangkingan Alternatif)
// ========================================================================

// Langkah 1: Normalisasi TOPSIS (Vector Normalization - TANPA Benefit/Cost)
$R = [];
$denominators = []; // SIMPAN denominator untuk input petani

for ($j = 0; $j < $n; $j++) {
    $col_values = array_column($matriks, $j);
    $sum_sq = 0;
    foreach ($col_values as $val) {
        $sum_sq += $val * $val;
    }
    $denom = sqrt($sum_sq);
    $denominators[$j] = $denom; // ← SIMPAN untuk input petani
        
    for ($i = 0; $i < $m; $i++) {
        if ($denom == 0) {
            $R[$i][$j] = 0;
        } else {
            $R[$i][$j] = $matriks[$i][$j] / $denom;
        }
    }
}

// Langkah 2: Matriks Terbobot (TANPA Benefit/Cost)
$Y = [];
for ($i = 0; $i < $m; $i++) {
    for ($j = 0; $j < $n; $j++) {
        $Y[$i][$j] = $R[$i][$j] * $weights[$j];
    }
}

// ========================================================================
// NORMALISASI INPUT PETANI (TANPA Benefit/Cost)
// ========================================================================
$r_petani = [];
$y_petani = [];

for ($j = 0; $j < $n; $j++) {
    $id_krit = $kriteria_data[$j]['id_kriteria'];
    $nilai_petani = isset($kondisi_lahan[$id_krit]) ? $kondisi_lahan[$id_krit] : 0;
    
    // Pakai denominator yang SAMA dengan varietas
    $denom = $denominators[$j];
    
    if ($denom == 0) {
        $r_petani[$j] = 0;
    } else {
        $r_petani[$j] = $nilai_petani / $denom;
    }
    
    // Kalikan dengan bobot CRITIC
    $y_petani[$j] = $r_petani[$j] * $weights[$j];
}

// ========================================================================
// Langkah 3: Solusi Ideal
// ========================================================================

// A⁺ = Input petani terbobot (TANPA Benefit/Cost)
$y_plus = $y_petani;

// A⁻ = Nilai ekstrem berdasarkan benefit/cost (PAKAI Benefit/Cost)
$y_minus = [];
for ($j = 0; $j < $n; $j++) {
    $col = array_column($Y, $j);
    $id_krit = $kriteria_data[$j]['id_kriteria'];
    $jenis = $kriteria_types[$id_krit];
    
    if ($jenis == 'benefit') {
        // Benefit: nilai tinggi = baik → nilai rendah = terburuk → ambil MIN
        $y_minus[$j] = min($col);
    } else { // cost
        // Cost: nilai rendah = baik → nilai tinggi = terburuk → ambil MAX
        $y_minus[$j] = max($col);
    }
}

// Langkah 4: Hitung Separasi (TANPA Benefit/Cost)
$D_plus = [];
$D_minus = [];
for ($i = 0; $i < $m; $i++) {
    $sum_plus = 0;
    $sum_minus = 0;
    for ($j = 0; $j < $n; $j++) {
        $sum_plus += pow($y_plus[$j] - $Y[$i][$j], 2);
        $sum_minus += pow($y_minus[$j] - $Y[$i][$j], 2);
    }
    $D_plus[$i] = sqrt($sum_plus);
    $D_minus[$i] = sqrt($sum_minus);
}

// Langkah 5: Hitung Nilai Preferensi (TANPA Benefit/Cost)
$scores = [];
for ($i = 0; $i < $m; $i++) {
    $denom = $D_plus[$i] + $D_minus[$i];
    if ($denom == 0) {
        $scores[$i] = 0;
    } else {
        $scores[$i] = $D_minus[$i] / $denom;
    }
}

// ========================================================================
// AMBIL NAMA VARIETAS DAN KRITERIA
// ========================================================================
$varietas_names = [];
foreach ($varietas_ids as $id_var) {
    $q = mysqli_query($koneksi, "SELECT nama_varietas FROM varietas WHERE id_varietas = '$id_var'");
    if ($row = mysqli_fetch_array($q)) {
        $varietas_names[$id_var] = $row['nama_varietas'];
    }
}

$kriteria_names = [];
foreach ($kriteria_data as $krit) {
    $kriteria_names[$krit['id_kriteria']] = $krit['nama_kriteria'];
}

// ========================================================================
// SIMPAN HASIL PERHITUNGAN KE SESSION
// ========================================================================
$_SESSION['hasil_perhitungan'] = [
    // Data Input
    'kondisi_lahan' => $kondisi_lahan,
    'varietas_list' => $varietas_list,
    'varietas_ids' => $varietas_ids,
    'varietas_names' => $varietas_names,
    'kriteria_data' => $kriteria_data,
    'kriteria_names' => $kriteria_names,
    
    // Matriks Keputusan (nilai asli varietas)
    'matriks_keputusan' => $matriks,
    
    // CRITIC
    'matriks_critic' => $matriks_critic,
    'norm_critic' => $norm_critic,
    'std_dev' => $std_dev,
    'corr_matrix' => $corr_matrix,
    'Cj' => $Cj,
    'weights' => $weights,
    
    // TOPSIS
    'R' => $R,
    'Y' => $Y,
    'denominators' => $denominators,
    'r_petani' => $r_petani,
    'y_petani' => $y_petani,
    'y_plus' => $y_plus,
    'y_minus' => $y_minus,
    'D_plus' => $D_plus,
    'D_minus' => $D_minus,
    'scores' => $scores
];

// ========================================================================
// SIMPAN HASIL KE DATABASE
// ========================================================================
$cek_peringkat = mysqli_query($koneksi, "SELECT * FROM peringkat WHERE username = '$username'");
if (mysqli_num_rows($cek_peringkat) > 0) {
    mysqli_query($koneksi, "DELETE FROM peringkat WHERE username = '$username'");
}

$success = true;
for ($i = 0; $i < $m; $i++) {
    $id_var = $varietas_ids[$i];
    $nilai = round($scores[$i], 6);
    
    $insert = mysqli_query($koneksi, "
        INSERT INTO peringkat (id_varietas, nilai_peringkat, username) 
        VALUES ('$id_var', '$nilai', '$username')
    ");
    
    if (!$insert) {
        $success = false;
        break;
    }
}

if ($success) {
    header("Location: hasil_rekomendasi.php?validasi=sukses-tambah");
} else {
    header("Location: hasil_rekomendasi.php?validasi=warning");
}
exit;
?>