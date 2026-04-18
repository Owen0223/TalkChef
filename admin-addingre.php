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
    <title>Add Ingredients</title>
</head>

<body>
    <div class="back-button">
        <a href="admin-ingre.html">
            <img src="../TalkChef/image/left.png" alt="Back" class="back-image">
        </a>
    </div>
    <h1>Add Ingredients</h1>

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
        <div class="ingre-container">
            <form action="admin-addingre.php" method="post">
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
                        <td><input type="text" name="ingreName" id="ingreName"></td>
                        <td><select id="type" name="type">
                                <option value="0">Choose</option>
                                <option value="carb">Carb</option>
                                <option value="vege">Vege</option>
                                <option value="protein">Protein</option>
                                <option value="fat">Fat</option>
                                <option value="other">Other</option>
                            </select></td>
                        <td><input type="text" name="unit" id="unit"></td>
                        <td><input type="text" name="protein" id="protein"></td>
                        <td><input type="text" name="carbs" id="carbs"></td>
                        <td><input type="text" name="fat" id="fat"></td>
                        <td><input type="text" name="calories" id="calories"></td>
                    </tr>
                    <tr>
                        <td colspan="7"><input type="submit" name="submit" value="Add Ingredient"></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</body>

</html>

<?php
if (isset($_POST['submit'])) {
    $ingreName = $_POST['ingreName'];
    $type = $_POST['type'];
    $unit = $_POST['unit'];
    $protein = $_POST['protein'];
    $carbs = $_POST['carbs'];
    $fat = $_POST['fat'];
    $calories = $_POST['calories'];

    if (!empty($ingreName) && $type != 0) {
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

        $sql = "SELECT ingreName, type, unit FROM ingredient";
        $allsql = mysqli_query($conn, $sql);

        if ($allsql->num_rows > 0) {
            while ($fetch_ingre = mysqli_fetch_assoc($allsql)) {

                if ($ingreName == $fetch_ingre['ingreName'] && $type == $fetch_ingre['type'] && $unit == $fetch_ingre['unit']) {
                    echo "<script>alert('Same ingredient, type and unit existed!')</script>";
                    echo "<script>location.href='admin-addingre.php';</script>";
                    exit;
                }
            }
        }


        $sql = "INSERT INTO ingredient (ingreName, type, unit, protein, carbs, fat, calories) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssdddi', $ingreName, $type, $unit, $protein, $carbs, $fat, $calories);

        if ($stmt->execute()) {
            echo "<script>alert('Ingredient Added Successfully!')</script>";
            echo "<script>location.href='admin-addingre.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "<script>alert('Must Fill up all the information!')</script>";
        echo "<script>location.href='admin-addingre.php';</script>";
    }
}
?>