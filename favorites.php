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

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="favorites.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <title>Your Favorites</title>
</head>

<body>
    <?php
    require_once 'header.php';
    ?>

    <h2>Your Favorites</h2>

    <?php
    if ($userId != 0) {
        $sql = "SELECT * FROM favorite JOIN recipe ON favorite.recipeId = recipe.recipeId JOIN user ON favorite.userId = user.userId WHERE favorite.userId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($fetch_recipe = mysqli_fetch_assoc($result)) {
                include "showrecipe.php";
            }
        } else {
    ?>
    <div class="position">
        <div class="container">
            <h3>You don't have any favorite recipe.</h3>
        </div>
    </div>
    <?php
        }
    } else {
        ?>
    <div class="position">
        <div class="container">
            <h3>You don't have any favorite recipe.</h3>
        </div>
    </div>
    <?php
    }
    require_once 'footer.php';
    ?>
</body>

</html>