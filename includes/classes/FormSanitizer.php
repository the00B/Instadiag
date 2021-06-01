<?php

class FormSanitizer
{

    public static function sanitizerFormString($text)
    {

        $text = strip_tags($text);
        $text = trim($text);
        $text = strtolower($text);
        $text = ucwords($text);
        return $text;
    }

    public static function sanitizerFormUsername($text)
    {
        $text = strip_tags($text);
        $text = trim($text);
        $text = strtolower($text);
        $text = str_replace(" ", "", $text);
        return $text;
    }

    public static function sanitizerFormPassword($text)
    {
        $text = strip_tags($text);
        return $text;
    }

    public static function sanitizerFormNumeroEmail($text)
    {
        $text = strip_tags($text);
        $text = trim($text);
        $text = str_replace(" ", "", $text);
        return $text;
    }
}
