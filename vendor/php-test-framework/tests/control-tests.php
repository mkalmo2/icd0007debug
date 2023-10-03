<?php

require_once '../public-api.php';

use stf\browser\page\RadioGroup;
use stf\browser\page\Checkbox;
use stf\browser\page\Select;

function radioGroupTest() {

    $radio = new RadioGroup('r1');

    $radio->addOption("v1");
    $radio->addOption("v2");

    assertThat($radio->getValue(), is(null));

    $radio->selectOption("v1");

    assertThat($radio->getValue(), is('v1'));

    assertThat($radio->hasOption('v1'), is(true));
    assertThat($radio->hasOption('v2'), is(true));
    assertThat($radio->hasOption('v3'), is(false));
}

function selectByValue() {
    $select = new Select('s1');

    $select->addOption("v1", "Value 1", false);
    $select->addOption("v2", "Value 2", false);
    $select->addOption("v3", "Value 3", false);

    assertThat($select->getValue(), is('v1'));

    $select->selectOptionWithValue("v2");

    assertThat($select->getValue(), is('v2'));
}

function selectByLabel() {

    $select = new Select('s1');

    $select->addOption("v1", "Value 1", false);
    $select->addOption("v2", "Value 2", false);
    $select->addOption("v3", "Value 3", false);

    assertThat($select->getValue(), is('v1'));

    $select->selectOptionWithText("Value 2");

    assertThat($select->getValue(), is('v2'));

    assertThat($select->hasOptionWithLabel("Value 1"), is(true));
    assertThat($select->hasOptionWithLabel("Value 4"), is(false));
}

function selectLastValueIfMultipleOptionsSelected() {
    $select = new Select('s1', true);

    $select->addOption("v1", "Value 1", true);
    $select->addOption("v2", "Value 2", true);

    assertThat($select->getValue(), is('v2'));
}

function multiselectHasNoDefault() {
    $select = new Select('s1', true);

    $select->addOption("v1", "Value 1", false);

    assertThat($select->getValue(), is(''));
}

function checkboxWithValue() {
    $checkbox = new Checkbox('c1', '1');

    assertThat($checkbox->isChecked(), is(false));
    assertThat($checkbox->getValue(), is(null));

    $checkbox->check(true);

    assertThat($checkbox->isChecked(), is(true));
    assertThat($checkbox->getValue(), is('1'));
}

function checkboxWithDefaultValue() {
    $checkbox = new Checkbox('c1', null, true);

    assertThat($checkbox->getValue(), is('on'));
}

stf\runTests();