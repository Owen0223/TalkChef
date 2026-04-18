<!DOCTYPE html>
<html>
<?php
require_once 'Connection.php';

$recipeId = isset($_POST['recipeId']) ? $_POST['recipeId'] : 0;
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <title>Edit Recipe</title>
</head>

<body>
    <div class="back-button">
        <a href="admin-recipe.html">
            <img src="../TalkChef/image/left.png" alt="Back" class="back-image">
        </a>
    </div>
    <h1>Edit Recipe</h1>

    <div class="view-position">
        <div class="add-container">
            <form action="admin-editrecipe.php" method="POST">
                <table>
                    <tr>
                        <td><label for="recipeId">Recipe:</label></td>
                        <td><select id="recipeId" name="recipeId">
                                <option value="0">Choose a Recipe</option>
                                <?php
                                $recipe = "SELECT * FROM recipe";
                                $allrecipe = mysqli_query($conn, $recipe);

                                if (mysqli_num_rows($allrecipe) > 0) {
                                    while ($fetch_recipe = mysqli_fetch_assoc($allrecipe)) {
                                ?>
                                <option value="<?php echo $fetch_recipe['recipeId']; ?>">
                                    <?php echo $fetch_recipe['recipeId'] . ". " . $fetch_recipe['recipeName']; ?>
                                </option>
                                <?php
                                    }
                                }
                                ?>
                            </select></td>
                        <td><input type="submit" value="Search"></td>
                    </tr>
                </table>
            </form>
        </div>

        <?php
        if ($recipeId != 0) {
            $sql = "SELECT * FROM recipe WHERE recipeId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $recipeId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $fetch_recipe = mysqli_fetch_assoc($result);
        ?>
        <div class="add-container">
            <form action="admin-recipeEdit.php" method="post" enctype="multipart/form-data">
                <table>
                    <tr>
                        <td><label for="recipeName">Recipe Name:</label></td>
                        <td><input type="text" name="recipeName" id="recipeName"
                                value="<?php echo $fetch_recipe['recipeName']; ?>"></td>
                    </tr>
                    <tr>
                        <td><label for="recipeCatId">Recipe Category:</label></td>
                        <td><select id="recipeCatId" name="recipeCatId">
                                <option value="0">Choose</option>
                                <option value="1" <?php if ($fetch_recipe['recipeCatId'] == 1) echo 'selected'; ?>>Malay
                                </option>
                                <option value="2" <?php if ($fetch_recipe['recipeCatId'] == 2) echo 'selected'; ?>>
                                    Chinese</option>
                                <option value="3" <?php if ($fetch_recipe['recipeCatId'] == 3) echo 'selected'; ?>>
                                    Indian</option>
                                <option value="4" <?php if ($fetch_recipe['recipeCatId'] == 4) echo 'selected'; ?>>
                                    Western</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td><label for="image">Recipe Image:</label></td>
                        <td><input type="file" name="image" id="image" accept="image/*"></td>
                    </tr>
                    <tr>
                        <td><label for="recipeDesc">Recipe Description:</label></td>
                        <td><textarea name="recipeDesc" id="recipeDesc" rows="6"
                                cols="50"><?php echo $fetch_recipe['recipeDesc']; ?></textarea></td>
                    </tr>
                    <tr>
                        <td><label for="estTime">Estimated Time (minutes):</label></td>
                        <td><input type="text" name="estTime" id="estTime"
                                value="<?php echo $fetch_recipe['estTime']; ?>"></td>
                    </tr>
                    <tr>
                        <td><input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>"></td>
                        <td colspan="2"><input type="submit" name="submit" value="Edit Recipe"></td>
                    </tr>

                </table>
            </form>
        </div>
        <?php
            }
        }
        ?>
    </div>
</body>

</html>