<?php
class UserCardProvider
{
    private $con, $userLoggedInObj;
    public function __construct($con, $userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function getCards($term)
    {
        if ($term == "") {
            return "Por favor ingrese algun nombre";
        }
        $query = $this->con->prepare("SELECT * FROM users WHERE usuario LIKE CONCAT('%', :term, '%') OR nombreCompleto LIKE CONCAT('%', :term, '%') ORDER BY usuario");
        $query->bindParam(":term", $term);
        $query->execute();

        $usuarios = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($usuarios, $row);
        }
        if (sizeof($usuarios) == 0) {
            return "Usuario no encontrado";
        }
        return $this->generateCard($usuarios);
    }

    private function generateCard(&$usuarios)
    {
        $idUserFrom = $this->userLoggedInObj->getId();
        $html = "";
        for ($i = 0; $i < sizeof($usuarios); $i++) {
            $foto = $usuarios[$i]["foto"];
            $username = $usuarios[$i]["usuario"];
            $nombre = $usuarios[$i]["nombreCompleto"];
            $idUserTo = $usuarios[$i]["id"];

            $estaSiguiendo = $this->userLoggedInObj->isFollowing($idUserTo);
            $buttonText = $estaSiguiendo ? "Siguiendo" : "Seguir";
            $class = $estaSiguiendo ? "follow" : "";
            $disabled = ($idUserTo == $idUserFrom) ? "display:none;" : "";
            $actionButton = "follow($idUserTo, $idUserFrom, this)";

            $html .= "<div class='userCard'>
                        <a href='profile.php?u=$username'>
                            <img src='$foto'>
                        </a>
                        <h4>$username</h4>
                        <span>$nombre</span>
                        <button type='button' class='boton $class' onclick='$actionButton' style='$disabled'>$buttonText</button>
                    </div>";
        }

        return $html;
    }
}
