<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['nama']) && !isset($_SESSION['level'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_POST['id_varietas'])) {
	header("Location: 404.php");
    exit();
} else {
	$id = $_POST['id_varietas'];
	$delete1 = mysqli_query($koneksi, "DELETE FROM varietas WHERE id_varietas = '$id'");
	$delete2 = mysqli_query($koneksi, "DELETE FROM matriks WHERE id_varietas = '$id'");
	if ($delete1 && $delete2) {
		header("Location: varietas.php?validasi=sukses-hapus");
	} else {
		header("Location: varietas.php?validasi=error");
	}
}
