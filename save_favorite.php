<?php
require_once 'Connection.php';
require_once 'session.php';

if (isset($_POST['recipeId']) && isset($_POST['isFavorite'])) {
    $recipeId = $_POST['recipeId'];
    $isFavorite = $_POST['isFavorite'] === "true";

    if ($isFavorite) {
        $sql = "INSERT INTO favorite (userId, recipeId) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $recipeId);
        $stmt->execute();
    } else {
        $sql = "DELETE FROM favorite WHERE userId = ? AND recipeId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $recipeId);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();
}