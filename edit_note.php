<?php
require_once 'Connection.php';
require_once 'session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipeId = $_POST['recipeId'];
    $noteId = $_POST['noteId'];
    $newNote = $_POST['recipeNote'];
    $source = $_POST['source'];

    $stepNo = $_POST['stepNo'] ?? null;

    if ($stepNo !== null) {
        $sql = "UPDATE step_notes SET note = ? WHERE id = ? AND userId = ?";
    } else {
        $sql = "UPDATE recipe_notes SET note = ? WHERE id = ? AND userId = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $newNote, $noteId, $userId);

    if ($stmt->execute()) {
        if ($source == "notebook") {
            header("Location: notebook.php?recipeId=" . urlencode($recipeId));
        } else if ($source == "details") {
            header("Location: details.php?recipeId=" . urlencode($recipeId));
        } else if ($source == "steps");
    } else {
        echo "Error updating note.";
    }
} else {
    echo "Invalid request method.";
}