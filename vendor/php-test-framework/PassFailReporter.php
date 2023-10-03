<?php

namespace stf;

class PassFailReporter implements ResultReporter {

    private int $neededForPass;
    private string $format;

    public function __construct(int $neededForPass, string $format) {
        $this->neededForPass = $neededForPass;
        $this->format = $format;
    }

    public function execute(int $passedMethodCount): string {
        $result = $passedMethodCount >= $this->neededForPass
            ? RESULT_PASSED : RESULT_FAILED;

        return sprintf($this->format, $result);
    }
}

