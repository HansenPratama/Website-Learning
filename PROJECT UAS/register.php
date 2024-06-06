<?php
include 'db_user.php';

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($password)) {
        $error_message = "Username and password are required.";
    } else {
        // Check if username already exists
        $stmt = $kon->prepare("SELECT username FROM user_db WHERE username = ?");
        if ($stmt === false) {
            error_log('Prepare failed: ' . htmlspecialchars($kon->error));
            $error_message = "An error occurred. Please try again later.";
        } else {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error_message = "Username already exists. Please choose a different username.";
            } else {
                // Prepare and bind
                $stmt = $kon->prepare("INSERT INTO user_db (username, password) VALUES (?, ?)");
                if ($stmt === false) {
                    error_log('Prepare failed: ' . htmlspecialchars($kon->error));
                    $error_message = "An error occurred. Please try again later.";
                } else {
                    $stmt->bind_param("ss", $username, $password);

                    if ($stmt->execute()) {
                        // If registration is successful, redirect to home.html
                        header("Location: home.html", true, 303);
                        exit();
                    } else {
                        error_log('Execute failed: ' . htmlspecialchars($stmt->error));
                        $error_message = "An error occurred. Please try again later.";
                    }
                }
            }
            $stmt->close();
        }
    }

    $kon->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Register</title>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var errorMessage = "<?php echo $error_message; ?>";
            if (errorMessage) {
                alert(errorMessage);
            }
        });
    </script>
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
    <div class="container">
        <h2>Register</h2>
        <form method="post" action="register.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <input type="checkbox" id="showPassword" onclick="togglePassword()"> Show Password
            </div>
            <button type="submit">Register</button>
        </form>
        <div class="login-link">
            <span>Already registered? <a href="login.php">Login here</a></span>
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
