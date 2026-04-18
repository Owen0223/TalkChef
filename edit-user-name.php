<?php
require_once 'Connection.php';
require_once 'session.php';

if (isset($_POST['name']) && $_POST['name'] != "") {
    $editName = $_POST['name'];

    $sql = "UPDATE user SET userName = ? WHERE userId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $editName, $userId);
    if ($stmt->execute()) {
        echo "<script>alert('Name Edit Successfully!')</script>";
        echo "<script>location.href='user.php';</script>";
        $_SESSION['name'] = $editName;
    }
} else {
    echo "<script>location.href='user.php';</script>";
}