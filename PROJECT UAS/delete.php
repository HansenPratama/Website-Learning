<?php
include("db_ta.php");

 if (isset($_GET['id'])) {
        $nim=htmlspecialchars($_GET["id"]);

        $sql="delete from mahasiswa where nim='$nim' ";
        $hasil=mysqli_query($kon,$sql);

        //Kondisi apakah berhasil atau tidak
            if ($hasil) {
                header("Location: list.php");
            }
            else {
                echo "<div class='alert alert-danger'> Data Gagal dihapus .</div>";

            }
        }
?>