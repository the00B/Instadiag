<?php
require_once("../includes/config.php");

if (isset($_POST["userTo"]) && isset($_POST["userFrom"])) {
    $userTo = $_POST["userTo"];
    $userFrom = $_POST["userFrom"];

    $query = $con->prepare("SELECT * FROM follow WHERE userTo = :userTo AND userFrom = :userFrom");
    $query->bindParam(":userTo", $userTo);
    $query->bindParam(":userFrom", $userFrom);
    $query->execute();

    if ($query->rowCount() == 0) {
        $query = $con->prepare("INSERT INTO follow (userTo, userFrom) VALUES (:userTo, :userFrom)");
        $query->bindParam(":userTo", $userTo);
        $query->bindParam(":userFrom", $userFrom);
        $query->execute();

        $result = array(
            'follow' => 'add'
        );

        echo json_encode($result);
    } else {
        $query = $con->prepare("DELETE FROM follow WHERE userTo = :userTo AND userFrom = :userFrom");
        $query->bindParam(":userTo", $userTo);
        $query->bindParam(":userFrom", $userFrom);
        $query->execute();

        $result = array(
            'follow' => 'less'
        );

        echo json_encode($result);
    }
}
