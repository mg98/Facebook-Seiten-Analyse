<?php

/**
 * Schöner var_dump
 *
 * @param mixed $var
 */
function debug($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

/**
 * Encodiert Strings zum Gebrauch in der URL,
 * indem es Leerzeichen durch Unterstriche ersetzt
 *
 * @param string$string
 * @return string
 */
function niceEncode($string) {
    return str_replace(' ', '_', $string);
}

/**
 * Decodiert Strins aus der URL,
 * indem es Unterstriche durch Leerzeichen ersetzt
 *
 * @param string$string
 * @return string
 */
function niceDecode($string) {
    return str_replace('_', ' ', $string);
}