<!DOCTYPE html>
<?php
require_once 'Connection.php';
session_start();
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="sign.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <title>Verify Forgot Password Email</title>
</head>

<div id="background-animation"></div>

<main>

    <div class="header">
        <div class="back-button">
            <a href="signup.php">
                <img src="../TalkChef/image/left.png" alt="Back" class="back-image">
            </a>
        </div>
        <h2>Verify Forgot Password Email</h2>
    </div>
    <div class="alert">
        <?php
        if (isset($_SESSION['status'])) {
            echo "<h4>" . $_SESSION['status'] . "</h4>";
            unset($_SESSION['status']);
        }
        ?>
    </div>
    <div class="reset">
        <form action="forgot-code.php" method="post">
            <table>
                <tr>
                    <td>Email:</td>
                    <td><input type="email" name="email"></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Send" class="reset" name="reset"></td>
                </tr>
            </table>
        </form>
    </div>

</main>

</html>
<script src="sign.js"></script>