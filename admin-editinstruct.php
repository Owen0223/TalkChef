<!DOCTYPE html>
<html>
<?php
require_once 'Connection.php';

$recipeId = isset($_POST['recipeId']) ? $_POST['recipeId'] : 0;
$no = isset($_POST['no']) ? $_POST['no'] : 0;
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <title>Edit Instructions</title>
</head>

<body>
    <div class="back-button">
        <a href="admin-instruct.html">
            <img src="../TalkChef/image/left.png" alt="Back" class="back-image">
        </a>
    </div>
    <h1>Edit Instructions</h1>

    <div class="view-position">
        <div class="add-container">
            <form action="admin-editinstruct.php" method="post">
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
                </table>
                <input type="submit" name="submit" value="Search">
            </form>
        </div>
    </div>

    <?php
    if ($recipeId != 0 && $no != 0) {
        $sql = "SELECT * FROM step WHERE recipeId = ? AND no = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $recipeId, $no);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $fetch_instruct = mysqli_fetch_assoc($result);
    ?>
    <div class="view-position">
        <div class="add-container">
            <form action="admin-instructEdit.php" method="post">
                <h2>Step Information:</h2>
                <table>
                    <tr>
                        <td><label for="preInstruction">Pre-Instruction:</label></td>
                        <td><textarea name="preInstruction" id="preInstruction" rows="6"
                                cols="50"><?php echo $fetch_instruct['preInstruction']; ?></textarea></td>
                    </tr>
                    <tr>
                        <td><label for="postInstruction">Post-Instruction:</label></td>
                        <td><textarea name="postInstruction" id="postInstruction" rows="6"
                                cols="50"><?php echo $fetch_instruct['postInstruction']; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="highlight">Highlight:</label></td>
                        <td><input type="text" name="highlight" id="highlight"
                                value="<?php echo $fetch_instruct['highlight']; ?>"></td>
                    </tr>
                    <tr>
                        <td><label for="duration">Cooking Duration:</label></td>
                        <td><input type="text" name="duration" id="duration"
                                value="<?php echo $fetch_instruct['duration']; ?>"></td>
                    </tr>
                </table>
                <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
                <input type="hidden" name="no" value="<?php echo $no; ?>">
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