<?php

require_once("../includes/config.php");
require_once("../includes/classes/Comment.php");
require_once("../includes/classes/User.php");

if (isset($_POST["commentText"]) && isset($_POST["postedBy"]) && isset($_POST["photoId"])) {
    $userLoggedInObj = new User($con, $_SESSION["userLoggedIn"]);
    $postedBy = $_POST["postedBy"];
    $photoId = $_POST["photoId"];
    $commentText = $_POST["commentText"];

    $query = $con->prepare("INSERT INTO comments (postedBy, photoId, body) VALUES (:postedBy, :photoId, :body)");
    $query->bindParam(":postedBy", $postedBy);
    $query->bindParam(":photoId", $photoId);
    $query->bindParam(":body", $commentText);
    $query->execute();

    $newComment = new Comment($con, $con->lastInsertId(), $userLoggedInObj, $photoId);
    echo $newComment->createSelfComment();
}
