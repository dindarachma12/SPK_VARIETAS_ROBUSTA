<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['nama']) || !isset($_SESSION['level'])) {
	header("Location: index.php");
	exit;
}

if ($_SESSION['level'] != "admin") {
	header("Location: homepage.php");
	exit;
}

if (isset($_POST['hapus_subkriteria'])) {
	$id_subkriteria = htmlspecialchars($_POST['id_subkriteria']);
	
	// Cek apakah subkriteria digunakan di tabel matriks
	$cek_matriks = mysqli_query($koneksi, "SELECT * FROM matriks WHERE id_subkriteria = '$id_subkriteria'");
	
	if (mysqli_num_rows($cek_matriks) > 0) {
		// Jika digunakan, ambil subkriteria lain dari kriteria yang sama untuk pengganti
		$get_kriteria = mysqli_query($koneksi, "SELECT id_kriteria FROM subkriteria WHERE id_subkriteria = '$id_subkriteria'");
		$data_kriteria = mysqli_fetch_array($get_kriteria);
		$id_kriteria = $data_kriteria['id_kriteria'];
		
		// Ambil subkriteria pengganti (yang tertinggi dari kriteria yang sama, selain yang akan dihapus)
		$get_pengganti = mysqli_query($koneksi, "SELECT id_subkriteria FROM subkriteria WHERE id_kriteria = '$id_kriteria' AND id_subkriteria != '$id_subkriteria' ORDER BY nilai_subkriteria DESC LIMIT 1");
		
		if (mysqli_num_rows($get_pengganti) > 0) {
			$data_pengganti = mysqli_fetch_array($get_pengganti);
			$id_pengganti = $data_pengganti['id_subkriteria'];
			
			// Update matriks dengan subkriteria pengganti
			$update = mysqli_query($koneksi, "UPDATE matriks SET id_subkriteria = '$id_pengganti' WHERE id_subkriteria = '$id_subkriteria'");
			
			if ($update) {
				// Hapus subkriteria setelah matriks diupdate
				$delete = mysqli_query($koneksi, "DELETE FROM subkriteria WHERE id_subkriteria = '$id_subkriteria'");
				if ($delete) {
					header("Location: subkriteria.php?validasi=sukses-hapus");
					exit;
				} else {
					header("Location: subkriteria.php?validasi=error");
					exit;
				}
			} else {
				header("Location: subkriteria.php?validasi=error");
				exit;
			}
		} else {
			// Tidak ada subkriteria pengganti, berarti ini subkriteria terakhir
			header("Location: subkriteria.php?validasi=warning&pesan=Tidak dapat menghapus subkriteria terakhir yang sedang digunakan");
			exit;
		}
	} else {
		// Jika tidak digunakan, langsung hapus
		$delete = mysqli_query($koneksi, "DELETE FROM subkriteria WHERE id_subkriteria = '$id_subkriteria'");
		if ($delete) {
			header("Location: subkriteria.php?validasi=sukses-hapus");
			exit;
		} else {
			header("Location: subkriteria.php?validasi=error");
			exit;
		}
	}
} else {
	header("Location: subkriteria.php");
	exit;
}
?>