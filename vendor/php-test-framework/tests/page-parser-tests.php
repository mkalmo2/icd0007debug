<?php

require_once '../public-api.php';

use stf\browser\page\PageParser;

function parserErrors() {

    $html = <<<END
            <html>
            <body>
            <input value="m=2""></a>
            </body>
            </html>
            END;

    $parser = new PageParser($html);

    $result = $parser->validate();

    assertThat($result->isSuccess(), is(false));
    assertThat($result->getLine(), is(3));
    assertThat($result->getColumn(), is(19));

    assertThat($result->getSource(), containsString('001 <html>'));
    assertThat($result->getSource(), containsString('002 <body>'));
    assertThat($result->getSource(), containsString('003 <input value="m=2"' . PHP_EOL));
    assertThat($result->getSource(), containsString('                      ^' . PHP_EOL));
}


stf\runTests();