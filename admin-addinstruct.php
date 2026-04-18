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
    <title>Add Instructions</title>
</head>

<body>
    <div class="back-button">
        <a href="admin-instruct.html">
            <img src="../TalkChef/image/left.png" alt="Back" class="back-image">
        </a>
    </div>
    <h1>Add Instructions</h1>

    <div class="view-position">
        <div class="add-container">
            <form action="admin-addinstruct.php" method="post">
                <h2>Step Information:</h2>
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
                        <td><label for="preInstruction">Pre-Instruction:</label></td>
                        <td><textarea name="preInstruction" id="preInstruction" rows="6" cols="50"></textarea></td>
                    </tr>
                    <tr>
                        <td><label for="postInstruction">Post-Instruction:</label></td>
                        <td><textarea name="postInstruction" id="postInstruction" rows="6" cols="50"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="highlight">Highlight:</label></td>
                        <td><input type="text" name="highlight" id="highlight"></td>
                    </tr>
                    <tr>
                        <td><label for="duration">Cooking Duration:</label></td>
                        <td><input type="text" name="duration" id="duration"></td>
                    </tr>
                </table>
                <input type="submit" name="submit" value="Add Instruction">
            </form>
        </div>
    </div>
</body>

</html>

<?php
if (isset($_POST['submit'])) {
    if (!empty($_POST['recipeId']) && !empty($_POST['no'])) {
        $recipeId = isset($_POST['recipeId']) ? $_POST['recipeId'] : 0;
        $no = isset($_POST['no']) ? $_POST['no'] : 0;
        $preInstruction = isset($_POST['preInstruction']) ? $_POST['preInstruction'] : null;
        $postInstruction = isset($_POST['postInstruction']) ? $_POST['postInstruction'] : null;
        $highlight = isset($_POST['highlight']) ? $_POST['highlight'] : null;
        $duration = isset($_POST['duration']) ? $_POST['duration'] : 0;

        $step = "SELECT * FROM step";
        $allstep = mysqli_query($conn, $step);

        if (mysqli_num_rows($allstep) > 0) {
            while ($fetch_step = mysqli_fetch_assoc($allstep)) {
                if ($fetch_step['recipeId'] == $recipeId && $fetch_step['no'] == $no) {
                    echo "<script>alert('Same step has existed in the same recipe!')</script>";
                    echo "<script>location.href='admin-addinstruct.php';</script>";
                    exit;
                }
            }

            $sql = "INSERT INTO step (recipeId, no, preInstruction, postInstruction, highlight, duration) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iisssd", $recipeId, $no, $preInstruction, $postInstruction, $highlight, $duration);

            if ($stmt->execute()) {
                echo "<script>alert('Instruction Added Successfully!')</script>";
                echo "<script>location.href='admin-addinstruct.php';</script>";
            }
        }
    } else {
        echo "<script>alert('Please fill up at least the Recipe and Step!')</script>";
        echo "<script>location.href='admin-addinstruct.php';</script>";
    }
}