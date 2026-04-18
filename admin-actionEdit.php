<?php
require_once 'Connection.php';

if (isset($_POST['submit'])) {
    $recipeId = $_POST['recipeId'];
    $no = $_POST['no'];
    $ingreId = $_POST['ingreId'];
    $action = $_POST['action'];
    $quantity = $_POST['quantity'];
    $adjustable = $_POST['adjustable'];

    $sql = "UPDATE recipe_ingredient SET action = ?, quantity = ?, adjustable = ? WHERE recipeId = ? AND no = ? AND ingreId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sdiiii', $action, $quantity, $adjustable, $recipeId, $no, $ingreId);

    if ($stmt->execute()) {
        echo "<script>alert('Action Edit Successfully!')</script>";
        echo "<script>location.href='admin-editaction.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "<script>alert('Failed to edit!')</script>";
    echo "<script>location.href='admin-editaction.php';</script>";
}