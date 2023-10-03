<?php

require_once '../public-api.php';

use stf\browser\page\PageParser;
use stf\browser\page\PageBuilder;
use stf\browser\page\FormSet;
use stf\browser\page\NodeTree;
use stf\browser\RequestBuilder;
use stf\browser\Url;

function buildsRequestFromForm() {
    $html = '<form>
                <input name="t1" value="t1_v" />
                <input name="r1" type="radio" value="r1_v" checked />
                <input name="r2" type="radio" value="r2_v" />
                <input name="c1" type="checkbox" value="c1_v" checked />
                <input name="c2" type="checkbox" value="c2_v"  />
                
                <button name="b1" type="submit">Send</button>
             </form>';

    $builder = new RequestBuilder(getFormSet($html), new Url(''));
    $request = $builder->requestFromButtonPress('b1', null);

    $params = $request->getParameters();

    assertThat($params['t1'], is('t1_v'));
    assertThat($params['r1'], is('r1_v'));
    assertThat($params['c1'], is('c1_v'));
    assertThat($params['b1'], is(''));

    assertThat(isset($params['r2']), is(false));
    assertThat(isset($params['c2']), is(false));

}

#Helpers

function getFormSet(string $html) : FormSet {
    $parser = new PageParser($html);

    $nodeTree = new NodeTree($parser->getNodeTree());

    return (new PageBuilder($nodeTree, $html))->getPage()->getFormSet();
}

stf\runTests();