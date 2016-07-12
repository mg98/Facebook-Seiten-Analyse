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
