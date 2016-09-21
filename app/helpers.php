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
 * Encodiert "/" und "\" in Strings
 *
 * @param string $string
 * @return string
 */
function nice_encode($string) {
    $string = urlencode($string);
    if (!env('ALLOW_ENCODED_SLASHES')) {
        $string = str_replace('%2F', '$2F', $string);
        $string = str_replace('%5C', '$5C', $string);
    }
    return $string;
}

/**
 * Decodiert umgewandelte "/" und "\" aus Strings
 *
 * @param string $string
 * @return string
 */
function nice_decode($string) {
    $string = urldecode($string);
    if (!env('ALLOW_ENCODED_SLASHES')) {
        $string = str_replace('$2F', '/', $string);
        $string = str_replace('$5C', '\\', $string);
    }
    return $string;
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

/**
 * Macht dasselbe wie array_chunk() für Objekte
 *
 * @param Illuminate\Database\Eloquent\Collection $collection
 * @param int $size
 * @return array
 */
function collection_chunk($collection, $size) {
    $arrays = [];
    $i = 0;
    foreach ($collection as $item) {
        if ($i++ % $size == 0) {
            $arrays[] = [];
            $current = & $arrays[count($arrays) - 1];
        }
        $current[] = $item;
    }
    return $arrays;
}

/**
 * Shortcut für Facebook API
 *
 * @return \Facebook\Facebook
 */
function fb() {
    return \App\Http\Middleware\FacebookAPI::get();
}