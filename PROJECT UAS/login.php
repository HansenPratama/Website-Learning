<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
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
                    <a href="home.html">Home</a>
                </div>
            </li>
        </ul>
    </div>
    <div class="login-container">
        <h2>Login</h2>
        <?php
        session_start();
        include 'db_user.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Ambil data user dari database berdasarkan username
            $stmt = $kon->prepare("SELECT id, username, password FROM user_db WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                // Verifikasi password
                if ($password == $row['password']) {
                    // Login berhasil, buat sesi login dan redirect ke list.php
                    $_SESSION['user_id'] = $row['id'];
                    header("Location: list.php");
                    exit();
                } else {
                    $error_message = "Password salah";
                }
            } else {
                $error_message = "Username tidak ditemukan";
            }

            $stmt->close();
            $kon->close();
        }
        ?>
        <?php if(isset($error_message)) { ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>
        <form class="login-form" method="post" action="login.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="checkbox" id="showPassword" onclick="togglePassword()"> Show Password
            <input type="submit" value="Login">
        </form>
        <div class="register-link">
            Doesn't have an account? <a href="register.php">Register now!</a>
        </div>
    </div>
    <script>
        function togglePassword() {
            var passwordField = document.getElementById('password');
            var showPasswordCheckbox = document.getElementById('showPassword');
            if (showPasswordCheckbox.checked) {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        }
        
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
