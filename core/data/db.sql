CREATE TABLE IF NOT EXISTS `users` (
  id bigint(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(64),
  type TINYINT(1) DEFAULT 1,
  first_name VARCHAR(50),
  last_name VARCHAR(50),
  address VARCHAR(250),
  city VARCHAR(50),
  state VARCHAR(50),
  postal_code CHAR(10),
  country CHAR(3),
  phone VARCHAR(20),
  date_created DATETIME DEFAULT '0000-00-00',
  status TINYINT(1) DEFAULT 1,
  INDEX(email)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `users_hashes` (
	id bigint(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	email_or_id VARCHAR(100) NOT NULL,
	hash CHAR(64),
	type TINYINT(1) DEFAULT 1,
	date_created DATETIME DEFAULT '0000-00-00',
	status TINYINT(1) DEFAULT 0,
	INDEX(hash)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `organization` (
	id INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	user_id bigint(20) NOT NULL,
	name VARCHAR(50) NOT NULL,
	description TINYTEXT  DEFAULT NULL,
	date_created DATETIME DEFAULT '0000-00-00',
	INDEX(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `organization_members` (
	id INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	organization_id INT(10) NOT NULL,
	user_id bigint(20) NOT NULL,
	role_id TINYINT(2) DEFAULT 1,
	date_joined DATETIME DEFAULT '0000-00-00',
	status TINYINT(1) DEFAULT 1,
	INDEX(user_id),INDEX(organization_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `project` (
	id INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	user_id bigint(20) NOT NULL,
	name VARCHAR(50) NOT NULL,
	description TINYTEXT  DEFAULT NULL,
	date_created DATETIME DEFAULT '0000-00-00',
	INDEX(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `project_members` (
	id INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	project_id INT(10) NOT NULL,
	user_id bigint(20) NOT NULL,
	role_id TINYINT(2) DEFAULT 1,
	date_joined DATETIME DEFAULT '0000-00-00',
	status TINYINT(1) DEFAULT 1,
	INDEX(user_id),INDEX(project_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `message_thread` (
	id INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	user_id bigint(20) NOT NULL,
	item_id INT(10) NOT NULL,
	message_type TINYINT(2) DEFAULT 1,
	subject TINYTEXT  DEFAULT NULL,
	message TEXT  DEFAULT NULL,
	date_created DATETIME DEFAULT '0000-00-00',
	status TINYINT(1) DEFAULT 1,
	INDEX(user_id),INDEX(item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `messages` (
	id INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	user_id bigint(20) NOT NULL,
	thread_id INT(10) NOT NULL,
	message_text TEXT  DEFAULT NULL,
	date_created DATETIME DEFAULT '0000-00-00',
	status TINYINT(1) DEFAULT 1,
	INDEX(user_id),INDEX(thread_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/**
*Activity
*/

CREATE TABLE IF NOT EXISTS `activity` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `description` LONGTEXT NULL,
  `project_id` INT(10) NOT NULL,
  `owner_id` BIGINT(20) NOT NULL,
  `type_id` INT(5) UNSIGNED NOT NULL,
  `parent_activity` INT(10) UNSIGNED NULL,
  `requestor` BIGINT(20) UNSIGNED NULL,
  `request_date` DATETIME NULL,
  `estimate_duration` VARCHAR(10) NULL,
  `wbs` VARCHAR(25) NOT NULL,
  `start_date` DATETIME NULL,
  `start_time` DATETIME NULL,
  `due_date` DATETIME NULL,
  `due_time` DATETIME NULL,
  `comment` TINYTEXT NULL,
  `priority` TINYINT(2) UNSIGNED NULL,
  `status` TINYINT(2) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX (`project_id` ASC),
  INDEX (`owner_id` ASC),
  INDEX (`type_id` ASC)
  )ENGINE = InnoDB;
  
  /*
	ALTER TABLE activity ADD `wbs` VARCHAR(25) NOT NULL AFTER `estimate_duration`;
	ALTER TABLE activity ADD `start_date` DATETIME NULL AFTER `wbs`;
	ALTER TABLE activity ADD `start_time` DATETIME NULL AFTER `start_date`;
	
	ALTER TABLE activity CHANGE `estimate_duration` `estimate_duration` VARCHAR(10) NULL
  */

  CREATE TABLE IF NOT EXISTS `activity_assignment` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) NOT NULL,
  `activity_id` INT(10) UNSIGNED NOT NULL,
  `status` TINYINT(2) NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX (`users_id` ASC),
  INDEX (`activity_id` ASC)
 )ENGINE = InnoDB

 CREATE TABLE IF NOT EXISTS `activity_predecessor` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `main_activity` INT(10) NULL,
  `predecessor_id` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX (`predecessor_id` ASC)
)
ENGINE = InnoDB

CREATE TABLE IF NOT EXISTS `activity_type` (
  `id` INT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NULL,
  `description` TINYTEXT NULL,
  `status` TINYINT(2) UNSIGNED NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB

INSERT INTO `activity_type`(`name`, `description`, `status`) VALUES ('Meeting','Meeting activty',1);
INSERT INTO `activity_type`(`name`, `description`, `status`) VALUES ('Milestone','Milestone activty',1);
INSERT INTO `activity_type`(`name`, `description`, `status`) VALUES ('Task','Task activty',1);

CREATE TABLE IF NOT EXISTS `activity_type_meeting` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `organizer_user_id` BIGINT(20) NOT NULL,
  `activity_id` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX (`organizer_user_id` ASC),
  INDEX (`activity_id` ASC)
  )
ENGINE = InnoDB

CREATE TABLE IF NOT EXISTS `meeting_attendees` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `status` TINYINT(2) UNSIGNED NULL,
  `activity_meeting_id` INT(10) UNSIGNED NOT NULL,
  `user_id` BIGINT(20) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX (`activity_meeting_id` ASC),
  INDEX (`user_id` ASC)
)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `user_widget_settings` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) NOT NULL,
  `settings` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX (`user_id` ASC),
  UNIQUE(`user_id`)
)
ENGINE = InnoDB;

/**WIDGET DB**/
CREATE TABLE IF NOT EXISTS `user_meta` (
  `umeta_id` INT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) NOT NULL,
  `meta_key` VARCHAR(255) NULL,
  `meta_value` LONGTEXT NULL,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`)
)
ENGINE = InnoDB;
CREATE TABLE IF NOT EXISTS `user_widget_settings` (
  `widget_id` INT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) NOT NULL,
  `widget_settings` LONGTEXT NULL,
  `tab_name` VARCHAR(255) NULL,
  `tab_order` TINYINT(1) UNSIGNED NULL,
  PRIMARY KEY (`widget_id`),
  KEY `user_id` (`user_id`)
)
ENGINE = InnoDB;

DROP TABLE user_meta;DROP TABLE user_widget_settings;