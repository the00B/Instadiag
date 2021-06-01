<?php
class ProfileData
{
    private $con, $profileUserObj;
    public function __construct($con, $profileUsername)
    {
        $this->con = $con;
        $this->profileUserObj = new User($con, $profileUsername);
    }

    public function getProfileUserObj()
    {
        return $this->profileUserObj;
    }
    public function getProfileUsername()
    {
        return $this->profileUserObj->getUsername();
    }
    public function getProfileId()
    {
        return $this->profileUserObj->getId();
    }
    public function getProfileFullName()
    {
        return $this->profileUserObj->getFullName();
    }
    public function getFoto()
    {
        return $this->profileUserObj->getFoto();
    }
    public function getSeguidoresCount()
    {
        return $this->profileUserObj->getSeguidoresCount();
    }
    public function getSeguidosCount()
    {
        return $this->profileUserObj->getSeguidosCount();
    }
    public function getPublicacionesCount()
    {
        return $this->profileUserObj->getPublicacionesCount();
    }
    public function getBografia()
    {
        return $this->profileUserObj->getBiografia();
    }
    public function userExists()
    {
        $profileUsername = $this->getProfileUsername();
        $query = $this->con->prepare("SELECT * FROM  users WHERE usuario = :usuario");
        $query->bindParam(":usuario", $profileUsername);
        $query->execute();
        return $query->rowCount() != 0;
    }
    public function getFotos()
    {
        $username = $this->getProfileId();
        $query = $this->con->prepare("SELECT * FROM photos WHERE uploadedBy = :up ORDER BY uploadDate DESC");
        $query->bindParam(":up", $username);
        $query->execute();
        $photos = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $photos[] = new Photo($this->con, $row, $this->getProfileId());
        }
        return $photos;
    }
}
