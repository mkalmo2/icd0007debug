<?php

list ($phpMajorVersion, $phpMinorVersion) = explode('.', PHP_VERSION);

if (intval($phpMajorVersion) < 7
    || intval($phpMajorVersion) === 7 && intval($phpMinorVersion) < 4) {

    die('This framework requires Php version 7.4 or greater. '.
        "Found Php version " . PHP_VERSION . '.' . PHP_EOL);
}

const RESULT_PATTERN_SHORT = "\nRESULT: %s\n";
const RESULT_PATTERN_POINTS = "\nRESULT: %s points\n";
const RESULT_PATTERN_WITH_MAX = "\nRESULT: %s of %s points\n";
const RESULT_PASSED = 'PASSED';
const RESULT_FAILED = 'FAILED';

require_once 'runner.php';
require_once 'util.php';
require_once 'domain.php';
require_once 'constants.php';

include_once __DIR__ . '/simpletest/user_agent.php';

require_once 'autoload.php';

use stf\browser\page\FieldType;
use stf\Globals;
use stf\matcher\ContainsMatcher;
use stf\matcher\AbstractMatcher;
use stf\matcher\ContainsStringMatcher;
use stf\matcher\ContainsNotStringMatcher;
use stf\browser\Browser;
use stf\PassFailReporter;
use stf\PointsReporter;
use stf\ResultReporter;

function getBrowser() : Browser {
    return getGlobals()->getBrowser();
}

function assertThrows($function): void {
    try {
        $function();
    } catch (Throwable $t) {
        return;
    }

    throw new stf\FrameworkException(ERROR_C01, "Expected to throw but did not");
}

function fail($code, $message): void {
    throw new stf\FrameworkException($code, $message);
}

function waitPageText(Closure $closure): void {
    $time = 0;
    while ($time < Globals::MAX_WAIT_TIME * 1000) {
        if ($closure()->matches(getPageText())) {
            return;
        }

        usleep(Globals::POLL_FREQUENCY * 1000);

        $time += Globals::POLL_FREQUENCY;
    }

    throw new stf\FrameworkException(ERROR_C05,
        "did not find text in " . Globals::MAX_WAIT_TIME . ' seconds');
}

function assertThat($actual, stf\matcher\AbstractMatcher $matcher, $message = null): void {
    if ($matcher->matches($actual)) {
        return;
    }

    if ($message) {
        throw new stf\FrameworkException(ERROR_C01, $message);
    }

    $error = $matcher->getError($actual);

    throw new stf\FrameworkException($error->getCode(), $error->getMessage());
}

function disableAutomaticRedirects() : void {
    getBrowser()->setMaxRedirectCount(0);
}

function setBaseUrl(string $url) : void {
    getGlobals()->baseUrl = new stf\browser\Url($url);
}

function useWebDriver(bool $shouldUse) : void {
    getGlobals()->getBrowser()->reset();

    getGlobals()->setUseWebDriver($shouldUse);
}

function setLogRequests(bool $flag) : void {
    getGlobals()->logRequests = $flag;
}

function setLogPostParameters(bool $flag) : void {
    getGlobals()->logPostParameters = $flag;
}

function setPrintStackTrace(bool $flag) : void {
    getGlobals()->printStackTrace = $flag;
}

function setPrintPageSourceOnError(bool $flag) : void {
    getGlobals()->printPageSourceOnError = $flag;
}

function setLeaveBrowserOpen(bool $flag) : void {
    getGlobals()->leaveBrowserOpen = $flag;
}

function setShowBrowser(bool $flag): void {
    getGlobals()->showBrowser = $flag;
}

function getResponseCode(): int {
    return getBrowser()->getResponse()->getResponseCode();
}

function getCurrentUrl(): string {
    return getBrowser()->getCurrentUrl();
}

function getCurrentUrlDir(): string {
    return getBrowser()->getCurrentUrlDir();
}

function printPageSource(): void {
    print getPageSource() . PHP_EOL;
}

function printPageText(): void {
    print getPageText() . PHP_EOL;
}

function getPageText(): string {
    return getBrowser()->getPageText();
}

function getPageSource(): string {
    return getBrowser()->getPageSource();
}

function assertPageContainsLinkWithId($linkId): void {
    if (getBrowser()->hasLinkWithId($linkId)) {
        return;
    }

    fail(ERROR_W03,
        sprintf("Current page does not contain link with id '%s'.", $linkId));
}

function assertPageContainsRelativeLinkWithId($linkId): void {
    assertPageContainsLinkWithId($linkId);

    $href = getBrowser()->getLinkHrefById($linkId);

    if (substr($href, 0, 1) === '/') {
        fail(ERROR_W02, "'$href' is not relative link");
    }
}

function assertPageContainsTextFieldWithName($name): void {
    if (getBrowser()->hasFieldByName($name, FieldType::TextField)) {
        return;
    }

    fail(ERROR_W13,
        sprintf("Current page does not contain text field with name '%s'.", $name));
}

