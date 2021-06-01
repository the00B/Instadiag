<?php
class PostProvider
{
    private $con, $userLoggedInObj;
    public function __construct($con, $userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }
    public function createPost($photo)
    {
        $contenedorImagen = $this->createContenedorImagen($photo->getFilePath());
        $contenedorDetalle = $this->createContenedorDetalle($photo);
        $html = "
                $contenedorImagen
                $contenedorDetalle
                ";
        return $html;
    }
    private function createContenedorImagen($imagen)
    {
        return "<div class='fotoPost'>
                <img src='$imagen'>
            </div>";
    }

    private function createContenedorDetalle(&$photo)
    {
        $id = $photo->getId();
        $fotoUser = $photo->getFotoUser();
        $username = $photo->getUsername();
        $descripcion = $photo->getDescription();
        $likes = $photo->getLikes();
        $commentPost = $photo->getCommentsTotal();
        $comments = $this->createFromComments($commentPost);

        $idUserLogged = $this->userLoggedInObj->getId();
        $wasLike = ($photo->wasLikedBy()) ? "<i class='fas fa-heart' style='color:#ED4956;'></i>" : "<i class='far fa-heart'></i>";
        $actionLike = "like($idUserLogged, $id, this)";
        $commentAction = "postCommentSelf(this, $idUserLogged, $id, \"comentariosPost\")";

        $html = "<div class='detailsPost'>
                    <div class='titlePost'>
                        <a href='profile.php?u=$username'>
                            <img src='$fotoUser'>
                        </a>
                        <div>
                            <a href='profile.php?u=$username'>
                                <p>$username</p>
                            </a>
                            <span>$descripcion</span>
                        </div>
                    </div>
                    <div class='comentariosPost'>
                        $comments
                    </div>
                    <div class='piePost'>
                        <button type='button' onclick='$actionLike'>$wasLike</button>
                        <p class='pl-$id'>$likes Me gusta</p>
                        <div class='respuesta'>
                            <input type='text' id='inputComment' name='comment' placeholder='Añade un comentario...'>
                            <button type='button' id='buttonComment' onclick='$commentAction'>Publicar</button>
                        </div>
                    </div>
                </div>";
        return $html;
    }
    private function createFromComments(&$comments)
    {
        $html = "";
        foreach ($comments as $comment) {
            $usuario = $comment->getUsername();
            $body = $comment->getBody();
            $foto = $comment->getFotoComment();
            $hora = $comment->getDatePosted();

            $html .= "<div class='comentarioPost'>
                            <a href='profile.php?u=$usuario'>
                                <img src='$foto'>
                            </a>
                            <div>
                                <p>
                                    <a href='profile.php?u=$usuario'>
                                        <b>$usuario</b>
                                    </a>
                                </p>
                                <span>$body</span>
                                <span>$hora</span>
                            </div>
                        </div>";
        }
        return $html;
    }
}
