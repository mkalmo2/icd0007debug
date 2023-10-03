<?php

namespace stf\browser;

use Error;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Exception\NoSuchElementException;

use stf\browser\page\FormSet;
use stf\browser\page\Element;
use stf\FrameworkException;
use stf\Globals;

class WebDriverBrowser implements Browser {

    private Globals $globals;
    private ?RemoteWebDriver $driver = null;

    public function __construct(Globals $globals) {
        $this->globals = $globals;
    }

    function setMaxRedirectCount(int $count): void {
        throw new Error('not implemented');
    }

    function getCurrentUrl(): string {
        return $this->getDriver()->getCurrentURL();
    }

    function getCurrentUrlDir(): string {
        throw new Error('not implemented');
    }

    function getResponse(): ?HttpResponse {
        $headers = new HttpHeaders(0, null, null);

        return new HttpResponse($headers, $this->getDriver()->getPageSource());
    }

    function reset(): void {
        if ($this->driver) {
            $this->driver->quit();
        }

        $this->driver = null;
    }

    function navigateTo(string $url): void {
        $this->getDriver()->get($url);
    }

    function getPageId(): ?string {
        throw new Error('not implemented');
    }

    function getLinkHrefById(string $id): string {
        throw new Error('not implemented');
    }

    function getLinkHrefByText(string $text): string {
        throw new Error('not implemented');
    }

    function hasLinkWithId(string $id): bool {
        return $this->getElement(WebDriverBy::id($id)) != null;
    }

    function hasLinkWithText(string $linkText): bool {
        return $this->getElement(WebDriverBy::linkText($linkText)) != null;
    }

    function hasElementWithId(string $id): bool {
        throw new Error('not implemented');
    }

    function getElementAttributeValue(string $id,
                                      string $attributeName): string {
        throw new Error('not implemented');
    }

    function getElementByInnerText(string $innerText): ?Element {
        throw new Error('not implemented');
    }

    function getElements(): array {
        throw new Error('not implemented');
    }

    function clickLinkWithId(string $linkId): void {
        $this->clickAndWaitUrlChange(WebDriverBy::id($linkId));
    }

    function clickLinkWithText(string $linkText): void {
        $this->clickAndWaitUrlChange(WebDriverBy::linkText($linkText));
    }

    function hasFieldByName(string $fieldName, string $type): bool {
        return $this->getElement(WebDriverBy::name($fieldName)) != null;
    }

    function setTextFieldValue(string $fieldName, string $value): void {
        $this->setValueBySelector(WebDriverBy::name($fieldName), $value);
    }

    function hasRadioOption(string $fieldName, string $optionValue): bool {
        throw new Error('not implemented');
    }

    function hasSelectOptionWithLabel(string $fieldName, string $label): bool {
        throw new Error('not implemented');
    }

    function selectOptionWithLabel(string $fieldName, string $label): void {
        throw new Error('not implemented');
    }

    function hasSelectOptionWithValue(string $fieldName, string $value): bool {
        throw new Error('not implemented');
    }

    function selectOptionWithValue(string $fieldName, string $value): void {
        throw new Error('not implemented');
    }

    function getSelectedOptionText(string $fieldName): string {
        throw new Error('not implemented');
    }

    function setRadioValue(string $fieldName, string $value): void {
        throw new Error('not implemented');
    }

    function setCheckboxValue(string $fieldName, string $value): void {
        throw new Error('not implemented');
    }

    public function setFileFieldValues(string $fieldName, string $path, string $contents) : void {
        throw new Error('not implemented');
    }

    function forceFieldValue(string $fieldName, string $value): void {
        throw new Error('not implemented');
    }

    function getFieldValue(string $fieldName) {
        throw new Error('not implemented');
    }

    function submitFormByButtonPress(string $buttonName, ?string $buttonValue) {
        $this->clickAndWaitUrlChange(WebDriverBy::name($buttonName));
    }

    function getPageText(): string {
        return $this->getDriver()->getPageSource();
    }

    function getPageSource(): string {
        return $this->getDriver()->getPageSource();
    }

    private function setValueBySelector($selector, $value) {

        $this->getDriver()->wait(Globals::MAX_WAIT_TIME, Globals::POLL_FREQUENCY)->until(
            function () use ($selector, $value) {
                $input = $this->getDriver()->findElement($selector);

                $input->clear()->sendKeys($value);

                $readValue = $input->getAttribute('value');

                return $readValue === $value;
            },
            sprintf("Could not set value to element %s = '%s'",
                $selector->getMechanism(), $selector->getValue())
        );
    }

    private function clickAndWaitUrlChange($selector) {
        $element = $this->getElement($selector);

        // sometimes the element is found but if clicked too soon the click fails.
        usleep(400000);

        $previousUrl = $this->getCurrentURL();

        $this->getDriver()->executeScript("document.wtrTestState = 1;");

        $element->click();

        $this->getDriver()->wait(Globals::MAX_WAIT_TIME, Globals::POLL_FREQUENCY)->until(
            function () use ($previousUrl) {
                $tmpUrl = $this->getCurrentURL();

                return $previousUrl !== $tmpUrl;
            },
            sprintf("Url did not change from %s", $previousUrl)
        );

        $jsResult = $this->getDriver()->executeScript("return document.wtrTestState;");

        if (!$jsResult) {
            throw new FrameworkException(ERROR_J01, "Applications state is missing after url change");
        }
    }

    private function getElement($selector) : ?RemoteWebElement {
        try {

            $this->getDriver()->wait(Globals::MAX_WAIT_TIME, Globals::POLL_FREQUENCY)->until(
                WebDriverExpectedCondition::presenceOfElementLocated($selector)
            );

        } catch (NoSuchElementException $e) {

            return null;
        }

        return $this->getDriver()->findElement($selector);
    }

    private function getDriver() : RemoteWebDriver {
        if ($this->driver) {
            return $this->driver;
        }

        $this->driver = $this->createDriver();

        return $this->driver;
    }

    private function createDriver() : RemoteWebDriver {
        $options = new ChromeOptions();

        $arguments = ['no-sandbox', 'disable-gpu'];

        if (!$this->globals->showBrowser) {
            $arguments[] = 'headless';
        }

        $options->addArguments($arguments);

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        return RemoteWebDriver::create(Globals::SELENIUM_SERVER_URL, $capabilities);
    }

    function getFormSet(): FormSet {
        throw new Error('not implemented');
    }
}