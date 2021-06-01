<?php

include_once("FotoResize.php");
class Account
{
    private $errorArray = array();
    private $allowedTypes = array("jpeg", "jpg", "png", "jpe");
    private $sizeLimit = 5000000; //5MB

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function register($numeroEmail, $nombreCompleto, $usuario, $password)
    {
        $this->validateNumeroEmail($numeroEmail);
        $this->validateUser($usuario);
        $this->validatePassword($password);

        if (empty($this->errorArray)) {
            return $this->insertUserDetails($numeroEmail, $nombreCompleto, $usuario, $password);
        } else {
            return false;
        }
    }

    public function login($usuario, $password)
    {
        $query = $this->con->prepare("SELECT * FROM users WHERE usuario = :usuario");
        $query->bindParam(":usuario", $usuario);
        $query->execute();

        if ($query->rowCount() == 1) {
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $passwordHashed = $row["password"];

            if (password_verify($password, $passwordHashed)) {
                return true;
            } else {
                array_push($this->errorArray, Constants::$loginFailed);
                return false;
            }
        } else {
            array_push($this->errorArray, Constants::$loginFailed);
            return false;
        }
    }
    private function insertUserDetails($numeroEmail, $nombreCompleto, $usuario, $password)
    {

        $option = array(
            'cost' => 12
        );
        $passhashed = password_hash($password, PASSWORD_BCRYPT, $option);
        $numeroEmailVal = (is_numeric($numeroEmail)) ? "numero" : "email";
        $avatar = $this->generarAvatar($nombreCompleto);

        $query = $this->con->prepare("INSERT INTO users ( $numeroEmailVal , nombreCompleto, usuario, password, foto) VALUES (:nE, :nC, :u, :pass, :foto)");
        $query->bindParam(":nE", $numeroEmail);
        $query->bindParam(":nC", $nombreCompleto);
        $query->bindParam(":u", $usuario);
        $query->bindParam(":pass", $passhashed);
        $query->bindParam(":foto", $avatar);

        return $query->execute();
    }

    private function generarAvatar($nC)
    {
        $directorio = "assets/img/avatar/";
        if (!is_dir($directorio)) {
            mkdir($directorio, 0775, true);
        }

        $letra =  substr($nC, 0, 1);

        $red = rand(88, 200);
        $green = rand(88, 200);
        $blue = rand(88, 200);

        $avatar = imagecreatetruecolor(50, 50);
        $bgColor = imagecolorallocate($avatar, $red, $green, $blue);
        imagefill($avatar, 0, 0, $bgColor);
        $coloText = imagecolorallocate($avatar, 255, 255, 255);
        $font = imageloadfont('assets/font/arial-reg-20.gdf');
        imagestring($avatar, $font, 14, 6, $letra, $coloText);
        $url = $directorio . uniqid() . ".webp";
        imagewebp($avatar, $url);
        return $url;
    }




    private function validatePassword($p)
    {
        if ($p == "") {
            array_push($this->errorArray, Constants::$passwordEmpty);
            return;
        }
    }

    private function validateUser($u)
    {
        if (strlen($u) > 20 || strlen($u) < 5) {
            array_push($this->errorArray, Constants::$numeroCarateresInvalido);
            return;
        }

        $query = $this->con->prepare("SELECT usuario FROM users WHERE usuario = :c");
        $query->bindParam(":c", $u);
        $query->execute();

        if ($query->rowCount() == 1) {
            array_push($this->errorArray, Constants::$userRegistrado);
            return;
        }
    }
    private function validateNumeroEmail($nE)
    {
        if (is_numeric($nE)) {
            $query = $this->con->prepare("SELECT numero FROM users WHERE numero = :numero");
            $query->bindParam(":numero", $nE);
            $query->execute();

            if ($query->rowCount() == 1) {
                array_push($this->errorArray, Constants::$numeroRegistrado);
                return;
            }
        } else {
            if (!filter_var($nE, FILTER_VALIDATE_EMAIL)) {
                array_push($this->errorArray, Constants::$errorEmail);
                return;
            } else {
                $query = $this->con->prepare("SELECT email FROM users WHERE email = :email");
                $query->bindParam(":email", $nE);
                $query->execute();
                if ($query->rowCount() == 1) {
                    array_push($this->errorArray, Constants::$emailRegistrado);
                    return;
                }
            }
        }
    }

