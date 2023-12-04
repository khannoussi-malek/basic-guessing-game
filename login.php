<?php
session_start();
require "connection.php";
session_destroy();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);

        $stmt->execute();

        $stmt->bind_result($userId, $dbUsername, $dbPassword);

        $stmt->fetch();
        if ( password_verify($password, $dbPassword)) {
            $_SESSION["username"] = $username;
            $_SESSION["userId"] = $userId;

            header("Location: game.php");
        } else {
            echo "Invalid username or password. <a href='login.html'>Login</a>";
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

$conn->close();
?>
