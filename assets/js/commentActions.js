function postComment(button, postedBy, photoId, containerClass) {
  var input = $(button).siblings("input");
  var commentText = input.val();
  input.val("");

  if (commentText) {
    $.post("ajax/postComment.php", {
      commentText: commentText,
      postedBy: postedBy,
      photoId: photoId
    }).done(function(comment) {
      $("." + containerClass + "-" + photoId).prepend(comment);
    });
  } else {
    alert("No se puede enviar un comentario vacio");
  }
}

function postCommentSelf(button, postedBy, photoId, containerClass) {
  var input = $(button).siblings("input");
  var commentText = input.val();
  input.val("");

  if (commentText) {
    $.post("ajax/postCommentSelf.php", {
      commentText: commentText,
      postedBy: postedBy,
      photoId: photoId
    }).done(function(comment) {
      $("." + containerClass).prepend(comment);
    });
  } else {
    alert("No se puede enviar un comentario vacio");
  }
}
