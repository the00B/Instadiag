<?php

class Photo
{

    public function __construct($con, $input, $userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;

        if (is_array($input)) {
            $this->sqlData = $input;
        } else {
            $query = $this->con->prepare("SELECT photos.id as 'idFoto', users.usuario as 'usuario', users.foto as 'foto', photos.description as 'description', photos.filePath as 'filePath', photos.uploadDate as 'uploadDate' FROM photos INNER JOIN users ON users.id = photos.uploadedBy WHERE photos.id = :id");
            $query->bindParam(":id", $input);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function getId()
    {
        $id = (isset($this->sqlData["idFoto"])) ? $this->sqlData["idFoto"] : $this->sqlData["id"];
        return $id;
    }
    public function getUsername()
    {
        return $this->sqlData["usuario"];
    }
    public function getFullName()
    {
        return $this->sqlData["nombreCompleto"];
    }
    public function getFotoUser()
    {
        return $this->sqlData["foto"];
    }
    public function getUploadedBy()
    {
        return $this->sqlData["uploadedBy"];
    }
    public function getDescription()
    {
        return $this->sqlData["description"];
    }
    public function getFilePath()
    {
        return $this->sqlData["filePath"];
    }
    public function getUploadDate()
    {
        return $this->time_elapsed_string($this->sqlData["uploadDate"]);
    }

    public function getLikes()
    {
        $photoId = $this->getId();

        $query = $this->con->prepare("SELECT count(*) as 'count' FROM likes WHERE photoId = :photoId");
        $query->bindParam(":photoId", $photoId);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        return $data["count"];
    }

    public function getNumberOfComments()
    {
        $id = $this->getId();

        $query = $this->con->prepare("SELECT * FROM comments WHERE photoId = :photoId");
        $query->bindParam(":photoId", $id);
        $query->execute();

        return $query->rowCount();
    }

    public function wasLikedBy()
    {
        $id = $this->getId();

        $usuario = $this->userLoggedInObj->getId();
        $query = $this->con->prepare("SELECT * FROM likes WHERE userId = :userId AND photoId = :photoId");
        $query->bindParam(":userId", $usuario);
        $query->bindParam(":photoId", $id);
        $query->execute();
        return $query->rowCount() > 0;
    }

    public function getComments()
    {
        $id = $this->getId();
        $query = $this->con->prepare("SELECT * FROM comments INNER JOIN users ON users.id = comments.postedBy WHERE photoId = :photoId ORDER BY datePosted DESC LIMIT 2");
        $query->bindParam(":photoId", $id);
        $query->execute();

        $comments = array();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $comment = new Comment($this->con, $row, $this->userLoggedInObj, $id);
            array_push($comments, $comment);
        }
        return $comments;
    }

    public function getCommentsTotal()
    {
        $id = $this->getId();
        $query = $this->con->prepare("SELECT * FROM comments INNER JOIN users ON users.id = comments.postedBy WHERE photoId = :photoId ORDER BY datePosted DESC");
        $query->bindParam(":photoId", $id);
        $query->execute();

        $comments = array();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $comment = new Comment($this->con, $row, $this->userLoggedInObj, $id);
            array_push($comments, $comment);
        }
        return $comments;
    }

    public function like()
    {
        $id = $this->getId();
        $userId = $this->userLoggedInObj->getId();

        if ($this->wasLikedBy()) {
            $query = $this->con->prepare("DELETE FROM likes WHERE userId=:userId AND photoId=:photoId");
            $query->bindParam(":userId", $userId);
            $query->bindParam(":photoId", $id);
            $query->execute();

            $result = array(
                "likes" => -1
            );

            return json_encode($result);
        } else {
            $query = $this->con->prepare("INSERT INTO likes (userId, photoId) VALUES (:userId, :photoId)");
            $query->bindParam(":userId", $userId);
            $query->bindParam(":photoId", $id);
            $query->execute();

            $result = array(
                "likes" => 1
            );

            return json_encode($result);
        }
    }

    function time_elapsed_string($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'año',
            'm' => 'mes',
            'w' => 'semana',
            'd' => 'día',
            'h' => 'hora',
            'i' => 'minuto',
            's' => 'segundo',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                if ($k == 'm') {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 'es' : '');
                } else {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                }
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? 'hace' . ' ' . implode(', ', $string)   : 'hace instantes';
    }
}