function assertPageContainsFileFieldWithName($name): void {
    if (getBrowser()->hasFieldByName($name, FieldType::File)) {
        return;
    }

    fail(ERROR_W17,
        sprintf("Current page does not contain file field with name '%s'.", $name));
}

function assertPageContainsRadioWithName($name): void {
    if (getBrowser()->hasFieldByName($name, FieldType::Radio)) {
        return;
    }

    fail(ERROR_W14,
        sprintf("Current page does not contain radio with name '%s'.", $name));
}

function assertPageContainsSelectWithName($name): void {
    if (getBrowser()->hasFieldByName($name, FieldType::Select)) {
        return;
    }

    fail(ERROR_W16,
        sprintf("Current page does not contain select with name '%s'.", $name));
}

function assertPageContainsFieldWithName($name): void {
    if (getBrowser()->hasFieldByName($name, FieldType::Any)) {
        return;
    }

    fail(ERROR_W05,
        sprintf("Current page does not contain field with name '%s'.", $name));
}

function assertPageDoesNotContainFieldWithName($name): void {
    if (!getBrowser()->hasFieldByName($name, FieldType::Any)) {
        return;
    }

    fail(ERROR_W18,
        sprintf("Current page should not contain field with name '%s'.", $name));
}

function assertPageDoesNotContainButtonWithName($name): void {
    if (!getBrowser()->hasFieldByName($name, FieldType::Button)) {
        return;
    }

    fail(ERROR_W19,
        sprintf("Current page should not contain button with name '%s'.", $name));
}

function assertPageContainsCheckboxWithName($name): void {
    if (getBrowser()->hasFieldByName($name, FieldType::Checkbox)) {
        return;
    }

    fail(ERROR_W15,
        sprintf("Current page does not contain checkbox with name '%s'.", $name));
}

function assertPageContainsButtonWithName($name): void {
    if (getBrowser()->hasFieldByName($name, FieldType::Button)) {
        return;
    }

    fail(ERROR_W06,
        sprintf("Current page does not contain button with name '%s'.",
            $name));
}

function assertPageContainsLinkWithText($text): void {
    if (getBrowser()->hasLinkWithText($text)) {
        return;
    }

    fail(ERROR_W04,
        sprintf("Current page does not contain link with text '%s'.", $text));
}

function assertPageContainsElementWithId($id): void {
    if (getBrowser()->hasElementWithId($id)) {
        return;
    }

    fail(ERROR_W08,
        sprintf("Current page does not contain element with id '%s'.", $id));
}

function assertPageDoesNotContainElementWithId($id): void {
    if (!getBrowser()->hasElementWithId($id)) {
        return;
    }

    fail(ERROR_W09,
        sprintf("Current page should not contain element with id '%s'.", $id));
}

function assertFrontControllerLink(string $id): void {
    assertPageContainsLinkWithId($id);

    $link = getBrowser()->getLinkHrefById($id);

    $pattern = '/^(index\.php)?\??[-=&\w]*$/';

    if (!preg_match($pattern, $link)) {
        $message = 'Front Controller pattern expects all links '
            . 'to be in ?key1=value1&key2=... format. But this link was: ' . $link;

        fail(ERROR_W20, $message);
    }
}

function assertPageContainsText($textToBeFound): void {
    if (strpos(getPageText(), $textToBeFound) !== false) {
        return;
    }

    fail(ERROR_H04, sprintf("Did not find text '%s' on the current page.",
        $textToBeFound));
}

function assertNoOutput(): void {
    $source = getPageSource();

    if (preg_match('/^\s*$/', $source)) {
        return;
    }

    fail(ERROR_W21, sprintf(
        "Should not print any output along with redirect header " .
        "but the output was: %s", $source));
}

function assertCurrentUrl($expected): void {
    $actual = getBrowser()->getCurrentUrl();

    if ($actual !== $expected) {
        fail(ERROR_H03, sprintf("Expected url to be '%s' but was '%s'",
            $expected, $actual));
    }
}

function clickLinkWithText($text): void {
    assertPageContainsLinkWithText($text);

    getBrowser()->clickLinkWithText($text);
}

function getHrefFromLinkWithText(string $text): string {
    assertPageContainsLinkWithText($text);

    return getBrowser()->getLinkHrefByText($text);
}

function getAttributeFromElementWithId(string $id, string $attributeName): string {
    assertPageContainsElementWithId($id);

    return getBrowser()->getElementAttributeValue($id, $attributeName);
}

function clickLinkWithId($linkId): void {
    assertPageContainsLinkWithId($linkId);

    getBrowser()->clickLinkWithId($linkId);
}

function navigateTo(string $url) {
    getBrowser()->navigateTo($url);
}

function assertImageExists(string $url): void {
    getBrowser()->navigateTo($url);

    $type = getBrowser()->getResponse()->getContentType();

    assertThat($type, containsString('image'),
        "$url does not point to image");
}

function getImage(string $url): string {
    getBrowser()->navigateTo($url);

    return getBrowser()->getResponse()->getContents();
}

function clickButton(string $buttonName, ?string $buttonValue = null) {
    assertPageContainsButtonWithName($buttonName);

    getBrowser()->submitFormByButtonPress($buttonName, $buttonValue);
}

