<?php
include_once("includes/config.php");
include_once("includes/classes/InicioFromProvider.php");
include_once("includes/classes/FormSanitizer.php");
include_once("includes/classes/Account.php");
include_once("includes/classes/Constants.php");

$newInicioFromProvider = new InicioFromProvider();

$newAccount = new Account($con);

if (isset($_POST["botonRegistro"])) {
    $numeroEmail = FormSanitizer::sanitizerFormNumeroEmail($_POST["numeroEmail"]);
    $nombreCompleto = FormSanitizer::sanitizerFormString($_POST["nombreCompleto"]);
    $usuario = FormSanitizer::sanitizerFormUsername($_POST["usuario"]);
    $password = FormSanitizer::sanitizerFormPassword($_POST["password"]);

    $true = $newAccount->register($numeroEmail, $nombreCompleto, $usuario, $password);

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
    <title>Sign Up</title>

    <style type="text/css">

        ::-webkit-scrollbar {
            display: none;
        }

        ::-moz-scrollbar
        {
            display: none;
        }

        ::-ns-scrollbar
        {
            display: none;
        }

        ::-o-scrollbar
        {
            display: none;
        }

    </style>
</head>

<body class="fondo">
    <div class="contenedorPrincipal">
        <img src="assets/img/main/main-instadiag-iphone12.png" alt="imagen inicio">
        <div class="contenedorCards">
            <div class="card">
                <div class="title">
                    <h1>Instadiag &#128153;</h1>
                    <span><b>¿Eres médico?</b> Regístrate para opinar sobre diagnósticos.</span>
                </div>
                <form action="signUp.php" method="post">
                    <div class="formulario">
                        <?php
                        echo $newAccount->getError(Constants::$emailRegistrado);
                        echo $newAccount->getError(Constants::$errorEmail);
                        echo $newAccount->getError(Constants::$numeroCarateresInvalido);
                        echo $newAccount->getError(Constants::$numeroRegistrado);
                        echo $newAccount->getError(Constants::$passwordEmpty);
                        echo $newAccount->getError(Constants::$userRegistrado);
                        echo $newInicioFromProvider->formRegister();
                        ?>
                    </div>
                </form>
                <div class="aviso">
                    <p>Al registrarte, aceptas nuestras <b>Condiciones, la política de datos y la política de cookies</b></p>
                </div>
            </div>
            <div class="card secundario">
                <p>¿Tienes una cuenta? <a href="signIn.php">Iniciar sesión</a></p>
            </div>
        </div>
    </div>

</body>

</html>