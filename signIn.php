<?php
include_once("includes/config.php");
include_once("includes/classes/InicioFromProvider.php");
include_once("includes/classes/FormSanitizer.php");
include_once("includes/classes/Account.php");
include_once("includes/classes/Constants.php");

$newInicioFromProvider = new InicioFromProvider();
$newAccount = new Account($con);

if (isset($_POST["botonLogin"])) {
    $usuario = FormSanitizer::sanitizerFormUsername($_POST["usuario"]);
    $password = FormSanitizer::sanitizerFormPassword($_POST["password"]);

    $true = $newAccount->login($usuario, $password);

    if ($true) {
        $_SESSION["userLoggedIn"] = $usuario;
        header("Location: index.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Cookie|Roboto:300,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
    <title>Sign In</title>
</head>

<body class="fondo">
    <div class="contenedorPrincipal">
        <img src="assets/img/main/main-instadiag-iphone12.png " alt="imagen inicio">
        <div class="contenedorCards">
            <div class="card">
                <div class="title">
                    <h1>Instadiag &#128153;</h1>
                    <span>¿Eres médico?</b> Regístrate para opinar sobre diagnósticos.</span>
                </div>
                <form action="signIn.php" method="post">
                    <div class="formulario">
                        <?php
                        echo $newAccount->getError(Constants::$loginFailed);
                        echo $newInicioFromProvider->formLogin();
                        ?>
                    </div>
                </form>
                <div class="aviso">
                    <p>¿Olvidaste los datos de acceso?</p>
                </div>
            </div>
            <div class="card secundario">
                <p>¿No tienes una cuenta? <a href="signUp.php">Regístrate</a></p>
            </div>
        </div>
    </div>
</body>

</html>