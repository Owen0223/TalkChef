<!DOCTYPE html>
<?php
require_once 'Connection.php';
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="sign.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <title>Forgot Password</title>
</head>

<div id="background-animation"></div>

<main>

    <div class="header">
        <div class="back-button">
            <a href="signin.php">
                <img src="../TalkChef/image/left.png" alt="Back" class="back-image">
            </a>
        </div>
        <h1>Forgot Password</h1>
    </div>
    <div class="reset">
        <form action="forgot.php" method="post">
            <input type="hidden" name="token" value="<?php if (isset($_GET['token'])) {
                                                            echo $_GET['token'];
                                                        } ?>">
            <table>
                <tr>
                    <td>Email:</td>
                    <td><input type="email" name="email" value="<?php if (isset($_GET['email'])) {
                                                                    echo $_GET['email'];
                                                                } ?>"></td>
                </tr>
                <tr>
                    <td>New Password:</td>
                    <td><input type="password" name="password" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}"
                            title="Password must be at least 8 characters, contain at least one uppercase letter, one lowercase letter, and one symbol">
                    </td>
                </tr>
                <tr>
                    <td>Confirm Password:</td>
                    <td><input type="password" name="Cpassword"></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Reset Password" class="reset" name="reset"></td>
                </tr>
            </table>
        </form>
    </div>

</main>

</html>
<script src="sign.js"></script>
<?php
if (isset($_POST['reset'])) {
    if (!empty($_POST['token'])) {
        if (!empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["Cpassword"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            $Cpassword = $_POST["Cpassword"];
            $token = $_POST['token'];

            $get_query = "SELECT * FROM user WHERE userEmail = '$email'";
            $result = mysqli_query($conn, $get_query);

            if (mysqli_num_rows($result) > 0) {
                if ($password == $Cpassword) {
                    $sql = "UPDATE user SET userPwd = ? WHERE userEmail = ? AND verify_token = ? LIMIT 1";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('sss', $password, $email, $token);
                    if ($stmt->execute()) {
                        echo "<script>alert('Reset Password Successfully!')</script>";
                        echo "<script>location.href='signin.php';</script>";
                    } else {
                        echo "<script>alert('Did not update password. Something went wrong!')</script>";
                        echo "<script>location.href='verify-forgot.php'</script>";
                    }
                } else {
                    echo "<script>alert('Password and Confirm Password does not match, please enter again!')</script>";
                    echo "<script>location.href='verify-forgot.php'</script>";
                }
            } else {
                echo "<script>alert('Email Not Found, Please Try Again!')</script>";
                echo "<script>location.href='verify-forgot.php'</script>";
            }
        } else {
            echo "<script>alert('Please fill up the information!')</script>";
            echo "<script>location.href='verify-forgot.php'</script>";
        }
    } else {
        echo "<script>alert('There seems to be a Problem!')</script>";
        echo "<script>location.href='verify-forgot.php'</script>";
    }
}
?>