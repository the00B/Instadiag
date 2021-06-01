<?php
include_once("includes/config.php");
include_once("includes/classes/User.php");
$usernameLoggedIn = User::isLoggedIn() ? $_SESSION["userLoggedIn"] : "";

$userLoggedInObj = new User($con, $usernameLoggedIn);

?>
<div class="barra" id="barra">
    <div class="contenedor">
        <div class="logo">
            <h1><a href="index.php">Instadiag &#128153;</a></h1>
        </div>
        <div>
            <form action="search.php" method="get">
                <div class="buscador">
                    <i class="fas fa-search"></i>
                    <input type="text" name="u" placeholder="Buscar">
                </div>
            </form>
        </div>
        <div class="opcionesBarra">
            <a href="upload.php">
                <i class="fas fa-upload"></i>
            </a>
            <a href="profile.php?u=<?php echo $userLoggedInObj->getUsername(); ?>">
                <i class="far fa-user"></i>
            </a>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</div>