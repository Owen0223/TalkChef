<!DOCTYPE html>
<?php
require_once 'Connection.php';
require_once 'session.php';
?>
<script src="home.js"></script>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="home.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <title>Home</title>
</head>

<body>
    <?php
    require_once 'header.php';
    ?>
    <main>
        <h2>TalkChef's Recipe</h2>
        <div id="category">
            <a href="home.php"><button>Show All</button></a>
            <button onclick="showRecipe(1)">Malay</button>
            <button onclick="showRecipe(2)">Chinese</button>
            <button onclick="showRecipe(3)">Indian</button>
            <button onclick="showRecipe(4)">Western</button>
        </div>
        <?php
        require_once "recipe.php";
        ?>
    </main>

    <?php
    require_once 'footer.php';
    ?>
</body>

</html>