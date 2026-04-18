<?php
require_once 'Connection.php';

if (isset($_POST['submit'])) {
    $recipeName = $_POST['recipeName'];
    $recipeCatId = $_POST['recipeCatId'];
    $recipeImage = '';
    $recipeDesc = $_POST['recipeDesc'];
    $estTime = $_POST['estTime'];
    $recipeId = $_POST['recipeId'];

    if (!empty($_FILES["image"]["name"])) {
        $recipeImage = basename($_FILES["image"]["name"]);
        $targetFile = "../TalkChef/image/" . $recipeImage;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        $allowedFormats = ["jpg", "jpeg", "png"];
        if ($check === false) {
            echo "<script>alert('File is not an Image!')</script>";
            echo "<script>location.href='admin-editrecipe.php';</script>";
        } else if ($_FILES["image"]["size"] > 5000000) {
            echo "<script>alert('The File is too large!')</script>";
            echo "<script>location.href='admin-editrecipe.php';</script>";
        } else if (!in_array($imageFileType, $allowedFormats)) {
            echo "<script>alert('Only jpg, jpeg and png is allowed!')</script>";
            echo "<script>location.href='admin-editrecipe.php';</script>";
        } else {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                echo "<script>alert('There is an error uploading the image!')</script>";
                echo "<script>location.href='admin-editrecipe.php';</script>";
            }
        }
    } else {
        $sql = "SELECT recipeImage FROM recipe WHERE recipeId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $recipeId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $recipeImage = $row['recipeImage'];
        }
    }

    if (!empty($recipeName) && $recipeCatId != 0 && !empty($recipeDesc) && !empty($estTime) && !empty($recipeId)) {
        $sql = "UPDATE recipe SET recipeName = ?, recipeCatId = ?, recipeImage = ?, recipeDesc = ?, estTime = ? WHERE recipeId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sissii', $recipeName, $recipeCatId, $recipeImage, $recipeDesc, $estTime, $recipeId);

        if ($stmt->execute()) {
            echo "<script>alert('Recipe Edit Successfully!')</script>";
            echo "<script>location.href='admin-editrecipe.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "<script>alert('No Empty Text!')</script>";
        echo "<script>location.href='admin-editrecipe.php';</script>";
    }
}