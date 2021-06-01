<?php
require_once("ProfileData.php");
require_once("ButtonProvider.php");
class ProfileProvider
{
    private $con, $userLoggedInObj, $profileData;
    public function __construct($con, $userLoggedInObj, $profileUsername)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
        $this->profileData = new ProfileData($con, $profileUsername);
    }
    public function create()
    {

        if (!$this->profileData->userExists()) {
            return "Usuario o persona no encontrada!";
        }

        $username = $this->profileData->getProfileUsername();
        $fullName = $this->profileData->getProfileFullName();
        $fotoUser = $this->profileData->getFoto();
        $seguidores = $this->profileData->getSeguidoresCount();
        $seguidos = $this->profileData->getSeguidosCount();
        $publicacionesCount = $this->profileData->getPublicacionesCount();
        $biografia = $this->profileData->getBografia();
        $buttonProfile = $this->createButton();
        $getPhotosProfile = $this->getFotosProfile($this->profileData->getFotos());

        $html = "<div class='contenedorProfileUser'>
                    <div class='photoProfile'>
                        <img src='$fotoUser'>
                    </div>
                    <div class='detailsProfile'>
                        <div class='usernameProfile'>
                            <h3>$username</h3>
                            <div class='controlsProfile'>
                                $buttonProfile
                            </div>
                        </div>
                        <div class='countProfile'>
                            <div>
                                <p><b>$publicacionesCount</b> publicaciones</p>
                            </div>
                            <div>
                                <p><b>$seguidores</b> seguidores</p>
                            </div>
                            <div>
                                <p><b>$seguidos</b> seguidos</p>
                            </div>
                        </div>
                        <div class='descriptionProfile'>
                            <h1>$fullName</h1>
                            <span>$biografia</span>
                        </div>
                    </div>
                </div>
                $getPhotosProfile";

        return $html;
    }
    private function createButton()
    {
        if ($this->userLoggedInObj->getUsername() == $this->profileData->getProfileUsername()) {
            return "<a href='setting.php' class='boton follow'>Editar perfil</a>";
        } else {
            return ButtonProvider::createButtonProfile(
                $this->con,
                $this->profileData->getProfileUserObj(),
                $this->userLoggedInObj
            );
        }
    }
    private function getFotosProfile($photos)
    {
        $html = "<div class='publicacionesProfile'>";
        foreach ($photos as $photo) {
            $urlfoto = $photo->getFilePath();
            $id = $photo->getId();
            $countLikes = $photo->getLikes();
            $countComments = $photo->getNumberOfComments();

            $html .= "<div class='contenedorFotoProfile'>
                        <a href='post.php?p=$id'>
                            <img src='$urlfoto'>
                            <div class='datosFotoProfile'>
                                <span>$countLikes <i class='fas fa-heart'></i></span>
                                <span>$countComments <i class='fas fa-comment'></i></span>
                            </div>
                        </a>
                    </div>";
        }
        $html .= "</div>";

        return $html;
    }
}
