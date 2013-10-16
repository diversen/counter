CREATE TABLE `counter_hits` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hits` int(11) unsigned DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uri` (`uri`)
) ENGINE=InnoDB AUTO_INCREMENT=4035 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci