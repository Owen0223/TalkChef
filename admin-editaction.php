<!DOCTYPE html>
<html>
<?php
require_once 'Connection.php';

$recipeId = isset($_POST['recipeId']) ? $_POST['recipeId'] : 0;
$no = isset($_POST['no']) ? $_POST['no'] : 0;
$ingreId = isset($_POST['ingreId']) ? $_POST['ingreId'] : 0;
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <title>Edit Actions</title>
</head>

<body>
    <div class="back-button">
        <a href="admin-instruct.html">
            <img src="../TalkChef/image/left.png" alt="Back" class="back-image">
        </a>
    </div>
    <h1>Edit Actions</h1>

    <div class="table-container">
        <?php
        $ingre = "SELECT * FROM ingredient";
        $allingre = mysqli_query($conn, $ingre);

        if (mysqli_num_rows($allingre) > 0) {
            $count = 0;
            while ($fetch_ingre = mysqli_fetch_assoc($allingre)) {
                if ($count % 10 === 0) {
                    if ($count > 0) {
                        echo '</table>';
                    }
                    echo '<table class="ingredient-table">';
                    echo '<tr class="ingre-header"><th>ID</th><th>Name</th><th>Type</th><th>Unit</th></tr>';
                }

                echo '<tr>';
                echo '<td>' . $fetch_ingre['ingreId'] . '</td>';
                echo '<td>' . $fetch_ingre['ingreName'] . '</td>';
                echo '<td>' . $fetch_ingre['type'] . '</td>';
                echo '<td>' . $fetch_ingre['unit'] . '</td>';
                echo '</tr>';

                $count++;
            }
            echo '</table>';
        }
        ?>
    </div>

    <div class="view-position">
        <div class="add-container">
            <form action="admin-editaction.php" method="post">
                <h2>Choose Recipe and Step No:</h2>
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
                    </tr>
                    <tr>
                        <td><label for="no">Step No:</label></td>
                        <td><input type="text" name="no" id="no"></td>
                    </tr>
                    <tr>
                        <td><label for="ingreId">Ingredient:</label></td>
                        <td><select id="ingreId" name="ingreId">
                                <option value="0">Choose Ingredient</option>
                                <?php
                                $recipe = "SELECT * FROM ingredient";
                                $allrecipe = mysqli_query($conn, $recipe);

                                if (mysqli_num_rows($allrecipe) > 0) {
                                    while ($fetch_recipe = mysqli_fetch_assoc($allrecipe)) {
                                ?>
                                <option value="<?php echo $fetch_recipe['ingreId']; ?>">
                                    <?php echo $fetch_recipe['ingreId'] . ". " . $fetch_recipe['ingreName']; ?>
                                </option>
                                <?php
                                    }
                                }
                                ?>
                            </select></td>
                    </tr>
                </table>
                <input type="submit" name="submit" value="Search">
            </form>
        </div>
    </div>

    <?php
    if ($recipeId != 0 && $no != 0 && $ingreId != 0) {
        $sql = "SELECT * FROM recipe_ingredient WHERE recipeId = ? AND no = ? AND ingreId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iii', $recipeId, $no, $ingreId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $fetch_action = mysqli_fetch_assoc($result);
    ?>
    <div class="view-position">
        <div class="add-container">
            <form action="admin-actionEdit.php" method="post">
                <h2>Action:</h2>
                <table>
                    <tr>
                        <td><label for="action">Action:</label></td>
                        <td><input type="text" name="action" id="action" value="<?php echo $fetch_action['action']; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="quantity">Quantity:</label></td>
                        <td><input type="text" name="quantity" id="quantity"
                                value="<?php echo $fetch_action['quantity']; ?>"></td>
                    </tr>
                    <tr>
                        <td><label for="adjustable">Adjustable:</label></td>
                        <td><select id="adjustable" name="adjustable">
                                <option value="0" <?php if ($fetch_action['adjustable'] == 0) echo "selected"; ?>>No
                                </option>
                                <option value="1" <?php if ($fetch_action['adjustable'] == 1) echo "selected"; ?>>Yes
                                </option>
                            </select></td>
                    </tr>
                </table>
                <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
                <input type="hidden" name="no" value="<?php echo $no; ?>">
                <input type="hidden" name="ingreId" value="<?php echo $ingreId; ?>">
                <input type="submit" name="submit" value="Edit Instruction">
            </form>
        </div>
    </div>
    <?php
        }
    }
    ?>
</body>

</html>