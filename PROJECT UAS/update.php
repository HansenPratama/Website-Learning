<!DOCTYPE html>
<html>
<head>
    <title>Form Data Mahasiswa</title>
    <!-- Load file CSS Bootstrap offline -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <?php
    // Include file koneksi, untuk koneksikan ke database
    include "db_ta.php";

    // Fungsi untuk mencegah inputan karakter yang tidak sesuai
    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Cek apakah ada kiriman form dari method post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nim = input($_POST["nim"]);
        $nama_mhs = input($_POST["nama_mhs"]);
        $kelas = input($_POST["kelas"]);
        $judul_ta = input($_POST["judul_ta"]);
        $tema_ta = input($_POST["tema_ta"]);

        // Query untuk mengecek apakah data sudah ada
        $query_check = "SELECT * FROM mahasiswa WHERE nim='$nim'";
        $result_check = mysqli_query($kon, $query_check);

        if (mysqli_num_rows($result_check) > 0) {
            // Jika data sudah ada, lakukan UPDATE
            $sql = "UPDATE mahasiswa SET nama_mhs='$nama_mhs', kelas='$kelas', judul_ta='$judul_ta', tema_ta='$tema_ta' WHERE nim='$nim'";
        } else {
            // Jika data belum ada, lakukan INSERT
            $sql = "INSERT INTO mahasiswa (nim, nama_mhs, kelas, judul_ta, tema_ta) VALUES ('$nim','$nama_mhs','$kelas','$judul_ta','$tema_ta')";
        }

        // Mengeksekusi/menjalankan query diatas
        $hasil = mysqli_query($kon, $sql);

        // Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($hasil) {
            header("Location:list.php");
        } else {
            echo "<div class='alert alert-danger'> Data Gagal disimpan: " . mysqli_error($kon) . "</div>";
        }
    }

    // Ambil data tema dari tabel tema
    $query_tema = "SELECT * FROM tema";
    $result_tema = mysqli_query($kon, $query_tema);

    if (!$result_tema) {
        die("Query gagal: " . mysqli_error($kon));
    }
    ?>
    <h2>Input Data Mahasiswa</h2>

    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <div class="form-group">
            <label>NIM :</label>
            <input type="text" name="nim" class="form-control" placeholder="Masukan NIM" required />
        </div>
        <div class="form-group">
            <label>Nama Mahasiswa :</label>
            <input type="text" name="nama_mhs" class="form-control" placeholder="Masukan Nama Mahasiswa" required/>
        </div>
        <div class="form-group">
            <label>Kelas :</label>
            <label><input type="radio" name="kelas" value="MI-1A"> MI-1A</label>
            <label><input type="radio" name="kelas" value="MI-1B"> MI-1B</label>
            <label><input type="radio" name="kelas" value="MI-1C"> MI-1C</label>
            <label><input type="radio" name="kelas" value="MI-1D"> MI-1D</label>
            <label><input type="radio" name="kelas" value="MI-1E"> MI-1E</label>
        </div>
        <div class="form-group">
            <label>Judul Tugas Akhir :</label>
            <input type="text" name="judul_ta" class="form-control" placeholder="Masukan Judul Tugas Akhir" required/>
        </div>
        <div class="form-group">
            <label>Tema Tugas Akhir :</label>
            <select name="tema_ta" class="form-control" required>
                <option value="">Pilih Tema</option>
                <?php
                while ($row_tema = mysqli_fetch_assoc($result_tema)) {
                    echo "<option value='".$row_tema['id_tema']."'>".$row_tema['tema_ta']."</option>";
                }
                ?>
            </select>
        </div>
        <br>
        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</body>
</html>
