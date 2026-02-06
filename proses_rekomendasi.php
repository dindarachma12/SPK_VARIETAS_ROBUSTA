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

// Ambil data dari form (KONDISI LAHAN PETANI)
$username = $_SESSION['username'];
$kriteria_ids = isset($_POST['kriteria']) ? $_POST['kriteria'] : [];
$subkriteria_ids = isset($_POST['subkriteria']) ? $_POST['subkriteria'] : [];

// Validasi: Cek apakah semua varietas sudah memiliki data lengkap
$jumlah_kriteria = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM kriteria"));

// Validasi input dari form
if (empty($subkriteria_ids) || count($subkriteria_ids) != $jumlah_kriteria) {
    header("Location: rekomendasi.php?validasi=error");
    exit;
}

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
$kondisi_lahan = []; // Array untuk menyimpan nilai kondisi lahan petani

foreach ($subkriteria_ids as $id_krit => $id_subkrit) {
    // PERBAIKAN: Gunakan variabel yang benar
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
// BUAT MATRIKS KEPUTUSAN BERDASARKAN KESESUAIAN DENGAN KONDISI LAHAN
// ========================================================================
// KONSEP BARU: Hitung kesesuaian berdasarkan kedekatan nilai varietas dengan kondisi lahan petani
// Semakin dekat nilai kebutuhan varietas dengan kondisi lahan, semakin tinggi skornya

$matriks = [];
$varietas_ids = [];

foreach ($varietas_list as $id_var) {
    $row_data = [];
    
    foreach ($kriteria_data as $krit) {
        $id_krit = $krit['id_kriteria'];
        
        // Ambil kebutuhan ideal varietas untuk kriteria ini
        $query_kebutuhan = mysqli_query($koneksi, "
            SELECT s.nilai_subkriteria 
            FROM matriks m 
            JOIN subkriteria s ON m.id_subkriteria = s.id_subkriteria 
            WHERE m.id_varietas = '$id_var' AND m.id_kriteria = '$id_krit'
        ");
        
        if ($data_kebutuhan = mysqli_fetch_array($query_kebutuhan)) {
            $kebutuhan_varietas = floatval($data_kebutuhan['nilai_subkriteria']);
            $kondisi_petani = isset($kondisi_lahan[$id_krit]) ? $kondisi_lahan[$id_krit] : 0;
            
            // Hitung selisih absolut
            $selisih = abs($kebutuhan_varietas - $kondisi_petani);
            
            // Konversi ke nilai kesesuaian (0-5)
            // Selisih 0 = nilai 5 (sangat cocok)
            // Selisih 4 = nilai 1 (tidak cocok)
            // Formula: nilai = 5 - selisih
            $nilai_kesesuaian = max(1, 5 - $selisih);
            
            $row_data[] = $nilai_kesesuaian;
        } else {
            $row_data[] = 1; // Nilai default terendah jika data tidak ada
        }
    }
    
    $matriks[] = $row_data;
    $varietas_ids[] = $id_var;
}

// ========================================================================
// METODE CRITIC (Pembobotan Kriteria)
// ========================================================================

$m = count($matriks); // Jumlah alternatif/varietas
$n = count($matriks[0]); // Jumlah kriteria

// Langkah 1: Normalisasi untuk CRITIC (Min-Max Normalization)
$norm_critic = [];

for ($j = 0; $j < $n; $j++) {
    $col_values = array_column($matriks, $j);
    $min_val = min($col_values);
    $max_val = max($col_values);
    $range = $max_val - $min_val;

    $id_krit = $kriteria_data[$j]['id_kriteria'];
    $jenis = $kriteria_types[$id_krit];
    
    for ($i = 0; $i < $m; $i++) {
        if ($range == 0) {
            $norm_critic[$i][$j] = 1.0; // Jika semua nilai sama, beri nilai 1
        } else {
            if ($jenis == 'benefit') {
                $norm_critic[$i][$j] = ($matriks[$i][$j] - $min_val) / $range;
            } else { // cost
                $norm_critic[$i][$j] = ($max_val - $matriks[$i][$j]) / $range;
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

// Langkah 1: Normalisasi TOPSIS
$R = [];
for ($j = 0; $j < $n; $j++) {
    $col_values = array_column($matriks, $j);
    // BENEFIT: Normalisasi biasa
    $sum_sq = 0;
    foreach ($col_values as $val) {
        $sum_sq += $val * $val;
    }
    $denom = sqrt($sum_sq);
        
    for ($i = 0; $i < $m; $i++) {
        if ($denom == 0) {
            $R[$i][$j] = 0;
        } else {
            $R[$i][$j] = $matriks[$i][$j] / $denom;
        }
    }
}

// Langkah 2: Matriks Terbobot
$Y = [];
for ($i = 0; $i < $m; $i++) {
    for ($j = 0; $j < $n; $j++) {
        $Y[$i][$j] = $R[$i][$j] * $weights[$j];
    }
}

// Langkah 3: Solusi Ideal (Semua kriteria adalah BENEFIT karena sudah dikonversi ke nilai kesesuaian)
$y_plus = [];
$y_minus = [];
for ($j = 0; $j < $n; $j++) {
    $col = array_column($Y, $j);
    $y_plus[$j] = max($col);   // Benefit: max = ideal positif
    $y_minus[$j] = min($col);  // Benefit: min = ideal negatif
}

// Langkah 4: Hitung Separasi
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

// Langkah 5: Hitung Nilai Preferensi
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
// SIMPAN HASIL KE DATABASE
// ========================================================================

// Cek apakah sudah ada data peringkat untuk user ini
$cek_peringkat = mysqli_query($koneksi, "SELECT * FROM peringkat WHERE username = '$username'");
if (mysqli_num_rows($cek_peringkat) > 0) {
    // Hapus data lama
    mysqli_query($koneksi, "DELETE FROM peringkat WHERE username = '$username'");
}

// Insert data baru
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

// Redirect ke halaman hasil
if ($success) {
    header("Location: hasil_rekomendasi.php?validasi=sukses-tambah");
} else {
    header("Location: hasil_rekomendasi.php?validasi=warning");
}
exit;
?>