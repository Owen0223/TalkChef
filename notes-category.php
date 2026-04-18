<?php
require_once 'Connection.php';
?>

<div id="recipes-container">
    <?php
    if (isset($_GET["catId"])) {
        $categoryId = $_GET["catId"];

        $sql = "SELECT * FROM recipe WHERE recipeCatId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($fetch_recipe = mysqli_fetch_assoc($result)) {
                include "shownotes.php";
            }
        }
    } else {
        $all = "SELECT * FROM recipe";
        $allrecipe = mysqli_query($conn, $all);

        if (mysqli_num_rows($allrecipe) > 0) {
            while ($fetch_recipe = mysqli_fetch_assoc($allrecipe)) {
                include "shownotes.php";
            }
        }
    }
    ?>
</div>