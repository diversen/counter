<?php

$_INSTALL['NAME'] = 'counter';

$_INSTALL['VERSIONS'] = array ('1.81', '1.82', '2.41');
$_INSTALL['VERSION'] = 2.41;
$_INSTALL['RUN_LEVEL'] = '0,6';

$_INSTALL['PUBLIC_CLONE_URL'] = "git://github.com/diversen/counter.git";
$_INSTALL['PRIVATE_CLONE_URL'] = 'git@github.com:diversen/counter.git';

$_INSTALL['INSTALL'] = function ($version = null) {
    if ($version == '2.41') {
        echo "The next operation may take some time - be patient!\n";
        moduleloader::includeModule('counter');
        
        $c = new counter();
        $c->updateCounterHits();
    }  
};


$_INSTALL['UNINSTALL'] = function ($version = null) {

};

