<?php 

/**
 * Crea un SLUG
 *
 * @param string $str
 * @param integer $max
 * @return string
 */
function slug(string $str, int $max = 255): string
{
    $out = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
    $out = substr(preg_replace("/[^-\/+|\w ]/", '', $out), 0, $max);
    $out = strtolower(trim($out, '-'));
    $out = preg_replace("/[\/_| -]+/", '-', $out);
    return $out;
}