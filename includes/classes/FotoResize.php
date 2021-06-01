<?php
class FotoResize
{

  public function __construct()
  { }
  public static function fotoResize($url, $anchoLimite, $extension)
  {
    if ($extension == "jpeg" or $extension == "jpg" or $extension == "jpe") {
      $imagenActual = imagecreatefromjpeg($url);
    } else if ($extension == "png") {
      $imagenActual = imagecreatefrompng($url);
    }

    $imagenAncho = imagesx($imagenActual);
    $imagenAlto = imagesy($imagenActual);
    $anchoLimite = $anchoLimite;

    if ($imagenAncho > $imagenAlto) {
      $imagenAncho = $anchoLimite;
      $imagenAlto = $anchoLimite * imagesy($imagenActual) / imagesx($imagenActual);
    } else {
      $imagenAlto = $anchoLimite;
      $imagenAncho = $anchoLimite * imagesx($imagenActual) / imagesy($imagenActual);
    }

    $imagenDestino = imagecreatetruecolor($imagenAncho, $imagenAlto);
    imagecopyresized($imagenDestino, $imagenActual, 0, 0, 0, 0, $imagenAncho, $imagenAlto, imagesx($imagenActual), imagesy($imagenActual));

    $explode = explode(".", $url);
    $nuevoPath = $explode[0] . '.webp';
    imagewebp($imagenDestino, $nuevoPath);
    unlink(realpath($url));
  }
}
