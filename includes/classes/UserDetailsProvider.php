<?php

class UserDetailsProvider
{

    public function __construct($userLoggedInObj)
    {
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function userDetails()
    {
        $link = "profile.php?u=" . $this->userLoggedInObj->getUsername();
        $foto = $this->userLoggedInObj->getFoto();
        $username = $this->userLoggedInObj->getUsername();
        $nombreCompleto = $this->userLoggedInObj->getFullName();

        $html = "<div class='user'>
                    <a href='$link'>
                        <img src='$foto'>
                    </a>
                    <div class='datos'>
                        <a href='$link'>
                            <h3>$username</h3>
                        </a>
                        <span>$nombreCompleto</span>
                    </div>
                </div>";

        return $html;
    }

    public function following()
    {
        $following = $this->userLoggedInObj->getFollowing();

        $html = "";

        if (sizeof($following) == 0) {
            return "Aún no sigues a nadie";
        } else {
            foreach ($following as $follow) {
                $usuario = $follow->getUsername();
                $imagen = $follow->getFoto();

                $html .= "
                        <div class='datosUserSeguidos'>
                            <a href='profile.php?u=$usuario'>
                                <img src='$imagen'>
                            </a>
                            <a href='profile.php?u=$usuario'>
                                <span>$usuario</span>
                            </a>
                        </div>
                ";
            }

            return $html;
        }
    }
}
