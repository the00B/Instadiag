<?php
include_once("includes/config.php");
include_once("includes/classes/User.php");
$usernameLoggedIn = User::isLoggedIn() ? $_SESSION["userLoggedIn"] : "";

$userLoggedInObj = new User($con, $usernameLoggedIn);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Cookie|Roboto:300,400&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/6bc6a5be32.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/main.css">
    <title>Instadiag &#128153;</title>
</head>

<body>