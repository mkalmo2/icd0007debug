<?php

dump_parameters();

function dump_parameters() {
    print '<pre>';
    print 'GET: ' . print_r($_GET, true);
    print 'POST: ' . print_r($_POST, true);
    print 'HEADERS: ' . print_r(getallheaders1(), true);
    print '</pre>';
}

function getallheaders1() { 
   $headers = array (); 
   foreach ($_SERVER as $name => $value) 
   { 
       if (substr($name, 0, 5) == 'HTTP_') 
       { 
           $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
       } 
   } 
   return $headers; 
} 
