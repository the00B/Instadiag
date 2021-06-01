<?php
class ButtonProvider
{
    public static function createButtonProfile($con, $userToObj, $userLoggedInObj)
    {
        $userTo = $userToObj->getId();
        $userLoggedIn = $userLoggedInObj->getId();

        $estaSiguiendo = $userLoggedInObj->isFollowing($userTo);
        $buttonText = $estaSiguiendo ? "Siguiendo" : "Seguir";
        $class = $estaSiguiendo ? "follow" : "";
        $disabled = ($userTo == $userLoggedIn) ? "display:none;" : "";

        $action = "follow($userTo, $userLoggedIn, this)";

        $html = "<div class='userCard'>
                    <button type='button' class='boton $class' onclick='$action' style='$disabled; margin:0;'>$buttonText</button>
                </div>";

        return $html;
    }
}
