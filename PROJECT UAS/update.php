<?php
include "db_ta.php";

function input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function get_student_data($kon, $nim) {
    $query = "SELECT mahasiswa.*, tema.tema_ta AS tema_name FROM mahasiswa JOIN tema ON mahasiswa.tema_ta = tema.id_tema WHERE nim = ?";
    $stmt = mysqli_prepare($kon, $query);
    mysqli_stmt_bind_param($stmt, 's', $nim);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $data;
}

function get_themes($kon) {
    $query = "SELECT * FROM tema";
    $result = mysqli_query($kon, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function update_student($kon, $nim, $new_nim, $nama_mhs, $kelas, $judul_ta, $tema_ta) {
    // Check for duplicate NIM
    if ($nim !== $new_nim) {
        $duplicate_check_query = "SELECT * FROM mahasiswa WHERE nim = ?";
        $stmt = mysqli_prepare($kon, $duplicate_check_query);
        mysqli_stmt_bind_param($stmt, 's', $new_nim);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            mysqli_stmt_close($stmt);
            return "duplicate";
        }
        mysqli_stmt_close($stmt);
    }

    $query = "UPDATE mahasiswa SET nim = ?, nama_mhs = ?, kelas = ?, judul_ta = ?, tema_ta = ? WHERE nim = ?";
    $stmt = mysqli_prepare($kon, $query);
    mysqli_stmt_bind_param($stmt, 'ssssss', $new_nim, $nama_mhs, $kelas, $judul_ta, $tema_ta, $nim);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result ? "success" : "error";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = input($_POST["nim"]);
    $new_nim = input($_POST["new_nim"]);
    $nama_mhs = input($_POST["nama_mhs"]);
    $kelas = input($_POST["kelas"]);
    $judul_ta = input($_POST["judul_ta"]);
    $tema_ta = input($_POST["tema_ta"]);

    // Validate NIM and new NIM as integers and not negative
    if (!filter_var($new_nim, FILTER_VALIDATE_INT) || $new_nim < 0) {
        echo "<script>alert('NIM harus berupa angka bulat positif.'); window.history.back();</script>";
    } else if (empty($new_nim) || empty($nama_mhs) || empty($kelas) || empty($judul_ta) || empty($tema_ta)) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
    } else {
        $update_result = update_student($kon, $nim, $new_nim, $nama_mhs, $kelas, $judul_ta, $tema_ta);
        if ($update_result === "success") {
            header("Location: list.php");
            exit();
        } elseif ($update_result === "duplicate") {
            echo "<script>alert('Data already exists!'); window.history.back();</script>";
        } else {
            echo "<script>alert('Error updating record: " . mysqli_error($kon) . "'); window.history.back();</script>";
        }
    }
}

if (isset($_GET["nim"])) {
    $nim = input($_GET["nim"]);
    $data = get_student_data($kon, $nim);

    if (!$data) {
        echo "Student not found!";
        exit();
    }
} else {
    echo "NIM parameter is missing!";
    exit();
}

$themes = get_themes($kon);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Mahasiswa</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Update Mahasiswa</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="nim" value="<?php echo htmlspecialchars($nim); ?>">
            <div class="form-group">
                <label for="new_nim">NIM:</label>
                <input type="number" class="form-control" id="new_nim" name="new_nim" value="<?php echo htmlspecialchars($data['nim']); ?>" required step="1">
            </div>
            <div class="form-group">
                <label for="nama_mhs">Nama Mahasiswa:</label>
                <input type="text" class="form-control" id="nama_mhs" name="nama_mhs" value="<?php echo htmlspecialchars($data['nama_mhs']); ?>" required>
            </div>
            <div class="form-group">
                <label for="kelas">Kelas:</label>
                <input type="text" class="form-control" id="kelas" name="kelas" value="<?php echo htmlspecialchars($data['kelas']); ?>" required>
            </div>
            <div class="form-group">
                <label for="judul_ta">Judul Tugas Akhir:</label>
                <input type="text" class="form-control" id="judul_ta" name="judul_ta" value="<?php echo htmlspecialchars($data['judul_ta']); ?>" required>
            </div>
            <div class="form-group">
                <label for="tema_ta">Tema Tugas Akhir:</label>
                <select class="form-control" id="tema_ta" name="tema_ta" required>
                    <?php foreach ($themes as $theme): ?>
                        <option value="<?php echo htmlspecialchars($theme['id_tema']); ?>" <?php if ($theme['id_tema'] == $data['tema_ta']) echo "selected"; ?>><?php echo htmlspecialchars($theme['tema_ta']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>
