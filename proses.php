<?php
session_start();
include 'koneksi.php';

if (isset($_POST['masuk'])) {
	$user = htmlspecialchars($_POST['user']);
	$pass = htmlspecialchars($_POST['pass']);

	$query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username = '$user'");
	if (mysqli_num_rows($query) > 0) {
		$baris = mysqli_fetch_array($query);
		if (password_verify($pass, $baris['password'])){
			$_SESSION['id'] = $baris['id_pengguna'];
			$_SESSION['username'] = $baris['username'];
			$_SESSION['nama'] = $baris['nama'];
			$_SESSION['level'] = $baris['level'];
			header("Location: homepage.php?validasi=sukses");
			exit;
		} else {
			header("Location: masuk.php?validasi=error");
			exit;
		}
	} else {
		header("Location: masuk.php?validasi=error");
		exit;
	}
}

if (isset($_POST['daftar_akun'])) {
	$nama = htmlspecialchars($_POST['nama']);
	$user = htmlspecialchars($_POST['user']);
	$pass = htmlspecialchars($_POST['pass']);
	$konfir = htmlspecialchars($_POST['konfir']);

	if ($pass == $konfir) {
		$query = mysqli_query($koneksi, "SELECT username FROM pengguna WHERE username = '$user'");
		if (mysqli_num_rows($query) > 0) {
			header("Location: buat_akun.php?validasi=warning");
			exit;
		} else {
			$hash = password_hash($pass, PASSWORD_DEFAULT);
			$insert = mysqli_query($koneksi, "INSERT INTO pengguna(nama, username, password, level) VALUES('$nama', '$user', '$hash', 'User')");
			if ($insert) {
				header("Location: buat_akun.php?validasi=sukses");
				exit;
			} else {
				header("Location: buat_akun.php?validasi=error");
				exit;
			}
		}
	} else {
		header("Location: buat_akun.php?validasi=error");
		exit;
	}
}

if (isset($_POST['tambah_kriteria'])) {
	$kode = htmlspecialchars($_POST['kode_kriteria']);
	$nama = htmlspecialchars($_POST['nama_kriteria']);
	$jenis = htmlspecialchars($_POST['jenis_kriteria']);

	$query = mysqli_query($koneksi, "SELECT kode_kriteria FROM kriteria WHERE kode_kriteria = '$kode'");
	if (mysqli_num_rows($query) > 0) {
		header("Location: kriteria.php?validasi=warning");
		exit;
	} else {
		$insert = mysqli_query($koneksi, "INSERT INTO kriteria(kode_kriteria, nama_kriteria, jenis_kriteria) VALUES('$kode', '$nama', '$jenis')");
		if ($insert) {
			header("Location: kriteria.php?validasi=sukses-tambah");
			exit;
		} else {
			header("Location: kriteria.php?validasi=error");
			exit;
		}
	}
}

if (isset($_POST['edit_kriteria'])) {
	$id = htmlspecialchars($_POST['id_kriteria']);
	$kode = htmlspecialchars($_POST['kode_kriteria']);
	$nama = htmlspecialchars($_POST['nama_kriteria']);
	$jenis = htmlspecialchars($_POST['jenis_kriteria']);

	$cek = mysqli_query($koneksi, "SELECT * FROM kriteria WHERE kode_kriteria = '$kode' AND id_kriteria != '$id'");
	if (mysqli_num_rows($cek) > 0) {
		header("Location: kriteria.php?validasi=warning");
		exit;
	}
	
	$update = mysqli_query($koneksi, "UPDATE kriteria SET kode_kriteria = '$kode', nama_kriteria = '$nama', jenis_kriteria = '$jenis' WHERE id_kriteria = '$id'");
	if ($update) {
		header("Location: kriteria.php?validasi=sukses-perbarui");
		exit;
	} else {
		header("Location: kriteria.php?validasi=error");
		exit;
	}
}

if (isset($_POST['tambah_subkriteria'])) {
	$id = htmlspecialchars($_POST['id_kriteria']);
	$nama = htmlspecialchars($_POST['nama_subkriteria']);
	$nilai = htmlspecialchars($_POST['nilai_subkriteria']);

	$insert = mysqli_query($koneksi, "INSERT INTO subkriteria(id_kriteria, nama_subkriteria, nilai_subkriteria) VALUES('$id', '$nama', '$nilai')");
	if ($insert) {
		header("Location: subkriteria.php?validasi=sukses-tambah");
		exit;
	} else {
		header("Location: subkriteria.php?validasi=error");
		exit;
	}
}

