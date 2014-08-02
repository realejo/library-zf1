 CREATE TABLE IF NOT EXISTS `album`  (
                  `id` char(1) NOT NULL,
                  `artist` varchar(100) NOT NULL,
                  `title` varchar(100) NOT NULL,
                  `deleted` tinyint(1) unsigned NOT NULL default '0',
                  PRIMARY KEY  (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;