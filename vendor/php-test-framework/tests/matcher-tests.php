<?php

require_once '../public-api.php';

function containsMatcher() {
    $text = 'abc 123 dcf';

    assertThat($text, containsString('123'));

    assertThrows(function () use ($text) {
        assertThat($text, containsString('123a'));
    });
}

function doesNotContainStringMather() {
    $text = 'abc 123 dcf';

    assertThat($text, doesNotContainString('123a'));

    assertThrows(function () use ($text) {
        assertThat($text, doesNotContainString('123'));
    });
}

function isMatcher() {
    $text = 'abc';

    assertThat($text, is('abc'));

    assertThrows(function () use ($text) {
        assertThat($text, is('ab'));
    });
}

function containsOnceMatcher() {
    $text = 'abcb';

    assertThat($text, containsStringOnce('a'));

    assertThrows(function () use ($text) {
        assertThat($text, containsStringOnce('b'));
    });
}

function containsInAnyOrderMatcher() {
    $actual = [1, 2, 3];

    assertThat($actual, containsInAnyOrder([1, 2, 3]));
    assertThat($actual, containsInAnyOrder([2, 1, 3]));

    assertThrows(function () use ($actual) {
        assertThat($actual, containsInAnyOrder([]));
    });

    assertThrows(function () use ($actual) {
        assertThat($actual, containsInAnyOrder([1, 2, 4]));
    });
}

stf\runTests();
