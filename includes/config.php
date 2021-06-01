<?php

ob_start();
session_start();

date_default_timezone_set("Europe/Madrid");

try {
    $con = new PDO("mysql:dbname=ddb166488;host=bbdd.instadiag.es", "ddb166488", "wV#21uQ50nxe%q9d");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch (PDOException $e) {
    echo "Connection fail: " . $e->getMessage();
}
