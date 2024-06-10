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
                <a href="list.php">Back</a>
            </div>
        </li>
    </ul>
</div>

<div class="container">
    <?php
    // Replace with your database credentials
    $server = "localhost";
    $user = "root";
    $password = "root";
    $database = "ta";

    // Create connection
    $kon = new mysqli($server, $user, $password, $database);

    // Check connection
    if ($kon->connect_error) {
        die("Connection failed: " . $kon->connect_error);
    }

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get search term from form submission
        $searchTerm = $_POST["search_term"];

        if (empty($searchTerm)) {
            echo "<div class='alert alert-warning' role='alert'>No results found.</div>";
        } else {
            // Prepare and bind
            $stmt = $kon->prepare("SELECT mahasiswa.nim, mahasiswa.nama_mhs, mahasiswa.kelas, mahasiswa.judul_ta, tema.tema_ta AS tema_ta 
                                   FROM mahasiswa 
                                   JOIN tema ON mahasiswa.tema_ta = id_tema 
                                   WHERE nama_mhs LIKE ?");
            $likeTerm = "%" . $searchTerm . "%";
            $stmt->bind_param("s", $likeTerm);

            // Execute the statement
            $stmt->execute();

            // Get the result
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<h2>Search Results</h2>";
                echo "<table class='table table-striped'>";
                echo "<thead><tr><th>NIM</th><th>Nama Mahasiswa</th><th>Kelas</th><th>Judul TA</th><th>Tema TA</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["nim"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["nama_mhs"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["kelas"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["judul_ta"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["tema_ta"]) . "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<div class='alert alert-warning' role='alert'>No results found.</div>";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $kon->close();
    ?>
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
