<?php

require_once("../includes/config.php");
require_once("../includes/classes/Photo.php");
require_once("../includes/classes/User.php");

$username = $_SESSION["userLoggedIn"];
$photoId = $_POST["photoId"];


$userLoggedInObj = new User($con, $username);

$newPhoto = new Photo($con, $photoId, $userLoggedInObj);
echo $newPhoto->like();
