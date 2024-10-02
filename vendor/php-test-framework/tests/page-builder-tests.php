<?php

require_once '../public-api.php';

use stf\browser\page\NodeTree;
use stf\browser\page\PageParser;
use stf\browser\page\PageBuilder;
use stf\browser\page\Page;

function buildPageSimple() {
    $html = '<a id="link1"> abc</a>';

    $page = getPage($html);

    $link = $page->getLinkById('link1');

    assertThat($link->getText(), is(' abc'));
}

function buildSimpleForm() {

    $html = file_get_contents('../test-files/form.html');

    $page = getPage($html);

    assertThat($page->getId(), is('form-page-id'));
}

function buildFormWithUncheckedInputs() {

    $html = file_get_contents('../test-files/empty-controls.html');

    $form = getPage($html)->getFormSet();

    assertThat($form->getTextFieldByName('t1')->getValue(), is(''));
    assertThat($form->getCheckboxByName('c1')->getValue(), is(null));
    assertThat($form->getRadioByName('r1')->getValue(), is(null));
    assertThat($form->getSelectByName('s1')->getValue(), is(null));
}

function findElementByInnerText() {

    $html = '<main>
                 <div data-task-id="id1">abc</div>
             </main>';

    $page = getPage($html);

    $element = $page->getElementByInnerText('abc');

    $id = $element->getAttributeValue('data-task-id');

    assertThat($id, is('id1'));
}

#Helpers

function getPage(string $html) : Page {
    $parser = new PageParser($html);

    $nodeTree = new NodeTree($parser->getNodeTree());

    return (new PageBuilder($nodeTree, $html))->getPage();
}

stf\runTests();