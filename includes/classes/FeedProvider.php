<?php

class FeedProvider
{

    public function __construct($con, $userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function createCardFeed($photos)
    {
        $html = "";

        foreach ($photos as $photo) {
            $id = $photo->getId();
            $descripcion = $photo->getDescription();
            $foto = $photo->getFilePath();
            $usuario = $photo->getUsername();
            $fotoUser = $photo->getFotoUser();
            $fotoLikes = $photo->getLikes();
            $countComments = ($photo->getNumberOfComments() > 2) ? "Ver los " . $photo->getNumberOfComments() . " comentarios" : "";
            $getComments = $this->generateComments($photo->getComments());
            $getDatePosted = $photo->getUploadDate();

            $wasLike = ($photo->wasLikedBy()) ? "<i class='fas fa-heart' style='color:#ED4956;'></i>" : "<i class='far fa-heart'></i>";

            $linkUser = "profile.php?u=" . $usuario;
            $linkPost = "post.php?p=" . $id;

            $idUserLogged = $this->userLoggedInObj->getId();
            $actionLike = "like($idUserLogged, $id, this)";
            $commentAction = "postComment(this, $idUserLogged, $id, \"commentDiv\")";
            $classDivComment = "commentDiv-$id";

            $html .= "
                    <div class='cardFeed'>
                        <div class='headFeed'>
                            <a href='$linkUser'>
                                <div class='user'>
                                    <img src='$fotoUser' alt=''>
                                    <span>$usuario</span>
                                </div>
                            </a>
                            <div class='publicacion'>
                                <a href='$linkPost'><i class='fas fa-ellipsis-h'></i></a>
                            </div>
                        </div>
                        <div class='fotoFeed'>
                            <img src='$foto' alt=''>
                        </div>
                        <div class='controlsFeed'>
                            <div class='controlPrincipal'>
                                <button type='button' onclick='$actionLike'>$wasLike</button>
                                <a href='$linkPost'>
                                    <i class='far fa-comment'></i>
                                </a>
                            </div>
                        </div>
                        <div class='detailsFeed'>
                            <div class='likes'>
                                <span class='pl-$id'>$fotoLikes Me gusta</span>
                            </div>
                            <div class='description'>
                                <a href='$linkUser'>$usuario</a>
                                <span>$descripcion</span>
                            </div>
                            <div class='comments'>
                                <div class='commentsCount'>
                                    <a href='$linkPost'>$countComments</a>
                                </div>
                                <div class='$classDivComment'>
                                    $getComments
                                </div>
                                
                                <div class='time'>
                                    <span>$getDatePosted</span>
                                </div>
                            </div>
                        </div>
                        <div class='respuesta'>
                            <input type='text' id='inputComment' name='comment' placeholder='Añade un comentario...'>
                            <button type='button' id='buttonComment' onclick='$commentAction'>Publicar</button>
                        </div>
                    
                    </div>
            
            
            ";
        }
        return $html;
    }

    private function generateComments($comments)
    {
        $html = "";

        foreach ($comments as $comment) {
            $body = $comment->getBody();
            $usuario = $comment->getUsername();
            $datePosted = $comment->getDatePosted();
            $linkUsuario = "profile.php?u=" . $usuario;

            $html .= "
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
        }
        return $html;
    }
}
