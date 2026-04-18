<?php
require_once 'Connection.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/autoload.php';

function resend_email_verify($name, $email, $verify_token)
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
    $mail->Subject = 'Resend - Email Verification from TalkChef';

    $email_template = "
    <h2>You have Registered with TalkChef</h2>
    <h5>Verify your email address to Login with the below given link</h5><br/><br/>
    <a href='http://localhost/TalkChef/verify-email.php?token=$verify_token'>Click Me to Verify!</a>
    ";

    $mail->Body = $email_template;
    $mail->send();
}

if (isset($_POST['resend'])) {
    if (!empty($_POST['email'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);

        $checkemail_query = "SELECT * FROM user WHERE userEmail = '$email' LIMIT 1";
        $checkemail_query_run = mysqli_query($conn, $checkemail_query);

        if (mysqli_num_rows($checkemail_query_run) > 0) {
            $row = mysqli_fetch_array($checkemail_query_run);
            if ($row['verify_status'] == 0) {
                $name = $row['userName'];
                $email = $row['userEmail'];
                $verify_token = $row['verify_token'];

                resend_email_verify($name, $email, $verify_token);

                $_SESSION['status'] = "Verification Email Link has been sent to your Email Address!";
                echo "<script>location.href='resend-verifyEmail.php'</script>";
                exit(0);
            } else {
                echo "<script>alert('Email Already Verified. Please Sign In!')</script>";
                echo "<script>location.href='signin.php'</script>";
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