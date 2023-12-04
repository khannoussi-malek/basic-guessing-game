<?php
require "connection.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $checkUsernameQuery = "SELECT id FROM users WHERE username = ?";
    
    if ($stmtCheck = $conn->prepare($checkUsernameQuery)) {
        $stmtCheck->bind_param("s", $username);

        $stmtCheck->execute();

        $stmtCheck->store_result();

        if ($stmtCheck->num_rows > 0) {
            echo "Username already exists. Please choose a different username.";
            $stmtCheck->close();
            $conn->close();
            exit();
        }

        $stmtCheck->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
        $conn->close();
        exit();
    }

    $insertUserQuery = "INSERT INTO users (username, password) VALUES (?, ?)";
    
    if ($stmt = $conn->prepare($insertUserQuery)) {
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            echo "User registered successfully. <a href='login.html'>Login</a>";
        } else {
            echo "Error executing query: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

$conn->close();
?>
