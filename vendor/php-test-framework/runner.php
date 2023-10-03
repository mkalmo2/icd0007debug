<?php

namespace stf;

use RuntimeException;

function runTests(?ResultReporter $reporter = null) {
    $successful = 0;

    foreach (getTestNamesToRun() as $testName) {
        if (!function_exists($testName)) {
            continue;
        }

        try {
            getGlobals()->getBrowser()->reset();

            call_user_func($testName);

            if (!getGlobals()->leaveBrowserOpen) {
                getGlobals()->getBrowser()->reset();
            }

            $successful++;

            printf("%s() OK\n", $testName);

        } catch (FrameworkException $ex) {

            handleFrameworkException($ex, $testName);

            printPageSourceIfNeeded();

        } catch (RuntimeException $e) {
            printf("\n### Test %s() failed \n\n %s\n\n", $testName, $e);
        }
    }

    printf("\n%s of %s tests passed.\n", $successful, count(getAllTestNames()));

    if ($reporter && !containsSelectedTests(getTestNamesToRun())) {
        print $reporter->execute($successful);
    }
}

function printPageSourceIfNeeded() {
    if (!getGlobals()->printPageSourceOnError) {
        return;
    }

    $response = getGlobals()->getBrowser()->getResponse();

    $text = $response ? $response->getContents() : 'Nothing fetched yet';

    print("##################  Page source start #################### \n");
    print $text . PHP_EOL;
    print("##################  Page source end ###################### \n");
}

function handleFrameworkException(FrameworkException $ex, string $testName) {
    [$callerFile, $callerLine] = getCallerLineAndFile($ex, $testName);
    printf("\n### Test %s() failed on line %s in file %s(%s)\n\n",
        $testName, $callerLine, $callerFile, $callerLine);
    printf("ERROR %s: %s\n\n", $ex->getCode(), $ex->getMessage());
    if (getGlobals()->printStackTrace) {
        printf("Stack trace: %s\n\n", $ex->getTraceAsString());
    }
}

function getCallerLineAndFile(FrameworkException $ex, string $testName) : array {
    $trace = $ex->getTrace();

    for ($i = 0; $i < count($trace); $i++) {
        if (!isset($trace[$i]['file']) && $trace[$i]['function'] === $testName) {
            $callerFile = $trace[$i - 1]['file'];
            $callerLine = $trace[$i - 1]['line'];

            return [$callerFile, $callerLine];
        }
    }

    throw new RuntimeException('Unexpected error');
}

function getAllTestNames() : array {
    $testFilePath = get_included_files()[0];

    $testFileSource = file_get_contents($testFilePath);

    return getTestFunctionNames($testFileSource);
}

function getTestNamesToRun() : array {
    $testNames = getAllTestNames();

    if (containsSelectedTests($testNames)) {
        $testNames = array_filter($testNames, function($name) {
            return startsWith($name, '_');
        });
    }

    return $testNames;
}

function containsSelectedTests($testNames) : bool {
    foreach ($testNames as $name) {
        if (startsWith($name, '_')) {
            return true;
        }
    }
    return false;
}

function startsWith($subject, $match) : bool {
    return stripos($subject, $match) === 0;
}

function getTestFunctionNames(string $src): array {

    $tokens = token_get_all($src);

    $result = [];
    while (count($tokens)) {
        $token = array_shift($tokens);

        if (is_array($token)
            && token_name($token[0]) === 'T_COMMENT'
            && strpos($token[1], '#Helpers') !== false) {

            return $result;
        }

        if (is_array($token) && token_name($token[0]) === 'T_FUNCTION') {
            $token = array_shift($tokens);
            if (is_array($token) && token_name($token[0]) === 'T_WHITESPACE') {
                $token = array_shift($tokens);
            }
            if ($token === '(') { // anonymous function
                continue;
            } else if (is_array($token) && token_name($token[0]) === 'T_STRING') {
                $result[] = $token[1];
            } else {
                throw new RuntimeException('Unexpected error');
            }
        }
    }

    return $result;
}

function runAllTestsInDirectory($directory, $suiteFile) {
    $files = scandir($directory);

    $testCount = 0;
    $passedCount = 0;
    foreach ($files as $file) {
        if (!is_file($file)) {
            continue;
        } else if (strpos($suiteFile, $file) !== false) {
            continue;
        }

        $cmd = sprintf('php %s', $file);

        $output = [];

        exec($cmd, $output);

        $outputString = implode("\n", $output);

        $allPassed = didAllTestsPass($outputString);

        $result =  $allPassed ? ' OK' : " NOK";

        $testCount++;
        if ($allPassed) {
            $passedCount++;
        }

        printf("%s%s\n", $file, $result);
    }

    printf("\n%s of %s tests passed.\n", $passedCount, $testCount);
}

function didAllTestsPass(string $output) : bool {
    preg_match("/(\d+) of (\d+) tests passed./", $output, $matches);

    return count($matches) && $matches[1] == $matches[2];
}
