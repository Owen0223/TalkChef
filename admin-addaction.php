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
    <title>Add Actions</title>
</head>

<body>
    <div class="back-button">
        <a href="admin-instruct.html">
            <img src="../TalkChef/image/left.png" alt="Back" class="back-image">
        </a>
    </div>
    <h1>Add Actions</h1>

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
            <form action="admin-addaction.php" method="POST">
                <h2>Action:</h2>
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
                    <tr>
                        <td><label for="action">Action:</label></td>
                        <td><input type="text" name="action" id="action"></td>
                    </tr>
                    <tr>
                        <td><label for="quantity">Quantity:</label></td>
                        <td><input type="text" name="quantity" id="quantity"></td>
                    </tr>
                    <tr>
                        <td><label for="adjustable">Adjustable:</label></td>
                        <td><select id="adjustable" name="adjustable">
                                <option value="0" selected>No</option>
                                <option value="1">Yes</option>
                            </select></td>
                    </tr>
                </table>
                <input type="submit" name="submit" value="Add Action">
            </form>
        </div>
    </div>
</body>

</html>

<?php
if (isset($_POST['submit'])) {
    if (!empty($_POST['recipeId'])) {
        $recipeId = $_POST['recipeId'];
        $no = $_POST['no'];
        $ingreId = $_POST['ingreId'];
        $action = $_POST['action'];
        $quantity = $_POST['quantity'];
        $adjustable = $_POST['adjustable'];
        if (empty($_POST['no'])) {
            $no = null;
        }

        $findaction = "SELECT * FROM recipe_ingredient WHERE recipeId = ? AND no = ? AND ingreId = ?";
        $stmt_find = $conn->prepare($findaction);
        $stmt_find->bind_param("iii", $recipeId, $no, $ingreId);
        $stmt_find->execute();
        $result_find = $stmt_find->get_result();

        if ($result_find->num_rows > 0) {
            echo "<script>alert('Same ingredient has existed in the same step of the recipe!')</script>";
            echo "<script>location.href='admin-addaction.php';</script>";
            exit;
        }

        $sql = "INSERT INTO recipe_ingredient (recipeId, ingreId, quantity, adjustable, action, no) 
                        VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iidisi", $recipeId, $ingreId, $quantity, $adjustable, $action, $no);

        if ($stmt->execute()) {
            echo "<script>alert('Action Added Successfully!')</script>";
            echo "<script>location.href='admin-addaction.php';</script>";
        }
    } else {
        echo "<script>alert('Please fill up at least the Recipe, Step No and Ingredient!')</script>";
        echo "<script>location.href='admin-addaction.php';</script>";
    }
}
?>