<?php
require_once 'Connection.php';

if (isset($_POST['submit'])) {
    $recipeId = $_POST['recipeId'];

    $sql = "DELETE FROM recipe WHERE recipeId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $recipeId);

    $sql2 = "DELETE FROM step WHERE recipeId = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('i', $recipeId);

    $sql3 = "DELETE FROM recipe_ingredient WHERE recipeId = ?";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param('i', $recipeId);

    if ($stmt->execute() && $stmt2->execute() && $stmt3->execute()) {
        echo "<script>alert('Recipe Delete Successfully!')</script>";
        echo "<script>location.href='admin-deleterecipe.php';</script>";
    } else {
        echo "<script>alert('Error!')</script>";
        echo "<script>location.href='admin-deleterecipe.php';</script>";
    }
}