<?php

namespace stf\matcher;

class ContainsNotStringMatcher extends AbstractMatcher {

    private string $needle;

    public function __construct(string $needle) {
        $this->needle = $needle;
    }

    public function matches($actual) : bool {
        return strpos($actual, $this->needle) === false;
    }

    public function getError(
        $actual, ?string $message = null) : MatcherError {

        return new MatcherError(ERROR_C04,
            sprintf("Should not contain string: '%s'", $this->needle));
    }
}

