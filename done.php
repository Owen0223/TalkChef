<!DOCTYPE html>
<?php
require_once 'Connection.php';
require_once 'session.php';
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="done.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <title>Done Cooking</title>
</head>

<body>
    <?php
    require_once 'header.php';
    ?>

    <div class="side-decor left-decor">
        <img src="../TalkChef/image/done-chef.png" alt="Left Food Icon">
    </div>

    <div class="done-position">
        <div class="done-container">
            <h2>Your Meal is Done! Enjoy~</h2>
            <a href="home.php"><button>Home</button></a>
            <a href="feedback.php"><button>Leave a Feedback</button></a>
        </div>
    </div>

    <?php
    require_once 'footer.php';
    ?>
</body>

</html>