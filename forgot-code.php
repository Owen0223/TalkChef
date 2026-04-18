<?php
require_once 'Connection.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/autoload.php';

function send_password_reset($name, $email, $token)
{
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->SMTPAuth   = true;

    $mail->Host       = 'smtp.gmail.com';
    $mail->Username   = 'owenkong0223@gmail.com';
    $mail->Password   = 'azjg aeit jzer quwr';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom("owenkong0223@gmail.com", $name);
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Reset Password - Email Verification from TalkChef';

    $email_template = "
    <h2>You have Registered with TalkChef</h2>
    <h5>Verify your email address to Reset your Password with the below given link</h5><br/><br/>
    <a href='http://localhost/TalkChef/forgot.php?token=$token&email=$email'>Click Me to Verify!</a>
    ";

    $mail->Body = $email_template;
    $mail->send();
}

if (isset($_POST['reset'])) {
    if (!empty($_POST['email'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $token = md5(rand());

        $checkemail_query = "SELECT * FROM user WHERE userEmail = '$email' LIMIT 1";
        $checkemail_query_run = mysqli_query($conn, $checkemail_query);

        if (mysqli_num_rows($checkemail_query_run) > 0) {
            $row = mysqli_fetch_array($checkemail_query_run);
            $name = $row['userName'];
            $email = $row['userEmail'];

            $update_token = "UPDATE user SET verify_token='$token' WHERE userEmail='$email' LIMIT 1";
            $update_token_run = mysqli_query($conn, $update_token);

            if ($update_token_run) {
                send_password_reset($name, $email, $token);
                $_SESSION['status'] = "A Password Reset Email Link has been sent to your Email Address!";
                echo "<script>location.href='verify-forgot.php'</script>";
                exit(0);
            } else {
                echo "<script>alert('Something Went Wrong!')</script>";
                echo "<script>location.href='verify-forgot.php'</script>";
                exit(0);
            }
        } else {
            echo "<script>alert('Email is not Registered. Please Register now!')</script>";
            echo "<script>location.href='signup.php'</script>";
            exit(0);
        }
    } else {
        echo "<script>alert('Please Enter the Email Field!')</script>";
        echo "<script>location.href='resend-verifyEmail.php'</script>";
        exit(0);
    }
}