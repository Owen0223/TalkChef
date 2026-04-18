<!DOCTYPE html>
<html>
<?php
require_once 'Connection.php';

$recipeId = isset($_POST['recipeId']) ? $_POST['recipeId'] : 0;
?>

<script>
function Confirm() {
    document.getElementById("confirm").style.display = "block";
}

function NoDelete() {
    document.getElementById("confirm").style.display = "none";
}
</script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <title>Delete Recipe</title>
</head>

<body>
    <div class="back-button">
        <a href="admin-recipe.html">
            <img src="../TalkChef/image/left.png" alt="Back" class="back-image">
        </a>
    </div>
    <h1>Delete Recipe</h1>

    <div class="view-position">
        <div class="add-container">
            <form action="admin-deleterecipe.php" method="POST">
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
            <table>
                <tr>
                    <td>Recipe Name:</td>
                    <td><?php echo $fetch_recipe['recipeName']; ?></td>
                </tr>
                <tr>
                    <td>Recipe Category:</td>
                    <td><?php if ($fetch_recipe['recipeCatId'] == 1) {
                                    echo "Malay";
                                } else if ($fetch_recipe['recipeCatId'] == 2) {
                                    echo "Chinese";
                                } else if ($fetch_recipe['recipeCatId'] == 3) {
                                    echo "Indian";
                                } else if ($fetch_recipe['recipeCatId'] == 4) {
                                    echo "Western";
                                } else {
                                    echo "Unknown Category";
                                } ?></td>
                </tr>
                <tr>
                    <td>Recipe Image:</td>
                    <td><img src="../TalkChef/image/<?php echo $fetch_recipe['recipeImage']; ?>"
                            alt="<?php echo $fetch_recipe['recipeName'] ?>" class="deleteImg"></td>
                </tr>
                <tr>
                    <td>Recipe Description:</td>
                    <td><?php echo $fetch_recipe['recipeDesc']; ?></td>
                </tr>
                <tr>
                    <td>Estimated Time (minutes):</td>
                    <td><?php echo $fetch_recipe['estTime'] . " minutes"; ?></td>
                </tr>
                <tr>
                    <td colspan="2"><button onclick="Confirm()">Delete</button></td>
                </tr>
            </table>
            <div id="confirm" class="confirm">
                Are you sure you want to delete this recipe?

                <div class="confirm-button">
                    <form action="admin-recipeDelete.php" method="POST">
                        <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
                        <input type="submit" name="submit" value="Yes">
                    </form>
                    <button onclick="NoDelete()">No</button>
                </div>

            </div>
        </div>
        <?php
            }
        }
        ?>
    </div>
</body>

</html>