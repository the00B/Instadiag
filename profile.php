<?php
include_once("includes/header.php");
include_once("includes/barra.php");
include_once("includes/classes/ProfileProvider.php");
include_once("includes/classes/Photo.php");

if (!User::isLoggedIn()) {
    header("Location: signIn.php");
}

if (isset($_GET["u"])) {
    $ProfileUsername = $_GET["u"];
} else {
    echo "Error";
    exit;
}
$profileGenerator = new ProfileProvider($con, $userLoggedInObj, $ProfileUsername);
?>

<div class="contenedorMaster">
    <div class="contenedor">
        <div class="contenedorMasterProfile">
            <?php echo $profileGenerator->create(); ?>
        </div>
    </div>
</div>

<?php
include_once("includes/footer.php");
?>