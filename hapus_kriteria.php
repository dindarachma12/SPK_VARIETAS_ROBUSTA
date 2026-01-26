<?php
session_start();
include 'koneksi.php';

// Cek session login
if (!isset($_SESSION['nama']) || !isset($_SESSION['level'])) {
    header("Location: index.php");
    exit;
}

// Proses hapus
if (isset($_POST['hapus_kriteria']) && isset($_POST['id_kriteria'])) {
    $id = htmlspecialchars($_POST['id_kriteria']);
    
    // Cek apakah kriteria yang akan dihapus ada di database
    $query = mysqli_query($koneksi, "SELECT * FROM kriteria WHERE id_kriteria = '$id'");
    
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_array($query);

        // Hapus kriteria
        $delete = mysqli_query($koneksi, "DELETE FROM kriteria WHERE id_kriteria = '$id'");
        
        if ($delete && mysqli_affected_rows($koneksi) > 0) {
            header("Location: kriteria.php?validasi=sukses-hapus");
            exit;
        } else {
            header("Location: kriteria.php?validasi=error");
            exit;
        }
    } else {
        header("Location: kriteria.php?validasi=error");
        exit;
    }
} else {
    header("Location: kriteria.php");
    exit;
}
?>