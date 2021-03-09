<?php

function evaluate($expression) : int {
    if (preg_match('/[^\d +-]/', $expression, $matches)) {
        throw new RuntimeException(
            'expression contains illegal character: ' . $matches[0]);
    }

    try {
        $result = '';

        eval(sprintf('$result = %s;', $expression));

        return intval($result);

    } catch (Error $ex) {
        throw new RuntimeException('bad expression: ' . $expression);
    }
}

