<?php

namespace stf\matcher;

use \RuntimeException;

use function \stf\asString;

class ContainsMatcher extends AbstractMatcher {

    private array $expected;

    public function __construct(array $expected) {
        $this->expected = $expected;
    }

    public function matches($actual) : bool {
        if (count($actual) !== count($this->expected)) {
            return false;
        }

        for ($i = 0; $i < count($actual); $i++) {
            if ($actual[$i] !== $this->expected[$i]) {
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
            $actualElement = $actual[$i];
            $expectedElement = $this->expected[$i];

            if ($actualElement !== $expectedElement) {
                $message = sprintf(
                    "Different values in position %s. Expected: %s. Actual: %s",
                    $i, asString($expectedElement), asString($actualElement));

                return new MatcherError(ERROR_C02, $message);
            }
        }

        throw new RuntimeException('Programming error');
    }
}

