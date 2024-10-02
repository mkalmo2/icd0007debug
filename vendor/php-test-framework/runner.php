<?php

namespace stf {

    use Error;
    use Exception;
    use RuntimeException;

    class Storage { // constrain variables to stf namespace
        public static array $collectedTestNames = [];
        public static array $filteredNames = [];
        public static bool $fromPest = false;
    }

    function runTests(?ResultReporter $reporter = null): void {

        $opts = getopt('t:', ['testToRun:']);

        Storage::$fromPest = isset($opts['testToRun']);

        if (isset($opts['testToRun']) && $opts['testToRun']) {
            Storage::$filteredNames[] = $opts['testToRun'];
        }

        $successful = 0;
        foreach (getTestsToRun() as $entry) {
            [$testName, $fn] = $entry;

            try {
                getGlobals()->getBrowser()->reset();

                $fn();

                if (!getGlobals()->leaveBrowserOpen) {
                    getGlobals()->getBrowser()->reset();
                }

                $successful++;

                reportSuccess($testName);

            } catch (FrameworkException $e) {

                handleFrameworkException($e, $testName);

                printPageSourceIfNeeded();

            } catch (Error | Exception $e) {
                printf("\n### Test %s() failed \n\n %s\n\n", $testName, $e->getMessage());

                printPestFailure($testName, $e);
            }
        }

        printf("\n%s of %s tests passed.\n", $successful, count(getAllTestNames()));

        if ($reporter && !containsSelectedTests(getTestNamesToRun())) {
            print $reporter->execute($successful);
        }
    }

    function printPestFailure($testName, $ex): void {
        $details = teamcityEncode($ex->getMessage());
        $testName = teamcityEncode($testName);

        if (Storage::$fromPest) {
            print("##teamcity[testStarted name='$testName']" . PHP_EOL);
            print("##teamcity[testFailed name='$testName' message='$details']" . PHP_EOL);
        }
    }

    function reportSuccess($testName): void {
        printf("%s: OK\n", $testName);
        $testName = teamcityEncode($testName);

        if (Storage::$fromPest) {
            print("##teamcity[testStarted name='$testName']" . PHP_EOL);
            print("##teamcity[testFinished name='$testName' duration='0']" . PHP_EOL);
        }
    }

    function teamcityEncode($string): string {
        $replacements = [
            "'" => "|'",
            '"' => '|"',
            '|' => '||',
            '[' => '|[',
            ']' => '|]',
            "\n" => '|n',
            "\r" => '|r'
        ];

        return strtr($string, $replacements);
    }

    function printPageSourceIfNeeded(): void {
        if (!getGlobals()->printPageSourceOnError) {
            return;
        }

        $response = getGlobals()->getBrowser()->getResponse();

        $text = $response ? $response->getContents() : 'Nothing fetched yet';

        print("##################  Page source start #################### \n");
        print $text . PHP_EOL;
        print("##################  Page source end ###################### \n");
    }

    function handleFrameworkException(FrameworkException $ex, string $testName): void {
        [$callerFile, $callerLine] = getCallerLineAndFile($ex, $testName);
        printf("\n### Test %s failed on line %s in file %s(%s)\n\n",
            $testName, $callerLine, $callerFile, $callerLine);
        printf("ERROR %s: %s\n\n", $ex->getCode(), $ex->getMessage());
        if (getGlobals()->printStackTrace) {
            printf("Stack trace: %s\n\n", $ex->getTraceAsString());
        }

        printPestFailure($testName, $ex);
    }

    function getCallerLineAndFile(FrameworkException $ex, string $testName) : array {
        $trace = $ex->getTrace();

        for ($i = 0; $i < count($trace); $i++) {
            if ($trace[$i]['function'] === '{closure}') {
                $callerFile = $trace[$i - 1]['file'];
                $callerLine = $trace[$i - 1]['line'];

                return [$callerFile, $callerLine];
            }
        }

        throw new RuntimeException('Unexpected error');
    }

    function getAllTestNames(): array {
        return array_map(function($entry) {
            return $entry[0];
        }, Storage::$collectedTestNames);
    }

    function getTestsToRun(): array {
        $namesToRun = getTestNamesToRun();

        return array_filter(Storage::$collectedTestNames, function($entry) use ($namesToRun) {
            return in_array($entry[0], $namesToRun);
        });

    }

    function getTestNamesToRun(): array {
        $testNames = getAllTestNames();

        if (containsSelectedTests($testNames)) {
            $testNames = array_filter($testNames, function($name) {
                return startsWith($name, '_')
                    || in_array($name, Storage::$filteredNames);
            });
        }

        return $testNames;
    }

    function containsSelectedTests($testNames): bool {
        if (count(Storage::$filteredNames) > 0) {
            return true;
        }

        foreach ($testNames as $name) {
            if (startsWith($name, '_')) {
                return true;
            }
        }
        return false;
    }

    function startsWith($subject, $match): bool {
        return stripos($subject, $match) === 0;
    }

    function runAllTestsInDirectory($directory, $suiteFile): void {
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

    function didAllTestsPass(string $output): bool {
        preg_match("/(\d+) of (\d+) tests passed./", $output, $matches);

        return count($matches) && $matches[1] == $matches[2];
    }
}

namespace {

    use stf\Storage;

    function test($name, $fn): void {
        Storage::$collectedTestNames[] = [$name, $fn];
    }

}