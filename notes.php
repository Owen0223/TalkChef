<!DOCTYPE html>
<?php
require_once 'Connection.php';
session_start();

if (!empty($_SESSION['name'])) {
    if ($_SESSION['name'] != "admin") {
        $username = $_SESSION['name'];
        $userId = $_SESSION['userId'];
    } else {
        $username = "Guest";
        $userId = 0;
        echo "<script>alert('Must Sign In as User to use this Feature!')</script>";
        echo "<script>location.href='signin.php'</script>";
    }
} else {
    $username = "Guest";
    $userId = 0;
    echo "<script>alert('Must Sign In as User to use this Feature!')</script>";
    echo "<script>location.href='signin.php'</script>";
}
?>

<script src="notes.js"></script>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="notes.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <title>Your Notebook</title>
</head>

<body>
    <?php
    require_once 'header.php';
    ?>

    <h2>Your Notebook</h2>
    <div id="category">
        <a href="notes.php"><button>Show All</button></a>
        <button onclick="showRecipe(1)">Malay</button>
        <button onclick="showRecipe(2)">Chinese</button>
        <button onclick="showRecipe(3)">Indian</button>
        <button onclick="showRecipe(4)">Western</button>
    </div>

    <?php
    require_once 'notes-category.php';
    require_once 'footer.php';
    ?>
</body>

</html>