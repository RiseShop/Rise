CREATE TABLE `geo_city` (
  `id` mediumint(8) unsigned NOT NULL,
  `region_id` mediumint(8) unsigned DEFAULT NULL,
  `name_ru` varchar(128) NOT NULL,
  `name_en` varchar(128) NOT NULL,
  `lat` decimal(10,5) NOT NULL,
  `lon` decimal(10,5) NOT NULL,
  `okato` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Информация о городах для SxGeo 2.2';

CREATE TABLE `geo_country` (
  `id` tinyint(3) unsigned NOT NULL,
  `iso` char(2) NOT NULL,
  `continent` char(2) NOT NULL,
  `name_ru` varchar(128) NOT NULL,
  `name_en` varchar(128) NOT NULL,
  `lat` decimal(6,2) NOT NULL,
  `lon` decimal(6,2) NOT NULL,
  `timezone` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Информация о странах для SxGeo 2.2';

CREATE TABLE `geo_region` (
  `id` mediumint(8) unsigned NOT NULL,
  `iso` varchar(7) NOT NULL,
  `country` char(2) NOT NULL,
  `name_ru` varchar(128) NOT NULL,
  `name_en` varchar(128) NOT NULL,
  `timezone` varchar(30) NOT NULL,
  `okato` char(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Информация о регионах для SxGeo 2.2';