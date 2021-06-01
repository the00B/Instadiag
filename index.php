<?php
include_once("includes/header.php");
include_once("includes/barra.php");
include_once("includes/classes/UserDetailsProvider.php");
include_once("includes/classes/FollowProvider.php");
include_once("includes/classes/Photo.php");
include_once("includes/classes/FeedProvider.php");
include_once("includes/classes/Comment.php");

if (!User::isLoggedIn()) {
    header("Location: signIn.php");
}

$newUserDetailsProvider = new UserDetailsProvider($userLoggedInObj);

$newFollowProvider = new FollowProvider($con, $userLoggedInObj);
$photos = $newFollowProvider->getPhotos();

$newFeedProvider = new FeedProvider($con, $userLoggedInObj);

?>
<div class="contenedorMaster">
    <div class="contenedor">
        <div class="feed">
            <?php echo $newFeedProvider->createCardFeed($photos); ?>
        </div>
        <div class="aside" id="aside">
            <?php echo $newUserDetailsProvider->userDetails(); ?>
            <div class="seguidos">
                <div class="titleSeguido">
                    <p>Siguiendo</p>
                </div>
                <div class="userSeguidos">
                    <?php echo $newUserDetailsProvider->following() ?>
                </div>
            </div>
            <div class="pieAside">
                <p>Privacidad - Condiciones -  Perfiles - Hashtags - API</p>
            </div>
        </div>
    </div>
</div>

<?php
include_once("includes/footer.php");
?>