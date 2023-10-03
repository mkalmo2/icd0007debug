<?php

namespace stf\matcher;

use function stf\asString;

class IsCloseToMatcher extends AbstractMatcher {

    private float $expected;

    public function __construct(float $expected) {
        $this->expected = $expected;
    }

    public function matches($actual): bool {
        return abs($this->expected - $actual) < 0.001;
    }

    public function getError(
        $actual, ?string $message = null): MatcherError {

        $message = sprintf("Expected %s to be close to %s",
            asString($actual), asString($this->expected));

        return new MatcherError(ERROR_C02, $message);
    }
}

