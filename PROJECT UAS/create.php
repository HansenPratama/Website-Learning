<!DOCTYPE html>
<html>
<head>
    <title>Form Data Mahasiswa</title>
    <!-- Load file CSS Bootstrap offline -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script>
        function showAlert(message) {
            alert(message);
            window.location.href = 'create.php';
        }

        function validateNIM() {
            var nim = document.getElementById('nim').value;
            if (nim < 0) {
                alert("NIM tidak boleh negatif");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<div class="container">
    <?php
    // Include file koneksi, untuk koneksikan ke database
    include "db_ta.php";

    // Fungsi untuk mencegah inputan karakter yang tidak sesuai
    function input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Cek apakah ada kiriman form dari method post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nim = input($_POST["nim"]);
        $nama_mhs = input($_POST["nama_mhs"]);
        $kelas = input($_POST["kelas"]);
        $judul_ta = input($_POST["judul_ta"]);
        $tema_ta = input($_POST["tema_ta"]);

        // Validate NIM as an integer
        if (!filter_var($nim, FILTER_VALIDATE_INT) || $nim < 0) {
            echo "<script>showAlert('NIM harus berupa angka bulat positif.');</script>";
        } else {
            // Check for duplicate NIM
            $check_sql = "SELECT * FROM mahasiswa WHERE nim = ?";
            if ($stmt_check = mysqli_prepare($kon, $check_sql)) {
                mysqli_stmt_bind_param($stmt_check, "s", $nim);
                mysqli_stmt_execute($stmt_check);
                mysqli_stmt_store_result($stmt_check);
                
                if (mysqli_stmt_num_rows($stmt_check) > 0) {
                    echo "<script>showAlert('Data already exists');</script>";
                    mysqli_stmt_close($stmt_check);
                } else {
                    mysqli_stmt_close($stmt_check);

                    // Query input menginput data kedalam tabel mahasiswa
                    $sql = "INSERT INTO mahasiswa (nim, nama_mhs, kelas, judul_ta, tema_ta) VALUES (?, ?, ?, ?, ?)";

                    // Prepare statement
                    if ($stmt = mysqli_prepare($kon, $sql)) {
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "sssss", $nim, $nama_mhs, $kelas, $judul_ta, $tema_ta);

                        // Attempt to execute the prepared statement
                        if (mysqli_stmt_execute($stmt)) {
                            header("Location:list.php");
                            exit();
                        } else {
                            echo "<div class='alert alert-danger'> Data Gagal disimpan: " . mysqli_error($kon) . "</div>";
                        }

                        // Close statement
                        mysqli_stmt_close($stmt);
                    } else {
                        echo "<div class='alert alert-danger'> Data Gagal disimpan: " . mysqli_error($kon) . "</div>";
                    }
                }
            } else {
                echo "<div class='alert alert-danger'> Data Gagal disimpan: " . mysqli_error($kon) . "</div>";
            }
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

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateNIM()">
        <div class="form-group">
            <label>NIM :</label>
            <input type="number" id="nim" name="nim" class="form-control" placeholder="Masukan NIM" required step="1" />
        </div>
        <div class="form-group">
            <label>Nama Mahasiswa :</label>
            <input type="text" name="nama_mhs" class="form-control" placeholder="Masukan Nama Mahasiswa" required/>
        </div>
        <div class="form-group">
            <label>Kelas :</label>
            <input type="text" name="kelas" class="form-control" placeholder="Masukan Kelas" required/>
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
