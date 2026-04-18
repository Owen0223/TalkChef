<!DOCTYPE html>
<html>
<?php
require_once 'Connection.php';
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
    <title>Delete Ingredients</title>
</head>

<body>
    <div class="back-button">
        <a href="admin-ingre.html">
            <img src="../TalkChef/image/left.png" alt="Back" class="back-image">
        </a>
    </div>
    <h1>Delete Ingredients</h1>

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
            <form action="admin-deleteingre.php" method="POST">
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
                        <td>
                        <td><button type="button" onclick="Confirm()">Delete</button></td>
                    </tr>
                </table>

                <div id="confirm" class="confirm">
                    Are you sure you want to delete this recipe?

                    <div class="confirm-button">
                        <input type="submit" name="submit" value="Yes">
                        <button type="button" onclick="NoDelete()">No</button>
                    </div>

                </div>
            </form>

            <div id="confirm" class="confirm">
                Are you sure you want to delete this recipe?

                <div class="confirm-button">
                    <form action="admin-ingreDelete.php" method="POST">
                        <input type="hidden" name="ingreId" value="<?php echo $ingreId; ?>">
                        <input type="submit" name="submit" value="Yes">
                    </form>
                    <button onclick="NoDelete()">No</button>
                </div>

            </div>

        </div>
</body>

</html>

<?php

if (isset($_POST['submit'])) {
    $ingreId = $_POST['ingreId'];

    if ($ingreId != 0) {
        $sql = "DELETE FROM ingredient WHERE ingreId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $ingreId);

        if ($stmt->execute()) {
            echo "<script>alert('Ingredient Delete Successfully!')</script>";
            echo "<script>location.href='admin-deleteingre.php';</script>";
        } else {
            echo "<script>alert('Error!')</script>";
            echo "<script>location.href='admin-deleteingre.php';</script>";
        }
    } else {
        echo "<script>alert('Choose the ingredient you want to delete!')</script>";
        echo "<script>location.href='admin-deleteingre.php';</script>";
    }
}
?>