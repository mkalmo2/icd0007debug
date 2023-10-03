<?php

require_once '../public-api.php';

function findsFunctionNamesFromSource() {

    $src = <<<EOF
            <?php function a1() : void {} function a2(){
                assertThrows(function () { assertThat(1, 3); });
            } 
            #Helpers function a3(){}
            EOF;

    $names = stf\getTestFunctionNames($src);

    assertThat($names, contains(['a1', 'a2']));
}

stf\runTests();