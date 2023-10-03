<?php

namespace stf;

interface ResultReporter {

    public function execute(int $passedMethodCount): string;
}

