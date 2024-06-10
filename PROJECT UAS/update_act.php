<?php
include("db_ta.php");

if (isset($_POST['simpan'])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $judul_ta = $_POST['judul_ta'];

    $sql = "UPDATE mahasiswa SET nama_mhs='$nama', kelas='$kelas', judul_ta='$judul_ta' WHERE nim='$nim'";
    $query = mysqli_query($kon, $sql);

    if ($query) {
        header('Location: list.php');
    } else {
        die("Update gagal...");
    }
} else {
    die("Akses dilarang...");
}
?>
