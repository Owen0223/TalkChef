<?php
session_start();
require_once 'Connection.php';

use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/autoload.php';

function sendemail_verify($name, $email, $verify_token)
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
    $mail->Subject = 'Email Verification from TalkChef';

    $email_template = "
    <h2>You have Registered with TalkChef</h2>
    <h5>Verify your email address to Login with the below given link</h5><br/><br/>
    <a href='http://localhost/TalkChef/verify-email.php?token=$verify_token'>Click Me to Verify!</a>
    ";

    $mail->Body = $email_template;
    $mail->send();
}

if (isset($_POST['signup'])) {
    if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['Cpassword']) && !empty($_POST['name'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $Cpassword = $_POST['Cpassword'];
        $name = $_POST['name'];
        $verify_token = md5(rand());

        $get_query = "SELECT * FROM user WHERE userEmail = '$email'";
        $result = mysqli_query($conn, $get_query);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('You Cannot Create 2 Accounts using the same Email!')</script>";
            echo "<script>location.href='signup.php';</script>";
        } else {
            if ($password == $Cpassword) {
                $insert_query = "INSERT INTO user (userEmail, verify_token, userPwd, userName, isAdmin) 
                VALUES ('$email', '$verify_token', '$password', '$name', '0')";
                $result = mysqli_query($conn, $insert_query);

                if ($result) {
                    sendemail_verify("$name", "$email", "$verify_token");
                    $_SESSION['status'] = "Account Created Successfully! Please verify your Email Address.";
                    echo "<script>location.href='signup.php';</script>";
                    exit();
                }
            } else {
                echo "<script>alert('Password and Confirm Password does not match, please enter again!')</script>";
                echo "<script>location.href='signup.php';</script>";
            }
        }
    } else {
        echo "<script>alert('Please fill up all the information!')</script>";
        echo "<script>location.href='signup.php';</script>";
    }
}