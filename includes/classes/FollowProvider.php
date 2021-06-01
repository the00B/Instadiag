<?php

class FollowProvider
{

    public function __construct($con, $userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function getPhotos()
    {
        $photos = array();
        $following = $this->userLoggedInObj->getFollowing();

        if (sizeof($following) > 0) {
            $condition = "";
            $i = 0;
            while ($i < sizeof($following)) {
                if ($i == 0) {
                    $condition .= "WHERE photos.uploadedBy=?";
                } else {
                    $condition .= "OR photos.uploadedBy=?";
                }
                $i++;
            }
            $idUser = $this->userLoggedInObj->getId();

            $photoSql = "SELECT photos.id as 'idFoto', users.usuario as 'usuario', users.foto as 'foto', users.nombreCompleto as 'nombreCompleto', photos.description as 'description', photos.filePath as 'filePath', photos.uploadDate as 'uploadDate' FROM photos INNER JOIN users ON users.id = photos.uploadedBy $condition OR photos.uploadedBy = '$idUser' ORDER BY uploadDate DESC LIMIT 40";
            $photoQuery = $this->con->prepare($photoSql);

            $i = 1;
            foreach ($following as $follow) {
                $idUser = $follow->getId();
                $photoQuery->bindValue($i, $idUser);
                $i++;
            }
            $photoQuery->execute();
            while ($row = $photoQuery->fetch(PDO::FETCH_ASSOC)) {
                $photo = new Photo($this->con, $row, $this->userLoggedInObj);
                array_push($photos, $photo);
            }

            return $photos;
        } else {
            $idUser = $this->userLoggedInObj->getId();

            $photoSql = "SELECT photos.id as 'idFoto', users.usuario as 'usuario', users.foto as 'foto', users.nombreCompleto as 'nombreCompleto', photos.description as 'description', photos.filePath as 'filePath', photos.uploadDate as 'uploadDate' FROM photos INNER JOIN users ON users.id = photos.uploadedBy WHERE photos.uploadedBy = '$idUser' ORDER BY uploadDate DESC LIMIT 20";
            $photoQuery = $this->con->prepare($photoSql);
            $photoQuery->execute();

            while ($row = $photoQuery->fetch(PDO::FETCH_ASSOC)) {
                $photo = new Photo($this->con, $row, $this->userLoggedInObj);
                array_push($photos, $photo);
            }

            return $photos;
        }
    }
}
