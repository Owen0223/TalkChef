<?php
require_once 'Connection.php';
require_once 'session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipeId = $_POST['recipeId'];
    $noteId = $_POST['noteId'];
    $source = $_POST['source'];

    $stepNo = $_POST['stepNo'] ?? null;

    if ($stepNo !== null) {
        $sql = "DELETE FROM step_notes WHERE id = ? AND userId = ?";
    } else {
        $sql = "DELETE FROM recipe_notes WHERE id = ? AND userId = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $noteId, $userId);

    if ($stmt->execute()) {
        if ($source == "notebook") {
            header("Location: notebook.php?recipeId=" . urlencode($recipeId));
        } else if ($source == "details") {
            header("Location: details.php?recipeId=" . urlencode($recipeId));
        } else if ($source == "steps");
    } else {
        echo "Error deleting note.";
    }
} else {
    echo "Invalid request method.";
}
