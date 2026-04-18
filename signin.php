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
    <title>Sign In</title>
</head>

<div id="background-animation"></div>

<main>

    <div class="header">
        <div class="back-button">
            <a href="welcome.html">
                <img src="../TalkChef/image/left.png" alt="Back" class="back-image">
            </a>
        </div>
        <h1>Sign In</h1>
    </div>
    <div class="signin">
        <form action="signin.php" method="post">
            <table>
                <tr>
                    <td>Email:</td>
                    <td><input type="email" name="email"></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><input type="password" name="password"></td>
                </tr>
                <tr>
                    <td colspan="2" class="forgot-row">
                        <a href="verify-forgot.php" class="forgot">Forgot Password?</a>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Sign In" class="signin" name="signin">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Sign In as <a href="home.php" class="link-guest">Guest</a>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="divider">
                            <hr>
                            <span>OR</span>
                            <hr>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Don't have an account? <a href="signup.php" class="link">Sign Up</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>

</main>

</html>
<script src="sign.js"></script>
<?php
if (isset($_POST['signin'])) {
    if (!empty($_POST["email"]) && !empty($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $get_query = "SELECT * FROM user WHERE userEmail = '$email'";
        $result = mysqli_query($conn, $get_query);

        while ($row = mysqli_fetch_assoc($result)) {
            $check_email = $row['userEmail'];
            $check_password = $row['userPwd'];
            $check_id = $row['userId'];
            $username = $row['userName'];
            $isadmin = $row['isAdmin'];
            $verify = $row['verify_status'];
        }

        if ($email == $check_email && $password == $check_password && $verify != 0) {
            $_SESSION['name'] = $username;
            $_SESSION['userId'] = $check_id;

            if ($isadmin == 1) {
                echo "<script>alert('Welcome TalkChef Admin!')</script>";
                echo "<script>location.href='admin.html'</script>";
            } else {
                echo "<script>alert('Login Successfully!')</script>";
                echo "<script>location.href='home.php'</script>";
            }
        } else if ($verify == 0) {
            echo "<script>alert('This Email has not been Verified, Please Verify through Email!')</script>";
            echo "<script>location.href='signin.php'</script>";
        } else {
            echo "<script>alert('Incorrect Email or Password, Please Try Again!')</script>";
            echo "<script>location.href='signin.php'</script>";
        }
    } else {
        echo "<script>alert('Please fill up your login information!')</script>";
        echo "<script>location.href='signin.php'</script>";
    }
}
?>