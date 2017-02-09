--CREATE TABLE QUERIES

CREATE TABLE IF NOT EXISTS `ideas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `creation_date` datetime NOT NULL,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `author_id` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `short_desc` tinytext NOT NULL,
  `heading_1` varchar(60) NOT NULL,
  `detailed_desc` text NOT NULL,
  `heading_2` varchar(60) NOT NULL,
  `purpose` text,
  `manager_id` int(10) unsigned NOT NULL,
  `likes` int(10) unsigned NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `pic` varchar(255),
  `has_location` int(10) unsigned NOT NULL DEFAULT '0',
  `video` varchar(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `creation_date` datetime NOT NULL,
  `date_changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `author_id` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `short_desc` tinytext NOT NULL,
  `heading_1` varchar(60) NOT NULL,
  `detailed_desc` text NOT NULL,
  `motivation` text,
  `heading_2` varchar(60) NOT NULL,
  `has_location` BOOLEAN NOT NULL DEFAULT '0',
  `heading_3` varchar(60) NOT NULL,
  `newsfeed` text DEFAULT NULL,
  `heading_4` varchar(60) NOT NULL,
  `goals` text DEFAULT NULL,
  `heading_5` varchar(60) NOT NULL,
  `obstacles` text DEFAULT NULL,
  `manager_id` int(10) unsigned NOT NULL,
  `bool_jobs` BOOLEAN NOT NULL DEFAULT '0',
  `bool_money` BOOLEAN NOT NULL DEFAULT '0',
  `pic` varchar(255),
  `forum_id` int(10) unsigned NOT NULL,
  `likes` int(10) unsigned NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `video` varchar(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `creation_date` datetime NOT NULL,
  `date_changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `username` varchar(25) NOT NULL,
  `name` varchar(64),
  `profession` varchar(50),
  `pic` varchar(255),
  `dreams` text,
  `goals` text,
  `about_me` text,
  `location_id` int(10) unsigned DEFAULT '0',
  `password` CHAR(128) NOT NULL,
  `salt` CHAR(64) NOT NULL,
  `confirmcode` varchar(32),
  `email_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `email` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user` VARCHAR(58) NOT NULL,
  `domain` VARCHAR(58) NOT NULL,
  `tld` VARCHAR(6) NOT NULL,
  `is_opted_out` BOOLEAN NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE (`tld`, `domain`, `user`)
) ENGINE=InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `bool_paid` BOOLEAN NOT NULL DEFAULT '0',
  `proj_id` int(10) unsigned NOT NULL,
  `contact_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `idea_subscribers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idea_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned not NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`idea_id`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `idea_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idea_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned not NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`idea_id`, `tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `idea_pics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `idea_id` int(10) unsigned not NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`idea_id`,`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `project_subscribers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `project_id` int(10) unsigned not NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`user_id`, `project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `project_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `project_id` int(10) unsigned not NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`user_id`, `project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `project_admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `project_id` int(10) unsigned not NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`user_id`, `project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `project_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(10) unsigned NOT NULL,
  `project_id` int(10) unsigned not NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`tag_id`, `project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_pics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `user_id` int(10) unsigned not NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`user_id`, `url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `project_pics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `project_id` int(10) unsigned not NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`project_id`, `url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(10) unsigned,
  `country_id` int(10) unsigned NOT NULL,
  `region_id` int(10) unsigned,
  `state_id` int(10) unsigned,
  PRIMARY KEY (`id`),
  UNIQUE (`country_id`, `region_id`, `state_id`, `city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `long_name` varchar(80) NOT NULL,
  `country_code` char(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `regions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `country_code` char(2) NOT NULL,
  `region_code` char(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`country_code`,`region_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `combined` varchar(200) NOT NULL,
  `population` int(10) unsigned NOT NULL,
  `country_code` char(2) NOT NULL,
  `region` char(2) NOT NULL,
  `region_id` int(10) unsigned NOT NULL,
  `country_id` int(10) unsigned NOT NULL,
  `latitude` varchar(20) NOT NULL,
  `longitude` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `combined` (`combined`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `project_locations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(10) unsigned NOT NULL,
  `project_id` int(10) unsigned not NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`location_id`,`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `idea_locations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(10) unsigned NOT NULL,
  `idea_id` int(10) unsigned not NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`idea_id`,`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `job_locations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(10) unsigned NOT NULL,
  `job_id` int(10) unsigned not NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`location_id`,`job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `job_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`job_id`,`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
