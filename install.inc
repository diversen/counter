<?php

$_INSTALL['NAME'] = 'counter';

$_INSTALL['VERSIONS'] = array ('1.81', '1.82');
$_INSTALL['VERSION'] = 1.82; 
$_INSTALL['RUN_LEVEL'] = '0,6';

$_INSTALL['PUBLIC_CLONE_URL'] = "git://github.com/diversen/counter.git";
$_INSTALL['PRIVATE_CLONE_URL'] = 'git@github.com:diversen/counter.git';

$_INSTALL['INSTALL'] = function ($version = null) {

    if ($version == '1.81') {
        $sql = <<<EOF
CREATE TABLE `counter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `agent` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hits` int(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hitdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
EOF;
 
        $db = new db();
        $db->rawQuery($sql);
    }  
    
        if ($version == '1.82') {
        $sql = <<<EOF
ALTER TABLE counter
  ADD INDEX (uri);

ALTER TABLE counter
  ADD INDEX (hitdate);

EOF;
 
        $db = new db();
        $db->rawQuery($sql);
    } 
};


$_INSTALL['UNINSTALL'] = function ($version = null) {
    $sql = "DROP table `counter`";
    $db = new db();
    $db->rawQuery($sql);
};

