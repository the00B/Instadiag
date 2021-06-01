<?php
include_once("FotoResize.php");
class UploadFoto
{

    private $errorArray = array();
    private $allowedTypes = array("jpeg", "jpg", "png", "jpe");
    private $sizeLimit = 5000000;


    public function __construct($con)
    {
        $this->con = $con;
    }

    public function register($fotoName, $tempFile, $size, $descripcion, $idUser)
    {
        $pathDoc = "assets/img/post/";
        if (!is_dir($pathDoc)) {
            mkdir($pathDoc, 0775, true);
        }
        $tempFilePath = $pathDoc . uniqid() . basename($fotoName);
        $tempFilePath = str_replace(" ", "", $tempFilePath);
        $fotoType = pathinfo($tempFilePath, PATHINFO_EXTENSION);
        if (!$this->validateType($fotoType)) {
            array_push($this->errorArray, Constants::$typeInvalido);
            return;
        }
        if (!$this->validateSizeLimit($size)) {
            array_push($this->errorArray, Constants::$sizeInvalido);
            return;
        }

        if (empty($this->errorArray)) {
            if (move_uploaded_file($tempFile, $tempFilePath)) {
                $explode = explode(".", $tempFilePath);
                $nuevoPath = $explode[0] . '.webp';
                $query = $this->con->prepare("INSERT INTO photos (uploadedBy, description, filePath) VALUES (:u, :d, :f)");
                $query->bindParam(":u", $idUser);
                $query->bindParam(":d", $descripcion);
                $query->bindParam(":f", $nuevoPath);
                $isTrue = $query->execute();
                if ($isTrue) {
                    FotoResize::fotoResize($tempFilePath, 800, $fotoType);
                    return true;
                }
            }
        }
    }

    private function validateType($type)
    {
        $lower = strtolower($type);
        return in_array($lower, $this->allowedTypes);
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
