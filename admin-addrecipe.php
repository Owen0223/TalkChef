<!DOCTYPE html>
<html>
<?php
require_once 'Connection.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <title>Add Recipe</title>
</head>

<body>
    <div class="back-button">
        <a href="admin-recipe.html">
            <img src="../TalkChef/image/left.png" alt="Back" class="back-image">
        </a>
    </div>
    <h1>Add Recipe</h1>

    <div class="view-position">
        <div class="add-container">
            <form action="admin-addrecipe.php" method="post" enctype="multipart/form-data">
                <table>
                    <tr>
                        <td><label for="recipeName">Recipe Name:</label></td>
                        <td><input type="text" name="recipeName" id="recipeName"></td>
                    </tr>
                    <tr>
                        <td><label for="recipeCatId">Recipe Category:</label></td>
                        <td><select id="recipeCatId" name="recipeCatId">
                                <option value="0">Choose</option>
                                <option value="1">Malay</option>
                                <option value="2">Chinese</option>
                                <option value="3">Indian</option>
                                <option value="4">Western</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td><label for="image">Recipe Image:</label></td>
                        <td><input type="file" name="image" id="image" accept="image/*"></td>
                    </tr>
                    <tr>
                        <td><label for="recipeDesc">Recipe Description:</label></td>
                        <td><textarea name="recipeDesc" id="recipeDesc" rows="6" cols="50"></textarea></td>
                    </tr>
                    <tr>
                        <td><label for="estTime">Estimated Time:</label></td>
                        <td><input type="text" name="estTime" id="estTime"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="submit" name="submit" value="Add Recipe"></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</body>

</html>

<?php
if (isset($_POST['submit'])) {
    $recipeName = $_POST['recipeName'];
    $recipeCatId = $_POST['recipeCatId'];
    $recipeImage = basename($_FILES["image"]["name"]);
    $targetFile = "../TalkChef/image/" . $recipeImage;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $recipeDesc = $_POST['recipeDesc'];
    $estTime = $_POST['estTime'];

    if (!empty($recipeName) && $recipeCatId != 0 && !empty($recipeImage) && !empty($recipeImage) && !empty($recipeDesc) && !empty($estTime)) {

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        $allowedFormats = ["jpg", "jpeg", "png"];
        if ($check === false) {
            echo "<script>alert('File is not an Image!')</script>";
            echo "<script>location.href='admin-addrecipe.php';</script>";
        } else if ($_FILES["image"]["size"] > 5000000) {
            echo "<script>alert('The File is too large!')</script>";
            echo "<script>location.href='admin-addrecipe.php';</script>";
        } else if (!in_array($imageFileType, $allowedFormats)) {
            echo "<script>alert('Only jpg, jpeg and png is allowed!')</script>";
            echo "<script>location.href='admin-addrecipe.php';</script>";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $sql = "INSERT INTO recipe (recipeName, recipeCatId, recipeImage, recipeDesc, estTime) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sissi', $recipeName, $recipeCatId, $recipeImage, $recipeDesc, $estTime);

                if ($stmt->execute()) {
                    echo "<script>alert('Recipe Added Successfully!')</script>";
                    echo "<script>location.href='admin-addrecipe.php';</script>";
                } else {
                    echo "Error: " . $stmt->error;
                }
            } else {
                echo "There was an error uploading your file.";
            }
        }
    } else {
        echo "<script>alert('Please fill up all the information!')</script>";
        echo "<script>location.href='admin-addrecipe.php';</script>";
    }
}
?>