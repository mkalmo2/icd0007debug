<?php

namespace stf\browser;

use stf\browser\page\Element;
use stf\browser\page\FormSet;

interface Browser {
    function setMaxRedirectCount(int $count): void;

    function getCurrentUrl(): string;

    function getCurrentUrlDir(): string;

    function getResponse(): ?HttpResponse;

    function reset() : void;

    function navigateTo(string $url): void;

    function getPageId(): ?string;

    function getLinkHrefById(string $id): string;

    function getLinkHrefByText(string $text): string;

    function hasLinkWithId(string $id): bool;

    function hasLinkWithText(string $linkText): bool;

    function hasElementWithId(string $id): bool;

    function getElementByInnerText(string $innerText): ?Element;

    function getElements(): array;

    function getElementAttributeValue(string $id, string $attributeName): string;

    function clickLinkWithId(string $linkId): void;

    function clickLinkWithText(string $linkText): void;

    function hasFieldByName(string $fieldName, string $type): bool;

    function setTextFieldValue(string $fieldName, string $value): void;

    function hasRadioOption(string $fieldName, string $optionValue): bool;

    function hasSelectOptionWithValue(string $fieldName, string $value): bool;

    function selectOptionWithValue(string $fieldName, string $value): void;

    function hasSelectOptionWithLabel(string $fieldName, string $label): bool;

    function selectOptionWithLabel(string $fieldName, string $label): void;

    function getSelectedOptionText(string $fieldName): string;

    function setRadioValue(string $fieldName, string $value): void;

    function setFileFieldValues(string $fieldName, string $path, string $contents): void;

    function setCheckboxValue(string $fieldName, string $value): void;

    function forceFieldValue(string $fieldName, string $value): void;

    function getFieldValue(string $fieldName); // union type string | bool

    function submitFormByButtonPress(string $buttonName, ?string $buttonValue);

    function getPageText(): string;

    function getPageSource(): string;

    function getFormSet(): FormSet;

}