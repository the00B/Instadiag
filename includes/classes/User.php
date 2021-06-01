<?php

class User
{

    public function __construct($con, $usuario)
    {
        $this->con = $con;

        $query = $this->con->prepare("SELECT * FROM users WHERE usuario = :usuario");
        $query->bindParam(":usuario", $usuario);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION["userLoggedIn"]);
    }

    public function getId()
    {
        return $this->sqlData["id"];
    }
    public function getUsername()
    {
        return $this->sqlData["usuario"];
    }
    public function getFullName()
    {
        return $this->sqlData["nombreCompleto"];
    }
    public function getNumero()
    {
        return $this->sqlData["numero"];
    }
    public function getEmail()
    {
        return $this->sqlData["email"];
    }
    public function getBiografia()
    {
        return $this->sqlData["biografia"];
    }
    public function getFoto()
    {
        return $this->sqlData["foto"];
    }

    public function getFollowing()
    {
        $id = $this->getId();
        $query = $this->con->prepare("SELECT follow.userTo as 'userTo', users.usuario as 'usuario' FROM follow INNER JOIN users ON users.id = follow.userTo WHERE follow.userFrom = :userFrom");
        $query->bindParam(":userFrom", $id);

        $query->execute();

        $following = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $user = new User($this->con, $row["usuario"]);
            array_push($following, $user);
        }
        return $following;
    }

    public function isFollowing($idUserTo)
    {
        $username = $this->getId();
        $query = $this->con->prepare("SELECT * FROM follow WHERE userTo = :userTo AND userFrom = :userFrom");
        $query->bindParam(":userTo", $idUserTo);
        $query->bindParam(":userFrom", $username);
        $query->execute();
        return $query->rowCount() > 0;
    }

    public function getSeguidoresCount()
    {
        $username = $this->getId();
        $query = $this->con->prepare("SELECT * FROM follow WHERE userTo = :userTo");
        $query->bindParam(":userTo", $username);
        $query->execute();
        return $query->rowCount();
    }
    public function getSeguidosCount()
    {
        $username = $this->getId();
        $query = $this->con->prepare("SELECT * FROM follow WHERE userFrom = :userFrom");
        $query->bindParam(":userFrom", $username);
        $query->execute();
        return $query->rowCount();
    }
    public function getPublicacionesCount()
    {
        $username = $this->getId();
        $query = $this->con->prepare("SELECT * FROM photos WHERE uploadedBy = :uploadedBy");
        $query->bindParam(":uploadedBy", $username);
        $query->execute();
        return $query->rowCount();
    }
}
