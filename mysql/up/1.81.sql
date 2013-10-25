DROP table IF EXISTS `counter`;

CREATE TABLE `counter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `agent` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hits` int(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hitdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE counter ADD INDEX (uri);

ALTER TABLE counter ADD INDEX (hitdate);