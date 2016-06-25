<?php

/**
 * Formatierter var_dump
 *
 * @param mixed $var
 * @return void
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

/**
 * Findet das n-te Vorkommen einer Zeichenkette
 * innerhalb einer anderen Zeichenkette
 *
 * @param string $haystack
 * @param string $needle
 * @param int $occurence
 * @return bool|int
 */
function nth_strpos($haystack, $needle, $occurence = 1) {
    $ct = 0;
    $pos = 0;
    while (($pos = strpos($haystack, $needle, $pos)) !== false) {
        if (++$ct == $occurence) {
            return $pos;
        }
        $pos++;
    }
    return false;
}