if (isset($_POST['edit_subkriteria'])) {
	$id = htmlspecialchars($_POST['id_subkriteria']);
	$nama = htmlspecialchars($_POST['nama_subkriteria']);
	$nilai = htmlspecialchars($_POST['nilai_subkriteria']);

	$update = mysqli_query($koneksi, "UPDATE subkriteria SET nama_subkriteria = '$nama', nilai_subkriteria = '$nilai' WHERE id_subkriteria = '$id'");
	if ($update) {
		header("Location: subkriteria.php?validasi=sukses-perbarui");
		exit;
	} else {
		header("Location: subkriteria.php?validasi=error");
		exit;
	}
}

if (isset($_POST['tambah_varietas'])) {
	$kode = htmlspecialchars($_POST['kode_varietas']);
	$nama = htmlspecialchars($_POST['nama_varietas']);
	$kriteria = $_POST['kriteria'];
	$subkriteria = $_POST['subkriteria'];

	$query = mysqli_query($koneksi, "SELECT kode_varietas FROM varietas WHERE kode_varietas = '$kode'");
	if (mysqli_num_rows($query) > 0) {
		header("Location: varietas.php?validasi=warning");
		exit;
	} else {
		$insert = mysqli_query($koneksi, "INSERT INTO varietas(kode_varietas, nama_varietas) VALUES('$kode', '$nama')");
		if ($insert) {
			$get_id = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_varietas FROM varietas ORDER BY id_varietas DESC LIMIT 1"));
			$id_varietas = $get_id['id_varietas'];
			for ($i = 0; $i < count($kriteria); $i++) {
				$insert = mysqli_query($koneksi, "INSERT INTO matriks(id_varietas, id_kriteria, id_subkriteria) VALUES('$id_varietas', '$kriteria[$i]', '$subkriteria[$i]')");
				if (!$insert) {
					header("Location: varietas.php?validasi=error");
					exit;
				}
			}
			header("Location: varietas.php?validasi=sukses-tambah");
			exit;
		} else {
			header("Location: varietas.php?validasi=error");
			exit;
		}
	}
}

if (isset($_POST['edit_varietas'])) {
	$id = htmlspecialchars($_POST['id_varietas']);
	$kode = htmlspecialchars($_POST['kode_varietas']);
	$nama = htmlspecialchars($_POST['nama_varietas']);
	$kriteria = $_POST['kriteria'];
	$subkriteria = $_POST['subkriteria'];

	$update = mysqli_query($koneksi, "UPDATE varietas SET kode_varietas = '$kode', nama_varietas = '$nama' WHERE id_varietas = '$id'");
	if ($update) {
		$delete = mysqli_query($koneksi, "DELETE FROM matriks WHERE id_varietas = '$id'");
		if ($delete) {
			for ($i = 0; $i < count($kriteria); $i++) {
				$insert = mysqli_query($koneksi, "INSERT INTO matriks(id_varietas, id_kriteria, id_subkriteria) VALUES('$id', '$kriteria[$i]', '$subkriteria[$i]')");
				if (!$insert) {
					header("Location: varietas.php?validasi=error");
					exit;
				}
			}
			header("Location: varietas.php?validasi=sukses-perbarui");
			exit;
		} else {
			header("Location: varietas.php?validasi=error");
			exit;
		}
	} else {
		header("Location: varietas.php?validasi=error");
		exit;
	}
}

if (isset($_POST['hitung'])) {
	$user = $_POST['user'];
	$pilih = isset($_POST['pilih']) ? $_POST['pilih'] : 0;

	if ($pilih == 0 || count($pilih) < 2) {
		header("Location: data_perhitungan.php?validasi=error");
		exit;
	} else {
		$cek = mysqli_query($koneksi, "SELECT * FROM checked WHERE username = '$user'");
		if (mysqli_num_rows($cek) > 0) {
			$delete = mysqli_query($koneksi, "DELETE FROM checked WHERE username = '$user'");
			if ($delete) {
				for ($i = 0; $i < count($pilih); $i++) {
					$insert = mysqli_query($koneksi, "INSERT INTO checked(id_varietas, username) VALUES('$pilih[$i]', '$user')");
					if (!$insert) {
						header("Location: data_perhitungan.php?validasi=error");
						exit;
					}
				}
				header("Location: proses_perhitungan.php?validasi=sukses");
				exit;
			} else {
				header("Location: data_perhitungan.php?validasi=error");
				exit;
			}
		} else {
			for ($i = 0; $i < count($pilih); $i++) {
				$insert = mysqli_query($koneksi, "INSERT INTO checked(id_varietas, username) VALUES('$pilih[$i]', '$user')");
				if (!$insert) {
					header("Location: data_perhitungan.php?validasi=error");
					exit;
				}
			}
			header("Location: proses_perhitungan.php?validasi=sukses");
			exit;
		}
	}
}

