<!DOCTYPE html>
<html>
<?php
require_once 'Connection.php';

$ingreId = isset($_POST['ingreId']) ? $_POST['ingreId'] : 0;
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <title>Edit Ingredients</title>
</head>

<body>
    <div class="back-button">
        <a href="admin-ingre.html">
            <img src="../TalkChef/image/left.png" alt="Back" class="back-image">
        </a>
    </div>
    <h1>Edit Ingredients</h1>

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
            <form action="admin-editingre.php" method="POST">
                <table>
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
                        <td><input type="submit" value="Search"></td>
                    </tr>
                </table>
            </form>
        </div>

        <?php
        if ($ingreId != 0) {
            $sql = "SELECT * FROM ingredient WHERE ingreId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $ingreId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $fetch_ingre = mysqli_fetch_assoc($result);
        ?>
        <div class="view-position">
            <div class="ingre-container">
                <form action="admin-ingreEdit.php" method="post">
                    <table>
                        <tr>
                            <th><label for="ingreName">Ingredient Name:</label></th>
                            <th><label for="type">Ingredient Type:</label></th>
                            <th><label for="unit">Unit:</label></th>
                            <th><label for="protein">Protein (g):</label></th>
                            <th><label for="carbs">Carbohydrates (g):</label></th>
                            <th><label for="fat">Fat (g):</label></th>
                            <th><label for="calories">Calories (kcal):</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" name="ingreName" id="ingreName"
                                    value="<?php echo $fetch_ingre['ingreName']; ?>"></td>
                            <td><select id="type" name="type">
                                    <option value="0">Choose</option>
                                    <option value="carb" <?php if ($fetch_ingre['type'] == "carb") echo "selected"; ?>>
                                        Carb</option>
                                    <option value="vege" <?php if ($fetch_ingre['type'] == "vege") echo "selected"; ?>>
                                        Vege</option>
                                    <option value="protein"
                                        <?php if ($fetch_ingre['type'] == "protein") echo "selected"; ?>>Protein
                                    </option>
                                    <option value="fat" <?php if ($fetch_ingre['type'] == "fat") echo "selected"; ?>>Fat
                                    </option>
                                    <option value="other"
                                        <?php if ($fetch_ingre['type'] == "other") echo "selected"; ?>>Other</option>
                                </select></td>
                            <td><input type="text" name="unit" id="unit" value="<?php echo $fetch_ingre['unit']; ?>">
                            </td>
                            <td><input type="text" name="protein" id="protein"
                                    value="<?php echo $fetch_ingre['protein']; ?>"></td>
                            <td><input type="text" name="carbs" id="carbs" value="<?php echo $fetch_ingre['carbs']; ?>">
                            </td>
                            <td><input type="text" name="fat" id="fat" value="<?php echo $fetch_ingre['fat']; ?>"></td>
                            <td><input type="text" name="calories" id="calories"
                                    value="<?php echo $fetch_ingre['calories']; ?>"></td>
                        </tr>
                        <tr>
                            <td colspan="3"><input type="hidden" name="ingreId" value="<?php echo $ingreId; ?>"></td>
                            <td colspan="2"><input type="submit" name="submit" value="Edit Ingredient"></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <?php
            }
        }
        ?>
</body>

</html>