<?php

namespace stf;

function mapAsString(array $map) : string {
    $parts = [];
    foreach ($map as $key => $value) {
        $parts[] = sprintf("%s='%s'", $key, $value);
    }

    return implode(', ', $parts);
}

function decode_html_entities(string $input) : string {
    $tmp = html_entity_decode($input, ENT_QUOTES | ENT_HTML5);
    return str_replace('&#039;', "'", $tmp);
}

function asString($value) : string {
    if (gettype($value) === 'boolean') {
        return $value ? 'TRUE' : 'FALSE';
    } else if (gettype($value) === 'NULL') {
        return 'NULL';
    } else if (gettype($value) === 'string') {
        return sprintf("'%s'", $value);
    } else if (gettype($value) === 'array') {
        return sprintf("'%s'", varDumpToString($value));
    } else if ($value === '') {
        return '<EMPTY STRING>';
    }

    return $value;
}

function varDumpToString($input) {
    ob_start();
    var_dump($input);
    return ob_get_clean();
}