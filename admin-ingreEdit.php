<?php
require_once 'Connection.php';

if (isset($_POST['submit'])) {
    $ingreId = $_POST['ingreId'];
    $ingreName = $_POST['ingreName'];
    $type = $_POST['type'];
    $unit = $_POST['unit'];
    $protein = $_POST['protein'];
    $carbs = $_POST['carbs'];
    $fat = $_POST['fat'];
    $calories = $_POST['calories'];

    if (!empty($ingreName) && $type != 0 && !empty($ingreId)) {
        if (empty($unit)) {
            $unit = '';
        }
        if (empty($protein)) {
            $protein = 0;
        }
        if (empty($carbs)) {
            $carbs = 0;
        }
        if (empty($fat)) {
            $fat = 0;
        }
        if (empty($calories)) {
            $calories = 0;
        }

        $sql = "UPDATE ingredient SET ingreName = ?, type = ?, unit = ?, protein = ?, carbs = ?, fat = ?, calories = ? WHERE ingreId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssdddii', $ingreName, $type, $unit, $protein, $carbs, $fat, $calories, $ingreId);

        if ($stmt->execute()) {
            echo "<script>alert('Ingredient Edit Successfully!')</script>";
            echo "<script>location.href='admin-editingre.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "<script>alert('No Empty Text!')</script>";
        echo "<script>location.href='admin-editingre.php';</script>";
    }
}