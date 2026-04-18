<?php
session_start();

if (!empty($_SESSION['name'])) {
    if ($_SESSION['name'] != "admin") {
        $username = $_SESSION['name'];
        $userId = $_SESSION['userId'];
    } else {
        $username = "Guest";
        $userId = 0;
    }
} else {
    $username = "Guest";
    $userId = 0;
}