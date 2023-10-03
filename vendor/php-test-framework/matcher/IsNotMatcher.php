<?php

namespace stf\matcher;

use function stf\asString;

class IsNotMatcher extends AbstractMatcher {

    private $expected;

    public function __construct($expected) {
        $this->expected = $expected;
    }

    public function matches($actual) : bool {
        return $this->expected !== $actual;
    }

    public function getError(
        $actual, ?string $message = null) : MatcherError {

        $message = sprintf("Expected not to be %s but it was.",
            asString($this->expected));

        return new MatcherError(ERROR_C02, $message);
    }
}

