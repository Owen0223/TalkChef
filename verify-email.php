<?php
require_once 'Connection.php';
session_start();
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $verify_query = "SELECT verify_token, verify_status FROM user WHERE verify_token='$token' LIMIT 1";
    $verify_query_run = mysqli_query($conn, $verify_query);

    if (mysqli_num_rows($verify_query_run) > 0) {
        $row = mysqli_fetch_array($verify_query_run);

        if ($row['verify_status'] == '0') {
            $clicked_token = $row['verify_token'];
            $update_query = "UPDATE user SET verify_status='1' WHERE verify_token='$clicked_token' LIMIT 1";
            $update_query_run = mysqli_query($conn, $update_query);

            if ($update_query_run) {
                echo "<script>alert('Your Account has been verified successfully!')</script>";
                echo "<script>location.href='signin.php';</script>";
                exit();
            } else {
                echo "<script>alert('Verification Failed!')</script>";
                echo "<script>location.href='signin.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Email already verified. Please Sign In.')</script>";
            echo "<script>location.href='signin.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('This token does not exists')</script>";
        echo "<script>location.href='signin.php';</script>";
    }
} else {
    echo "<script>alert('Not Allowed!')</script>";
    echo "<script>location.href='signin.php';</script>";
}