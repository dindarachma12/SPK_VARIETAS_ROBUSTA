<?php
session_start();
include 'koneksi.php';

// Cek session login
if (!isset($_SESSION['nama']) || !isset($_SESSION['level'])) {
    header("Location: index.php");
    exit;
}

// Proses hapus
if (isset($_POST['hapus_pengguna']) && isset($_POST['id'])) {
    $id = htmlspecialchars($_POST['id']);
    
    // Cek apakah pengguna yang akan dihapus ada di database
    $query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id_pengguna = '$id'");
    
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_array($query);
        
        // Hapus pengguna
        $delete = mysqli_query($koneksi, "DELETE FROM pengguna WHERE id_pengguna = '$id'");
        
        if ($delete && mysqli_affected_rows($koneksi) > 0) {
            header("Location: pengguna.php?validasi=sukses-hapus");
            exit;
        } else {
            header("Location: pengguna.php?validasi=error");
            exit;
        }
    } else {
        header("Location: pengguna.php?validasi=error");
        exit;
    }
} else {
    header("Location: pengguna.php");
    exit;
}
?>