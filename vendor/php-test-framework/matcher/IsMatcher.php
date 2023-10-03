<?php

namespace stf\matcher;

use function stf\asString;

class IsMatcher extends AbstractMatcher {

    private $expected;

    public function __construct($expected) {
        $this->expected = $expected;
    }

    public function matches($actual) : bool {
        return $this->expected === $actual;
    }

    public function getError(
        $actual, ?string $message = null) : MatcherError {

        $message = sprintf("Expected %s but was %s",
            asString($this->expected), asString($actual));

        return new MatcherError(ERROR_C02, $message);
    }
}

