<?php
require_once 'Connection.php';
require_once 'session.php';

if ($userId > 0) {
    if (isset($_POST['recipeId']) && !empty($_POST['recipeNote'])) {
        $recipeId = $_POST['recipeId'];
        $note = $_POST['recipeNote'];
        $source = $_POST['source'];

        $sql = "INSERT INTO recipe_notes (userId, recipeId, note) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $userId, $recipeId, $note);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_POST['recipeId'], $_POST['stepNo']) && !empty($_POST['stepNote'])) {
        $recipeId = $_POST['recipeId'];
        $stepNo = $_POST['stepNo'];
        $note = $_POST['stepNote'];
        $source = $_POST['source'];

        $sql = "INSERT INTO step_notes (userId, recipeId, stepNo, note) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $userId, $recipeId, $stepNo, $note);
        $stmt->execute();
        $stmt->close();
    }

    if ($source == "notebook") {
        header("Location: notebook.php?recipeId=" . urlencode($recipeId));
    } else if ($source == "details") {
        header("Location: details.php?recipeId=" . urlencode($recipeId));
    } else if ($source == "steps");
}
