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
            <form action="notebook.php" method="POST">
                <input type="hidden" name="recipeId" value="<?php echo $fetch_recipe['recipeId']; ?>">
                <div class="recipe-image">
                    <button type="submit">
                        <img src="../TalkChef/image/<?php echo $fetch_recipe['recipeImage']; ?>"
                            alt="<?php echo $fetch_recipe['recipeName']; ?>">
                    </button>
                    <div class="recipe-overlay">
                        <h3><?php echo $fetch_recipe['recipeName']; ?></h3>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>