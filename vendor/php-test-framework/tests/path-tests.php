<?php

require_once '../public-api.php';

use stf\browser\Path;

function absolute() {

    assertThat(path('/')->isAbsolute(), is(true));

    assertThat(path('a')->isAbsolute(), is(false));

    assertThat(path('/a')->isAbsolute(), is(true));

    assertThat(path('a/')->isAbsolute(), is(false));
}

function asString() {
    assertThat(path('')->asString(), is(''));
    assertThat(path('a')->asString(), is('a'));
    assertThat(path('/')->asString(), is('/'));
    assertThat(path('/a')->asString(), is('/a'));
    assertThat(path('/a/')->asString(), is('/a/'));
    assertThat(path('a/')->asString(), is('a/'));
}

function normalize() {
    assertThat(Path::normalize(path(''))->asString(), is(''));

    assertThat(Path::normalize(path('.'))->asString(), is(''));
    assertThat(Path::normalize(path('..'))->asString(), is(''));

    assertThat(Path::normalize(path('./'))->asString(), is('/'));
    assertThat(Path::normalize(path('../'))->asString(), is('/'));

    assertThat(Path::normalize(path('../..'))->asString(), is(''));
    assertThat(Path::normalize(path('../../'))->asString(), is('/'));

    assertThat(Path::normalize(path('../'))->isAbsolute(), is(true));
}

function cd() {
    assertThat(path('')->cd(path(''))->asString(), is(''));
    assertThat(path('a')->cd(path(''))->asString(), is('a'));
    assertThat(path('/')->cd(path(''))->asString(), is('/'));

    assertThat(path('')->cd(path('/a'))->asString(), is('/a'));
    assertThat(path('/')->cd(path('/a'))->asString(), is('/a'));
    assertThat(path('a')->cd(path('/b'))->asString(), is('/b'));

    assertThat(path('a')->cd(path('b'))->asString(), is('a/b'));
    assertThat(path('/a')->cd(path('b'))->asString(), is('/a/b'));

    assertThat(path('')->cd(path('.'))->asString(), is(''));
    assertThat(path('/')->cd(path('.'))->asString(), is('/'));
    assertThat(path('a')->cd(path('.'))->asString(), is('a'));

    assertThat(path('a/')->cd(path('.'))->asString(), is('a/'));
    assertThat(path('a/')->cd(path('./'))->asString(), is('a/'));
    assertThat(path('/a')->cd(path('..'))->asString(), is('/'));
    assertThat(path('/a')->cd(path('../'))->asString(), is('/'));
}


#Helpers

function path(?string $path) : Path {
    return new Path($path);
}

stf\runTests();