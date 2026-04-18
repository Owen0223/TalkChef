<?php
require_once 'Connection.php';

if (isset($_POST['submit'])) {
    $recipeId = $_POST['recipeId'];
    $no = $_POST['no'];
    $preInstruction = isset($_POST['preInstruction']) ? $_POST['preInstruction'] : null;
    $postInstruction = isset($_POST['postInstruction']) ? $_POST['postInstruction'] : null;
    $highlight = isset($_POST['highlight']) ? $_POST['highlight'] : null;
    $duration = isset($_POST['duration']) ? $_POST['duration'] : 0;

    $sql = "UPDATE step SET preInstruction = ?, postInstruction = ?, highlight = ?, duration = ? WHERE recipeId = ? AND no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssdii', $preInstruction, $postInstruction, $highlight, $duration, $recipeId, $no);

    if ($stmt->execute()) {
        echo "<script>alert('Step Edit Successfully!')</script>";
        echo "<script>location.href='admin-editinstruct.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "<script>alert('Failed to edit!')</script>";
    echo "<script>location.href='admin-editinstruct.php';</script>";
}