<?php
require_once 'Connection.php';

if (isset($_POST['submit'])) {
    $recipeId = $_POST['recipeId'];
    $no = $_POST['no'];
    $ingreId = $_POST['ingreId'];

    if ($recipeId != 0 && $no != 0 && $ingreId != 0) {
        $sql = "DELETE FROM recipe_ingredient WHERE recipeId = ? AND no = ? AND ingreId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iii', $recipeId, $no, $ingreId);

        if ($stmt->execute()) {
            echo "<script>alert('Action Delete Successfully!')</script>";
            echo "<script>location.href='admin-deleteaction.php';</script>";
        } else {
            echo "<script>alert('Error!')</script>";
            echo "<script>location.href='admin-deleteaction.php';</script>";
        }
    } else {
        echo "<script>alert('Choose the Recipe, Step No and Ingredient you want to delete!')</script>";
        echo "<script>location.href='admin-deleteaction.php';</script>";
    }
}