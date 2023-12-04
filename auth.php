<?php
session_start();

include 'connection.php';

function authenticateUser($username, $password) {
    global $conn;

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $hashedPassword = $user['password'];

        if (password_verify($password, $hashedPassword)) {
            return true;
        }
    }

    return false;
}


function getGameHistory($username) {
    global $conn;

    $sql = "SELECT * FROM game_history WHERE user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function saveGameResult($username, $tryNumber, $randomNumber) {
    global $conn;

    $sql = "INSERT INTO game_history (user, tryNumber, randomNumber, date) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sid", $username, $tryNumber, $randomNumber);
    $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (authenticateUser($username, $password)) {
        $_SESSION['username'] = $username;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $gameHistory = getGameHistory($username);
    echo json_encode(['success' => true, 'gameHistory' => $gameHistory]);
}
?>
