<?php

/**
 * Replaces spaces with underscore
 *
 * @param string$string
 * @return string
 */
function niceEncode($string) {
    return str_replace(' ', '_', $string);
}

/**
 * Replaces underscore with spaces
 *
 * @param string$string
 * @return string
 */
function niceDecode($string) {
    return str_replace('_', ' ', $string);
}