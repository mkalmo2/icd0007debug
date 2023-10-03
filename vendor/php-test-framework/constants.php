<?php

const REQUEST_TIMEOUT = 3; // each request should finish under 3 seconds

const ERROR_G01 = 'G01'; // unexpected

const ERROR_C01 = 'C01'; // message should be enough
const ERROR_C02 = 'C02'; // not equal
const ERROR_C03 = 'C03'; // string does not contain substring
const ERROR_C04 = 'C04'; // contains substring but should not
const ERROR_C05 = 'C05'; // does not contain substring once
const ERROR_C06 = 'C06'; // does not contain discounting order
const ERROR_C07 = 'C07'; // does not match any option

const ERROR_D01 = 'D01'; // wrong page id

const ERROR_N01 = 'N01'; // Error reading socket
const ERROR_N02 = 'N02'; // bad response code
const ERROR_N03 = 'N03'; // Timeout / nothing fetched

const ERROR_H01 = 'H01'; // Parse exception
const ERROR_H02 = 'H02'; // bad characters in url
const ERROR_H03 = 'H03'; // unexpected current url
const ERROR_H04 = 'H04'; // no text on the page text
const ERROR_H05 = 'H05'; // no text in the page source
const ERROR_H06 = 'H06'; // multiple fields with the same name

const ERROR_W02 = 'W02'; // link is not relative
const ERROR_W03 = 'W03'; // no link with id
const ERROR_W04 = 'W04'; // no link with text
const ERROR_W05 = 'W05'; // no input with name
const ERROR_W06 = 'W06'; // no button with name
const ERROR_W07 = 'W07'; // no form on page
const ERROR_W08 = 'W08'; // no element with id on page
const ERROR_W09 = 'W09'; // element with id should not be on the page
const ERROR_W11 = 'W11'; // radio does not have this option
const ERROR_W12 = 'W12'; // select does not have this option
const ERROR_W13 = 'W13'; // no text field with name
const ERROR_W14 = 'W14'; // no radio with name
const ERROR_W15 = 'W15'; // no checkbox with name
const ERROR_W16 = 'W16'; // no select with name
const ERROR_W17 = 'W17'; // no file with name
const ERROR_W18 = 'W18'; // field should not exist
const ERROR_W19 = 'W19'; // button should not exist
const ERROR_W20 = 'W20'; // not front controller link
const ERROR_W21 = 'W21'; // should not print any output

const ERROR_J01 = 'J01'; // applications state is missing after url change
