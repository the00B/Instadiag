function like(userId, photoId, button) {
  $.post("ajax/likePhoto.php", { photoId: photoId }).done(function(data) {
    var likeButton = $(button);

    var result = JSON.parse(data);

    updateLikesValue($(".pl-" + photoId), result.likes);

    if (result.likes < 0) {
      likeButton.html("<i class='far fa-heart'></i>");
    } else {
      likeButton.html("<i class='fas fa-heart' style='color:#ED4956;'></i>");
    }
  });
}

function updateLikesValue(element, num) {
  var likesCountValue = element.text() || 0;
  element.text(parseInt(likesCountValue) + parseInt(num) + " Me gusta");
}

function follow(userTo, userFrom, button) {
  $.post("ajax/follow.php", {
    userTo: userTo,
    userFrom: userFrom
  }).done(function(data) {
    const result = JSON.parse(data);
    $(button).toggleClass("follow");

    if (result.follow == "add") {
      $(button).text("Siguiendo");
    } else {
      $(button).text("Seguir");
    }
  });
}
