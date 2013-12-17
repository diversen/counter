<?php

$_INSTALL['NAME'] = 'counter';

$_INSTALL['VERSIONS'] = array ('1.81', '1.82', '2.41', '2.45');
$_INSTALL['VERSION'] = 2.45;
$_INSTALL['RUN_LEVEL'] = '0,5';

$_INSTALL['PUBLIC_CLONE_URL'] = "git://github.com/diversen/counter.git";
$_INSTALL['PRIVATE_CLONE_URL'] = 'git@github.com:diversen/counter.git';

// Note: You will need this is your anon install functions should run
$_INSTALL['VERSIONS'] = array ('1.81', '1.82', '2.41', '2.45');
$_INSTALL['INSTALL'] = function ($version = null) {
    if ($version == '2.41') {
        moduleloader::includeModule('counter');
        $c = new counter();
        $c->updateCounterHits();
    }  
};


$_INSTALL['UNINSTALL'] = function ($version = null) {

};

