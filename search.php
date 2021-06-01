<?php
include_once("includes/header.php");
include_once("includes/barra.php");
include_once("includes/classes/UserCardProvider.php");

if (!User::isLoggedIn()) {
    header("Location: signIn.php");
}

if (isset($_GET["u"])) {
    $term = $_GET["u"];

    $newUserCardProvider = new UserCardProvider($con, $userLoggedInObj);
}
?>

<div class="contenedorMaster">
    <div class="contenedor">
        <div class="contenedorCardsUsers">
            <?php echo $newUserCardProvider->getCards($term); ?>
        </div>
    </div>
</div>

<?php
include_once("includes/footer.php");
?>