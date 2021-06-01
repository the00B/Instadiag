<?php

class Comment
{
    private $con, $userLoggedInObj, $photoId;

    public function __construct($con, $input, $userLoggedInObj, $photoId)
    {
        if (!is_array($input)) {
            $query = $con->prepare("SELECT * FROM comments INNER JOIN users ON users.id = comments.postedBy WHERE comments.id = :id");
            $query->bindParam(":id", $input);
            $query->execute();

            $input = $query->fetch(PDO::FETCH_ASSOC);
        }

        $this->sqlData = $input;
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
        $this->photoId = $photoId;
    }

    public function getId()
    {
        return  $this->sqlData["id"];
    }
    public function getPostedBy()
    {
        return  $this->sqlData["postedBy"];
    }
    public function getUsername()
    {
        return  $this->sqlData["usuario"];
    }
    public function getFotoComment()
    {
        return  $this->sqlData["foto"];
    }
    public function getPhotoId()
    {
        return  $this->sqlData["photoId"];
    }
    public function getBody()
    {
        return  $this->sqlData["body"];
    }
    public function getDatePosted()
    {
        return  $this->time_elapsed_string($this->sqlData["datePosted"]);
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

    public function create()
    {
        $body = $this->getBody();
        $usuario = $this->getUsername();
        $datePosted = $this->getDatePosted();

        $linkUsuario = "profile.php?u=" . $usuario;

        $html = "
                <div class='comment'>
                    <div>
                        <a href='$linkUsuario'>$usuario</a>
                        <span>$body</span>
                    </div>
                    <div>
                        <span>$datePosted</span>
                    </div>

                </div>
        ";
        return $html;
    }
    public function createSelfComment()
    {
        $body = $this->getBody();
        $usuario = $this->getUsername();
        $datePosted = $this->getDatePosted();
        $foto = $this->getFotoComment();

        $html = "
                <div class='comentarioPost'>
                    <a href='profile.php?u=$usuario'>
                        <img src='$foto'>
                    </a>
                    <div>
                        <p><a href='profile.php?u=$usuario'><b>$usuario</b></a></p>
                        <span>$body</span>
                        <span>$datePosted</span>
                    </div>
                </div>
        ";
        return $html;
    }
}
