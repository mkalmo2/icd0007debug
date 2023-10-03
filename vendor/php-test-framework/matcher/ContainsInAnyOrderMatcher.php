<?php

namespace stf\matcher;

use \RuntimeException;

class ContainsInAnyOrderMatcher extends AbstractMatcher {

    private array $expected;

    public function __construct(array $expected) {
        $this->expected = $expected;
    }

    public function matches($actual) : bool {
        if (count($actual) !== count($this->expected)) {
            return false;
        }

        for ($i = 0; $i < count($actual); $i++) {
            if (!in_array($this->expected[$i], $actual)) {
                return false;
            }
        }

        return true;
    }

    public function getError(
        $actual, ?string $message = null) : MatcherError {

        if (count($actual) !== count($this->expected)) {
            return new MatcherError(ERROR_C02,
                "Arrays are of different length");
        }

        for ($i = 0; $i < count($actual); $i++) {
            $each = $this->expected[$i];

            if (!in_array($each, $actual)) {

                $message = sprintf(
                    "Expected to find value '%s' but did not.", $each);

                return new MatcherError(ERROR_C06, $message);
            }
        }

        throw new RuntimeException('Programming error');
    }
}