    public function updateFoto($nameFoto, $tempFile, $sizeFoto, $fotoAntigua, $id)
    {
        if ($nameFoto == null) {
            array_push($this->errorArray, Constants::$photoEmpty);
        } else {
            $directorio = "assets/img/avatar/";
            if (!is_dir($directorio)) {
                mkdir($directorio, 0775, true);
            }

            $tempFilePath = $directorio . uniqid() . basename($nameFoto);
            $tempFilePath = str_replace(" ", "", $tempFilePath);

            $fotoType = strtolower(pathinfo($tempFilePath, PATHINFO_EXTENSION));

            if (!$this->validateType($fotoType)) {
                array_push($this->errorArray, Constants::$typeInvalido);
                return;
            }
            if (!$this->validateSizeLimit($sizeFoto)) {
                array_push($this->errorArray, Constants::$sizeInvalido);
                return;
            }

            if (empty($this->errorArray)) {
                if (move_uploaded_file($tempFile, $tempFilePath)) {
                    unlink(realpath($fotoAntigua));

                    $explode = explode(".", $tempFilePath);
                    $nuevoPath = $explode[0] . '.webp';

                    $query = $this->con->prepare("UPDATE users SET foto = :foto WHERE id = :id");
                    $query->bindParam(":foto", $nuevoPath);
                    $query->bindParam(":id", $id);

                    $isTrue = $query->execute();
                    if ($isTrue) {
                        FotoResize::fotoResize($tempFilePath, 300, $fotoType);
                        return true;
                    }
                }
            }
        }
    }

    public function updateDatos($nombreCompleto, $numero, $email, $biografia, $id)
    {
        if (!$this->validateNumberUpdate($numero, $id)) {
            array_push($this->errorArray, Constants::$numeroExistente);
        }
        if (!$this->validateEmailUpdate($email, $id)) {
            array_push($this->errorArray, Constants::$emailExistente);
        }

        if (empty($this->errorArray)) {
            $query = $this->con->prepare("UPDATE users SET nombreCompleto = :nC, numero = :numero, email = :email, biografia = :bio WHERE id = :id");
            $query->bindParam(":nC", $nombreCompleto);
            $query->bindParam(":numero", $numero);
            $query->bindParam(":email", $email);
            $query->bindParam(":bio", $biografia);
            $query->bindParam(":id", $id);

            return  $query->execute();
        }
    }

    public function updatePass($oldPass, $newPass, $newPass2, $id)
    {
        $this->validateOldPass($oldPass, $id);
        $this->validatePassNew($newPass, $newPass2);

        if (empty($this->errorArray)) {
            $option = array(
                'cost' => 12
            );
            $passHashed = password_hash($newPass, PASSWORD_BCRYPT, $option);

            $query = $this->con->prepare("UPDATE users SET password = :pass WHERE id = :id");
            $query->bindParam(":pass", $passHashed);
            $query->bindParam(":id", $id);

            return  $query->execute();
        }
    }

    private function validatePassNew($newPass, $newPass2)
    {
        if ($newPass != $newPass2) {
            array_push($this->errorArray, Constants::$passNoCoinciden);
            return;
        }
    }
    private function validateOldPass($old, $id)
    {
        $query = $this->con->prepare("SELECT * FROM users WHERE id=:id");
        $query->bindParam(":id", $id);
        $query->execute();

        if ($query->rowCount() == 1) {
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $passwordHashed = $row["password"];

            if (!password_verify($old, $passwordHashed)) {
                array_push($this->errorArray, Constants::$passOldFail);
                return;
            }
        }
    }

    public function validateEmailUpdate($email, $id)
    {
        $query = $this->con->prepare("SELECT * FROm users WHERE email = :email");
        $query->bindParam(":email", $email);
        $query->execute();

        if ($query->rowCount()  > 0) {
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $idResult = $result["id"];

            if ($idResult == $id) {
                $success = true;
            } else {
                $success = false;
            }
        } else {
            $success = true;
        }

        return $success;
    }

    public function validateNumberUpdate($numero, $id)
    {
        $query = $this->con->prepare("SELECT * FROm users WHERE numero = :numero");
        $query->bindParam(":numero", $numero);
        $query->execute();

        if ($query->rowCount()  > 0) {
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $idResult = $result["id"];

            if ($idResult == $id) {
                $success = true;
            } else {
                $success = false;
            }
        } else {
            $success = true;
        }

        return $success;
    }

    private function validateType($type)
    {
        return in_array($type, $this->allowedTypes);
    }
    private function validateSizeLimit($size)
    {
        return $size <= $this->sizeLimit;
    }

    public function getError($error)
    {
        if (in_array($error, $this->errorArray)) {
            return "<span class='errorMessage'>$error</span>";
        }
    }
}
