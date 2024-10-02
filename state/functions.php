<?php

function removeElementByValue(int $value, array $array): array {
    $key = array_search($value, $array);
    if ($key === false) {
        return $array;
    }

    unset($array[$key]);

    return array_values($array);
}
