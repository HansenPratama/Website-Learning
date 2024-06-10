<?php
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logout</title>
    <script>
        // JavaScript alert after logout
        window.onload = function() {
            alert("Anda baru saja logout");
            // Redirect to login page or any other page after showing the alert
            window.location.href = "home.html";
        };
    </script>
</head>
<body>
</body>
</html>
