<?php
include 'data.php';
 
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM admin WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row['password'])) {
        $_SESSION['admin'] = [
            'admin_id' => $row['admin_id'],
            'username' => $row['username']
        ];
        header("Location: ../view/dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Password salah!";
        header("Location: ../view/login.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Admin tidak ditemukan!";
    header("Location: ../view/login.php");
    exit();
}

$conn->close();
?>
