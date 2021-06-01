<?php
include_once("includes/header.php");
include_once("includes/barra.php");
include_once("includes/classes/Photo.php");
include_once("includes/classes/PostProvider.php");
include_once("includes/classes/Comment.php");

if (!User::isLoggedIn()) {
    header("Location: signIn.php");
}

if (isset($_GET["p"])) {
    $postId = $_GET["p"];
} else {
    echo "Error";
    exit;
}
$photo = new Photo($con, $postId, $userLoggedInObj);
$newPostProvider = new PostProvider($con, $userLoggedInObj)
?>

<div class="contenedorMaster">
    <div class="contenedor">
        <div class="contenedorPost">
            <?php echo $newPostProvider->createPost($photo); ?>
        </div>
    </div>
</div>

<?php
include_once('includes/footer.php');
?>