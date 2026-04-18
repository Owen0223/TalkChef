<?php
require_once 'Connection.php';

if (isset($_POST['submit'])) {
    $recipeId = $_POST['recipeId'];
    $no = $_POST['no'];

    if ($recipeId != 0 && $no != 0) {
        $sql = "DELETE FROM step WHERE recipeId = ? AND no = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $recipeId, $no);

        if ($stmt->execute()) {
            echo "<script>alert('Step Delete Successfully!')</script>";
            echo "<script>location.href='admin-deleteinstruct.php';</script>";
        } else {
            echo "<script>alert('Error!')</script>";
            echo "<script>location.href='admin-deleteinstruct.php';</script>";
        }
    } else {
        echo "<script>alert('Choose the Recipe and Step No you want to delete!')</script>";
        echo "<script>location.href='admin-deleteinstruct.php';</script>";
    }
}