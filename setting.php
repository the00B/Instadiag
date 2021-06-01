<?php
include_once("includes/header.php");
include_once("includes/barra.php");
include_once("includes/classes/Account.php");
include_once("includes/classes/Constants.php");
include_once("includes/classes/FormSanitizer.php");

$newAccount = new Account($con);
$id = $userLoggedInObj->getId();

if (isset($_POST["submitFoto"])) {

      $nameFoto = $_FILES["foto"]["name"];
      $tempFile = $_FILES["foto"]["tmp_name"];
      $sizeFoto = $_FILES["foto"]["size"];
      $fotoAntigua = $_POST["fotoAntigua"];

      $isTrue = $newAccount->updateFoto($nameFoto, $tempFile, $sizeFoto, $fotoAntigua, $id);

      if ($isTrue) {
            header("Location: setting.php");
      }
}

if (isset($_POST["submitDatos"])) {
      $nombreCompleto = FormSanitizer::sanitizerFormString($_POST["nombreCompleto"]);
      $numero = FormSanitizer::sanitizerFormNumeroEmail($_POST["numero"]);
      $email = FormSanitizer::sanitizerFormNumeroEmail($_POST["email"]);
      $biografia = $_POST["biografia"];

      $isTrue = $newAccount->updateDatos($nombreCompleto, $numero, $email, $biografia, $id);
      if ($isTrue) {
            header("Location: setting.php");
      }
}

if (isset($_POST["submitPassword"])) {
      $oldPass = $_POST["oldPass"];
      $newPass = $_POST["newPass"];
      $newPass2 = $_POST["newPass2"];
      $isTrue = $newAccount->updatePass($oldPass, $newPass, $newPass2, $id);
      if ($isTrue) {
            header("Location: setting.php");
      }
}

?>

<div class="contenedorMaster">
      <div class="contenedor">
            <div class="contenedorSetting">
                  <div class="settingHeader">
                        <img src="<?php echo $userLoggedInObj->getFoto() ?>">
                        <h1><?php echo $userLoggedInObj->getUsername() ?></h1>
                  </div>
                  <form action="setting.php" method="post" enctype="multipart/form-data">
                        <div class="settingPhoto">
                              <?php echo $newAccount->getError(Constants::$photoEmpty); ?>
                              <?php echo $newAccount->getError(Constants::$typeInvalido); ?>
                              <?php echo $newAccount->getError(Constants::$sizeInvalido); ?>
                              <input type="file" name="foto" id="foto">
                              <input type="hidden" name="fotoAntigua" value="<?php echo $userLoggedInObj->getFoto(); ?>">
                              <button type="submit" name="submitFoto" class="boton">Actualizar foto</button>
                        </div>
                  </form>
                  <form action="setting.php" method="post">
                        <div class="settingDatos">
                              <input type="text" name="nombreCompleto" placeholder="Ingrese nombre completo" value="<?php echo $userLoggedInObj->getFullName() ?>">
                              <?php echo $newAccount->getError(Constants::$numeroExistente); ?>
                              <input type="text" name="numero" placeholder="Número telefónico" value="<?php echo $userLoggedInObj->getNumero() ?>">
                              <?php echo $newAccount->getError(Constants::$emailExistente); ?>
                              <input type="email" name="email" placeholder="Correo electrónico" value="<?php echo $userLoggedInObj->getEmail() ?>">
                              <textarea name="biografia" id="bio" placeholder="Ingrese alguna biografía"><?php echo $userLoggedInObj->getBiografia() ?></textarea>
                              <button type="submit" name="submitDatos" class="boton">Editar datos</button>
                        </div>
                  </form>
                  <form action="setting.php" method="post">
                        <div class="settingPassword">
                              <?php echo $newAccount->getError(Constants::$passOldFail); ?>
                              <input type="password" name="oldPass" placeholder="Ingrese su password antiguo">
                              <?php echo $newAccount->getError(Constants::$passNoCoinciden); ?>
                              <input type="password" name="newPass" placeholder="Ingrese su nuevo password">
                              <input type="password" name="newPass2" placeholder="Repita su nuevo password">
                              <button type="submit" name="submitPassword" class="boton">Editar password</button>
                        </div>
                  </form>
            </div>
      </div>
</div>

<?php
include_once("includes/footer.php");
?>