if (isset($_POST['edit_profil'])) {
	$id = htmlspecialchars($_POST['id']);
	$nama = htmlspecialchars($_POST['nama']);
	$user = htmlspecialchars($_POST['user']);
	$password = htmlspecialchars($_POST['password']);
	$pass_new = htmlspecialchars($_POST['pass_new']);

	if ($password == "" || $pass_new == "") {
		$update = mysqli_query($koneksi, "UPDATE pengguna SET nama = '$nama' WHERE id_pengguna = '$id'");
		if ($update) {
			header("Location: profil.php?validasi=sukses");
			exit;
		} else {
			header("Location: profil.php?validasi=error");
			exit;
		}
	} else {
		$baris = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id_pengguna = '$id'"));
		if (password_verify($password, $baris['password'])) {
			if ($pass_new) {
				$hash = password_hash($pass_new, PASSWORD_DEFAULT);
				$update = mysqli_query($koneksi, "UPDATE pengguna SET nama = '$nama', password = '$hash' WHERE id_pengguna = '$id'");
				if ($update) {
					header("Location: profil.php?validasi=sukses");
					exit;
				} else {
					header("Location: profil.php?validasi=error");
					exit;
				}
			} else {
				header("Location: profil.php?validasi=error");
				exit;
			}
		} else {
			header("Location: profil.php?validasi=error");
			exit;
		}
	}
}

if (isset($_POST['tambah_pengguna'])) {
	$nama = htmlspecialchars($_POST['nama']);
	$user = htmlspecialchars($_POST['user']);
	$level = htmlspecialchars($_POST['level']);
	$pass = htmlspecialchars($_POST['pass']);
	$konfir = htmlspecialchars($_POST['konfir']);

	if ($pass == $konfir) {
		$query = mysqli_query($koneksi, "SELECT username FROM pengguna WHERE username = '$user'");
		if (mysqli_num_rows($query) > 0) {
			header("Location: pengguna.php?validasi=warning");
			exit;
		} else {
			$hash = password_hash($pass, PASSWORD_DEFAULT);
			$insert = mysqli_query($koneksi, "INSERT INTO pengguna(nama, username, password, level) VALUES('$nama', '$user', '$hash', '$level')");
			if ($insert) {
				header("Location: pengguna.php?validasi=sukses-tambah");
				exit;
			} else {
				header("Location: pengguna.php?validasi=error");
				exit;
			}
		}
	} else {
		header("Location: pengguna.php?validasi=error");
		exit;
	}
}

if (isset($_POST['edit_pengguna'])) {
	$id = htmlspecialchars($_POST['id']);
	$nama = htmlspecialchars($_POST['nama']);
	$level = htmlspecialchars($_POST['level']);

	$update = mysqli_query($koneksi, "UPDATE pengguna SET nama = '$nama', level = '$level' WHERE id_pengguna = '$id'");
	if ($update) {
		header("Location: pengguna.php?validasi=sukses-perbarui");
		exit;
	} else {
		header("Location: pengguna.php?validasi=error");
		exit;
	}
}

if (isset($_POST['lupa_pass'])) {
	$nama = htmlspecialchars($_POST['nama']);
	$user = htmlspecialchars($_POST['user']);

	$verifikasi = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE nama = '$nama' AND username = '$user'");
	if (mysqli_num_rows($verifikasi) > 0) {
		header("Location: ubah_pass.php?validasi=sukses&user=" . $user);
		exit;
	} else {
		header("Location: lupa_pass.php?validasi=error");
		exit;
	}
}

if (isset($_POST['pass_new'])) {
	$user = htmlspecialchars($_POST['user']);
	$pass = htmlspecialchars($_POST['pass']);

	if ($pass) {
		$hash = password_hash($pass, PASSWORD_DEFAULT);
		$update = mysqli_query($koneksi, "UPDATE pengguna SET password = '$hash' WHERE username = '$user'");
		if ($update) {
			echo "
			<script>
				alert('Ubah sandi berhasil');
				document.location.href = 'masuk.php';
			</script>
			";
			exit;
		} else {
			header("Location: ubah_pass.php?validasi=error");
			exit;
		}
	} else {
		header("Location: ubah_pass.php?validasi=error");
		exit;
	}
}
?>