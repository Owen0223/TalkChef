<!DOCTYPE html>
<?php
require_once 'Connection.php';
require_once 'session.php';

$isFavorite = false;

if (isset($_POST["recipeId"])) {
    $recipeId = $_POST["recipeId"];
    if ($userId != 0 && isset($recipeId)) {
        $sql = "SELECT * FROM favorite WHERE userId = ? AND recipeId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $recipeId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $isFavorite = true;
        }
        $stmt->close();
    }
}
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="details.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <title>Recipe Details</title>
</head>

<script src="details.js"></script>

<body>
    <main>
        <?php
        require_once "calculation.php";
        require_once "fraction.php";
        require_once 'header.php';

        $recipeId = isset($_POST['recipeId']) ? $_POST['recipeId'] : (isset($_GET['recipeId']) ? $_GET['recipeId'] : null);

        echo "<h2>Recipe's Details</h2>";

        if ($recipeId) {
            $sql = "SELECT * FROM recipe WHERE recipeId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $recipeId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $fetch_recipe = mysqli_fetch_assoc($result);
        ?>
        <div class='recipe-infobox'>
            <div class='recipe-info'>
                <div class='recipe-image-container'>
                    <img src='../TalkChef/image/<?php echo $fetch_recipe['recipeImage']; ?>'
                        alt='"<?php echo $fetch_recipe['recipeName']; ?>"' class='recipe-image'>
                </div>
                <div class='recipe-text-container'>
                    <div class='recipe-header'>
                        <h1 class='recipe-title'><?php echo $fetch_recipe['recipeName']; ?></h1>
                        <?php
                                if ($userId != 0) {
                                ?>
                        <div class='love-button-container'>
                            <button class='love-button <?php echo $isFavorite ? "loved" : " "; ?>'
                                onclick='toggleFavorite()' data-recipe-id='<?php echo $recipeId; ?>'>

                                <span id='heart-icon' class='heart'>&#10084;&#65039;</span>
                                <?php
                                            echo $isFavorite ? 'Added to Favorites' : 'Add to Favorites';
                                            ?>
                            </button>
                        </div>
                        <?php
                                }
                                ?>
                    </div>
                    <p class='recipe-description'><?php echo $fetch_recipe['recipeDesc']; ?></p></br>
                    <?php
                            if ($fetch_recipe['estTime'] != 0) {
                                echo "<div class='estimate-time'>";
                                if ($fetch_recipe['estTime'] > 60) {
                                    echo "<h3>Estimate: " . number_format($fetch_recipe['estTime'] / 60, 2) . " hour</h3>";
                                } else if ($fetch_recipe['estTime'] < 60) {
                                    echo "<h3>Estimate: " . $fetch_recipe['estTime'] . " minutes</h3>";
                                } else if ($fetch_recipe['estTime'] == 60) {
                                    echo "<h3>Estimate: " . ($fetch_recipe['estTime'] / 60) . " hour</h3>";
                                }
                                echo "</div>";
                            }
                            ?>
                </div>
            </div>
        </div>
        <?php
            }
            ?>

        <?php
            if ($userId != 0) {
            ?>
        <div class="information-container">
            <div class="left-section">
                <div class="recipe-notes">
                    <h2>Recipe Notes</h2>
                    <?php
                            $noteSql = "SELECT * FROM recipe_notes WHERE recipeId = ? AND userId = ?";
                            $noteStmt = $conn->prepare($noteSql);
                            $noteStmt->bind_param("ii", $recipeId, $userId);
                            $noteStmt->execute();
                            $noteResult = $noteStmt->get_result();

                            if ($noteResult->num_rows > 0) {
                                $note = mysqli_fetch_assoc($noteResult);
                            ?>
                    <form method="POST" action="edit_note.php">
                        <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
                        <input type="hidden" name="noteId" value="<?php echo $note['id']; ?>">
                        <input type="hidden" name="source" value="details">
                        <textarea name="recipeNote"><?php echo htmlspecialchars($note['note']); ?></textarea>
                        <?php if (isset($stepNo)) { ?>
                        <input type="hidden" name="stepNo" value="<?php echo $stepNo; ?>">
                        <?php } ?>
                        <div class="note-actions">
                            <button type="submit">Edit</button>
                        </div>
                    </form>
                    <form method="POST" action="delete_note.php">
                        <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
                        <input type="hidden" name="noteId" value="<?php echo $note['id']; ?>">
                        <input type="hidden" name="source" value="details">
                        <?php if (isset($stepNo)) { ?>
                        <input type="hidden" name="stepNo" value="<?php echo $stepNo; ?>">
                        <?php } ?>
                        <div class="note-actions">
                            <button type="submit">Delete</button>
                        </div>
                    </form>
                    <?php
                            } else {
                            ?>
                    <form method="POST" action="save_note.php">
                        <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
                        <input type="hidden" name="source" value="details">
                        <textarea name="recipeNote" placeholder="Leave a note for this recipe..."></textarea>
                        <div class="note-actions">
                            <button type="submit">Save</button>
                        </div>
                    </form>
                    <?php
                            }
                            ?>
                </div>
            </div>

            <?php
            }
                ?>

            <?php
                if ($userId != 0) {
                ?>
            <div class="right-section">
                <?php
                }
                    ?>
                <?php
                    $nutritionDetails = calculateNutrition($recipeId);

                    if (isset($nutritionDetails['totals'])) {
                        echo "<div id='nutrition-totals' class='nutrition-totals'>";
                        echo "<h2>Nutrition per serving:</h2>";
                        echo "<p id='totalCalories'>Total Calories: " . $nutritionDetails['totals']['totalCalorie'] . " kcal</br></p>";
                        echo "<p id='totalProtein'>Total Protein: " . $nutritionDetails['totals']['totalProtein'] . " g</br></p>";
                        echo "<p id='totalCarbs'>Total Carbohydrates: " . $nutritionDetails['totals']['totalCarbs'] . " g</br></p>";
                        echo "<p id='totalFat'>Total Fat: " . $nutritionDetails['totals']['totalFat'] . " g</br></p>";
                        echo "</div>";
                    }
                    ?>

                <?php
                    if ($userId != 0) {
                    ?>
            </div>
        </div>
        <?php
                    }
            ?>

        <div class="adjustment-container">
            <div class="left-section">
                <div class="adjust-ingredients">
                    <h2>Adjust Ingredients</h2>

                    <div class="ingredient-slider">
                        <img src="../TalkChef/image/meat.png" alt="Protein Icon">
                        <div class="slider-container">
                            <label for="protein">Protein</label>
                            <input type="range" id="protein" name="protein" min="0.5" max="1.5" value="1" step="0.1"
                                oninput="adjustNutrients(this, 'protein')">
                            <span class="amount" id="proteinAmount">x1</span>
                        </div>
                    </div>

                    <div class="ingredient-slider">
                        <img src="../TalkChef/image/rice.png" alt="Carbs Icon">
                        <div class="slider-container">
                            <label for="carb">Carbohydrates</label>
                            <input type="range" id="carb" name="carb" min="0.5" max="1.5" value="1" step="0.1"
                                oninput="adjustNutrients(this, 'carb')">
                            <span class="amount" id="carbAmount">x1</span>
                        </div>
                    </div>

                    <div class="ingredient-slider">
                        <img src="../TalkChef/image/broccoli.png" alt="Vege Icon">
                        <div class="slider-container">
                            <label for="vege">Vegetable</label>
                            <input type="range" id="vege" name="vege" min="0.5" max="1.5" value="1" step="0.1"
                                oninput="adjustNutrients(this, 'vege')">
                            <span class="amount" id="vegeAmount">x1</span>
                        </div>
                    </div>

                    <div class="ingredient-slider">
                        <img src="../TalkChef/image/cheese.png" alt="Fat Icon">
                        <div class="slider-container">
                            <label for="fat">Fat</label>
                            <input type="range" id="fat" name="fat" min="0.5" max="1.5" value="1" step="0.1"
                                oninput="adjustNutrients(this, 'fat')">
                            <span class="amount" id="fatAmount">x1</span>
                        </div>
                    </div>


                    <div class="serving-control">
                        <label for="serving">Servings:</label>
                        <button onclick="adjustServing(-1)">-</button>
                        <span id="serving">1</span>
                        <button onclick="adjustServing(1)">+</button>
                    </div>
                </div>
            </div>

            <div class="right-section">
                <div class="ingredients-list">
                    <h2>Ingredient List</h2>

                    <?php


                        if (isset($nutritionDetails['ingredients'])) {
                            foreach ($nutritionDetails['ingredients'] as $ingredient) {
                                $riId = $ingredient['riId'];
                                $quantity = $ingredient['quantity'];
                                $type = $ingredient['type'];
                                $unit = $ingredient['unit'];
                                $ingredientName = $ingredient['ingreName'];
                                $calories = $ingredient['calories'];
                                $protein = $ingredient['protein'];
                                $carbs = $ingredient['carbs'];
                                $fat = $ingredient['fat'];
                                $adjustable = $ingredient['adjustable'];

                                echo "<script>
                        baseAmounts['$riId'] = $quantity;
                        ingredientDetails['$riId'] = {
                        type: '$type', 
                        unit: '$unit', 
                        name: '$ingredientName', 
                        calories: $calories,
                        protein: $protein,
                        carbs: $carbs,
                        fat: $fat,
                        adjustable: $adjustable};
                      </script>";

                                echo "<span id='$riId'>" . decimalToFraction($quantity) . " " . $unit . " " . $ingredientName . "</br></span>";
                            }
                        } else {
                            echo "There is no details in this recipe yet";
                        }
                        ?>
                </div>
            </div>
        </div>
        <?php
        }
        ?>

        <div class="button-container">
            <a href="home.php"><button class="details-button">Back</button></a>
            <button onclick="resetValues()" class="details-button">Reset to Default</button>

            <form id="cookingForm" action="steps.php" method="POST" onsubmit="return prepareInstructions()">
                <input type="hidden" name="recipeId_step" value="<?php echo $recipeId; ?>">
                <input type="hidden" id="updatedServing" name="updatedServing" value="">
                <input type="hidden" id="updatedMultipliers" name="updatedMultipliers" value="">
                <button type="submit" class="details-button">Start Cooking</button>
            </form>
        </div>
        <?php
        require_once 'footer.php';
        ?>
    </main>
</body>

</html>