function setTextFieldValue(string $fieldName, string $value) {
    assertPageContainsTextFieldWithName($fieldName);

    getBrowser()->setTextFieldValue($fieldName, $value);
}

function setFileFieldValues(string $fieldName, string $path, string $contents) {
    assertPageContainsFileFieldWithName($fieldName);

    getBrowser()->setFileFieldValues($fieldName, $path, $contents);
}

function forceFieldValue(string $fieldName, string $value) {
    assertPageContainsFieldWithName($fieldName);

    getBrowser()->forceFieldValue($fieldName, $value);
}

function selectOptionWithText(string $fieldName, string $text): void {
    assertPageContainsSelectWithName($fieldName);

    if (getBrowser()->hasSelectOptionWithLabel($fieldName, $text)) {
        getBrowser()->selectOptionWithLabel($fieldName, $text);
    } else {
        fail(ERROR_W12, sprintf("select with name '%s' does not have option '%s'",
            $fieldName, $text));
    }
}

function selectOptionWithValue(string $fieldName, string $value): void {
    assertPageContainsSelectWithName($fieldName);

    if (getBrowser()->hasSelectOptionWithValue($fieldName, $value)) {
        getBrowser()->selectOptionWithValue($fieldName, $value);
    } else {
        fail(ERROR_W12, sprintf("select with name '%s' does not have option '%s'",
            $fieldName, $value));
    }
}

function setCheckboxValue(string $fieldName, bool $value) {
    assertPageContainsCheckboxWithName($fieldName);

    getBrowser()->setCheckboxValue($fieldName, $value);
}

function setRadioFieldValue(string $fieldName, string $value) {
    assertPageContainsRadioWithName($fieldName);

    if (getBrowser()->hasRadioOption($fieldName, $value)) {
        getBrowser()->setRadioValue($fieldName, $value);
    } else {
        fail(ERROR_W11, sprintf("radio with name '%s' does not have option '%s'",
            $fieldName, $value));
    }
}

function getFieldValue(string $fieldName) {
    assertPageContainsFieldWithName($fieldName);

    return getBrowser()->getFieldValue($fieldName);
}

function getSelectedOptionText(string $fieldName): string {
    assertPageContainsSelectWithName($fieldName);

    return getBrowser()->getSelectedOptionText($fieldName);
}

function getSelectOptionValues(string $fieldName): array {
    assertPageContainsSelectWithName($fieldName);

    return getBrowser()->getFormSet()
        ->getSelectByName($fieldName)->getOptionValues();
}

function deleteSessionCookie(): void {
    throw new Error('not implemented');
}

function closeBrowser() {
    getBrowser()->reset();
}


function is($value): stf\matcher\AbstractMatcher {
    return new stf\matcher\IsMatcher($value);
}

function isNot($value): stf\matcher\AbstractMatcher {
    return new stf\matcher\IsNotMatcher($value);
}

function isCloseTo($value): stf\matcher\AbstractMatcher {
    return new stf\matcher\IsCloseToMatcher($value);
}

function contains(array $needleArray): AbstractMatcher {
    return new ContainsMatcher($needleArray);
}

function containsString(string $needle): AbstractMatcher {
    return new ContainsStringMatcher($needle);
}

function doesNotContainString(string $needle): AbstractMatcher {
    return new ContainsNotStringMatcher($needle);
}

function containsStringOnce(string $value): stf\matcher\AbstractMatcher {
    return new stf\matcher\ContainsStringOnceMatcher($value);
}

function containsInAnyOrder(array $value): stf\matcher\AbstractMatcher {
    return new stf\matcher\ContainsInAnyOrderMatcher($value);
}

function isAnyOf(...$values): stf\matcher\AbstractMatcher {
    return new stf\matcher\ContainsAnyMatcher($values);
}

function extendIncludePath(array $argv, string $userDefinedDir) {
    $path = getProjectPath($argv, $userDefinedDir);

    set_include_path(get_include_path() . PATH_SEPARATOR . $path);
}

function getProjectPath(array $argv, string $userDefinedDir) {
    $path = count($argv) === 2 ? $argv[1] : $userDefinedDir;

    if (!$path) {
        die("Please specify your project's directory in constant PROJECT_DIRECTORY");
    }

    $path = realpath($path);

    if (!file_exists($path)) {
        die("Value in PROJECT_DIRECTORY is not correct directory");
    }

    return $path;
}

function getGlobals(): Globals {
    $key = "---STF-GLOBALS---";

    if (!isset($GLOBALS[$key])) {
        $GLOBALS[$key] = new Globals();
    }

    return $GLOBALS[$key];
}

function getPointsReporter(array $scale): ResultReporter {
    return new PointsReporter($scale, RESULT_PATTERN_POINTS);
}

function getPassFailReporter(int $threshold): ResultReporter {
    return new PassFailReporter($threshold, RESULT_PATTERN_SHORT);
}

function getPointsReporterWithMax(array $scale): ResultReporter {
    return new PointsReporter($scale, RESULT_PATTERN_WITH_MAX);
}