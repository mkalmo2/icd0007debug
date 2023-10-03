<?php

require_once 'vendor/php-test-framework/public-api.php';

const BASE_URL = 'http://localhost:8080';

function confirmationWorksWithSimpleText() {
    navigateTo(BASE_URL . '/confirm/');

    setTextFieldValue('text', 'hello');

    clickButton('sendButton');

    clickLinkWithText('Confirm');

    assertPageContainsText('Confirmed: hello');
}

function confirmationWorksWithDifferentSymbols() {
    navigateTo(BASE_URL . '/confirm/');

    $text = "hello'\"\n";

    setTextFieldValue('text', $text);

    clickButton('sendButton');

    clickLinkWithText('Confirm');

    assertPageContainsText('Confirmed: ' . $text);
}

function checksCorrectRadio() {
    navigateTo(BASE_URL . '/radios.php');

    assertThat(getFieldValue('grade'), is('3'));

    navigateTo(BASE_URL . '/ex4/radios.php?grade=4');

    assertThat(getFieldValue('grade'), is('4'));
}

function calculatesArithmeticExpressions() {
    navigateTo(BASE_URL . '/calc/');

    setTextFieldValue('number', '4');

    clickButton('cmd', 'insert');

    clickButton('cmd', 'plus');

    setTextFieldValue('number', '3');

    clickButton('cmd', 'insert');

    clickButton('cmd', 'evaluate');

    clickButton('cmd', 'minus');

    setTextFieldValue('number', '-2');

    clickButton('cmd', 'insert');

    clickButton('cmd', 'evaluate');

    assertThat(getFieldValue('display'), is('9'));
}

setBaseUrl(BASE_URL);
setLogRequests(false);
setLogPostParameters(false);
setPrintPageSourceOnError(false);

#Helpers

stf\runTests(getPassFailReporter(4));
