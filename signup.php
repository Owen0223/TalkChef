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
    <title>Sign Up</title>
</head>

<div id="background-animation"></div>

<main>
    <div class="header">
        <div class="back-button">
            <a href="signin.php">
                <img src="../TalkChef/image/left.png" alt="Back" class="back-image">
            </a>
        </div>
        <h1>Sign Up</h1>
    </div>
    <div class="alert">
        <?php
        if (isset($_SESSION['status'])) {
            echo "<h4>" . $_SESSION['status'] . "</h4>";
            unset($_SESSION['status']);
        }
        ?>
    </div>
    <div class="signup">
        <form action="register.php" method="post">
            <table>
                <tr>
                    <td>Email:</td>
                    <td><input type="email" name="email"></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><input type="password" name="password" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}"
                            title="Password must be at least 8 characters, contain at least one uppercase letter, one lowercase letter, and one symbol">
                    </td>
                </tr>
                <tr>
                    <td>Confirm Password:</td>
                    <td><input type="password" name="Cpassword"></td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td><input type="text" name="name"></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Sign Up" class="signup" name="signup"></td>
                </tr>
                <tr>
                    <td colspan="2">I have registered. <a href="signin.php" class="link">Sign In</a></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="divider">
                            <hr>
                            <span>IF</span>
                            <hr>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Didn't Receive Verification Email? <a href="resend-verifyEmail.php" class="link">Resend</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</main>

</html>

<script src="sign.js"></script>