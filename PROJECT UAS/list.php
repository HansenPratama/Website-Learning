<!DOCTYPE html>
<html>
<head>
    <!-- Load file CSS Bootstrap offline -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="list.css">
</head>
<body>
<div class="navbar">
        <ul class="navbar-menu">
            <li>
                <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="dropdown-content" id="dropdownContent">
                    <a href="landing.html">Home</a>
                </div>
            </li>
        </ul>
    </div>
    <div class="container">
        <br>
        <h4>Daftar Mahasiswa</h4>
        <br>
        <a href="create.php" class="btn btn-primary" role="button">Tambah Data</a>
        <br><br>
        <form action="search.php" method="post">
            <label for="search">Search:</label>
            <input type="text" id="search" name="search_term">
            <button type="submit">Search</button>
        </form>
        <br>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>    
                    <th>No</th> 
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Kelas</th>
                    <th>Judul Tugas Akhir</th>
                    <th>Tema Tugas Akhir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include "db_ta.php";

                $batas = 5;
                $halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
                $halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

                $previous = $halaman - 1;
                $next = $halaman + 1;

                // Corrected SQL query with JOIN
                $sql = mysqli_query($kon, "SELECT mahasiswa.nim, mahasiswa.nama_mhs, mahasiswa.kelas, mahasiswa.judul_ta, tema.tema_ta AS tema_ta 
                                           FROM mahasiswa 
                                           JOIN tema ON mahasiswa.tema_ta = id_tema 
                                           ORDER BY mahasiswa.nim ASC");
                $jumlah_data = mysqli_num_rows($sql);
                $total_halaman = ceil($jumlah_data / $batas);

                $hasil = mysqli_query($kon, "SELECT mahasiswa.nim, mahasiswa.nama_mhs, mahasiswa.kelas, mahasiswa.judul_ta, tema.tema_ta AS tema_ta 
                                             FROM mahasiswa 
                                             JOIN tema ON mahasiswa.tema_ta = id_tema
                                             LIMIT $halaman_awal, $batas");
                $no = $halaman_awal + 1;
                while ($data = mysqli_fetch_array($hasil)) {
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($data["nim"]); ?></td>
                    <td><?php echo htmlspecialchars($data["nama_mhs"]); ?></td>
                    <td><?php echo htmlspecialchars($data["kelas"]); ?></td>
                    <td><?php echo htmlspecialchars($data["judul_ta"]); ?></td>
                    <td><?php echo htmlspecialchars($data["tema_ta"]); ?></td>
                    <td>
                        <a href="update.php?nim=<?php echo urlencode($data['nim']); ?>" class="btn btn-warning" role="button">Update</a>
                        <a href="delete.php?id=<?php echo htmlspecialchars($data['nim']); ?>" class="btn btn-danger" role="button">Delete</a>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
                   
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item">
                    <a class="page-link" <?php if($halaman > 1){ echo "href='?halaman=$previous'"; } ?>>Previous</a>
                </li>
                <?php
                for($x = 1; $x <= $total_halaman; $x++){
                ?>
                <li class="page-item"><a class="page-link" href="?halaman=<?php echo $x; ?>"><?php echo $x; ?></a></li>
                <?php
                }
                ?>
                <li class="page-item">
                    <a class="page-link" <?php if($halaman < $total_halaman) { echo "href='?halaman=$next'"; } ?>>Next</a>
                </li>
            </ul>
        </nav>
    </div>
    <script>
    // JavaScript function for toggling the dropdown menu
    document.getElementById('hamburger').addEventListener('click', function() {
        var dropdownContent = document.getElementById('dropdownContent');
        if (dropdownContent.style.display === 'block') {
            dropdownContent.style.display = 'none';
        } else {
            dropdownContent.style.display = 'block';
        }
    });
    </script>
</body>
</html>

