<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="showrecipe.css" />
</head>

<body>
    <?php
    require_once 'calculation.php';
    ?>
    <div class="recipe-container">
        <div class="recipe-card">
            <form action="details.php" method="POST">
                <input type="hidden" name="recipeId" value="<?php echo $fetch_recipe['recipeId']; ?>">
                <div class="recipe-image">
                    <button type="submit">
                        <img src="../TalkChef/image/<?php echo $fetch_recipe['recipeImage']; ?>"
                            alt="<?php echo $fetch_recipe['recipeName']; ?>">
                    </button>
                    <div class="recipe-overlay">
                        <h3><?php echo $fetch_recipe['recipeName']; ?></h3>

                        <?php
                        $nutritionDetails = calculateNutrition($fetch_recipe['recipeId']);
                        if (isset($nutritionDetails['tags'])) {
                            echo "<div class='nutrition-icons'>";
                            if ($nutritionDetails['tags']['proteinTag'] == "High in Protein") {
                                echo "<img src='../TalkChef/image/high-protein.png' alt='High in Protein'>";
                            } else if ($nutritionDetails['tags']['proteinTag'] == "Low in Protein") {
                                echo "<img src='../TalkChef/image/low-protein.png' alt='Low in Protein'>";
                            }

                            if ($nutritionDetails['tags']['carbsTag'] == "High in Carbohydrates") {
                                echo "<img src='../TalkChef/image/high-carbs.png' alt='High in Carbohydrates'>";
                            } else if ($nutritionDetails['tags']['carbsTag'] == "Low in Carbohydrates") {
                                echo "<img src='../TalkChef/image/low-carbs.png' alt='Low in Carbohydrates'>";
                            }

                            if ($nutritionDetails['tags']['fatTag'] == "High in Fat") {
                                echo "<img src='../TalkChef/image/high-fat.png' alt='High in Fat'>";
                            } else if ($nutritionDetails['tags']['fatTag'] == "Low in Fat") {
                                echo "<img src='../TalkChef/image/low-fat.png' alt='Low in Fat'>";
                            }
                            echo "</div>";
                        }

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
            </form>
        </div>
    </div>
</body>

</html>