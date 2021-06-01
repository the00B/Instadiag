<?php
include_once("includes/header.php");
include_once("includes/barra.php");
include_once("includes/classes/UploadFoto.php");
include_once("includes/classes/Constants.php");

if (!User::isLoggedIn()) {
    header("Location: signIn.php");
}

$newUploadFoto = new UploadFoto($con);

if (isset($_POST["submitButton"])) {
    $fotoName = $_FILES["foto"]["name"];
    $tempFile = $_FILES["foto"]["tmp_name"];
    $size = $_FILES["foto"]["size"];
    $descripcion = $_POST["descripcion"];
    $idUser = $userLoggedInObj->getId();

    $true = $newUploadFoto->register($fotoName, $tempFile, $size, $descripcion, $idUser);

    if ($true) {
        header("Location: index.php");
    }
}



?>
<div class="contenedorMaster">
    <div class="contenedor">
        <div class="contenedorUpload">
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <?php echo $newUploadFoto->getError(Constants::$typeInvalido); ?>
                <?php echo $newUploadFoto->getError(Constants::$sizeInvalido); ?>
                <input type="file" name="foto" id="foto" required>
                <input type="text" name="descripcion" placeholder="Ingrese una descripción...">
                <button type="submit" name="submitButton" class="boton">Subir foto para diagnóstico &#127973;</button>
            </form>
        </div>
    </div>
</div>

<?php
include_once("includes/footer.php");
?>