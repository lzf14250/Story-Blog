CREATE TABLE `user` (
 `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
 `username` varchar(12) CHARACTER SET latin1 NOT NULL,
 `password` varchar(255) CHARACTER SET latin1 NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8


CREATE TABLE `post` (
 `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 `title` tinytext NOT NULL,
 `abstract` tinytext NOT NULL,
 `content` longtext NOT NULL,
 `userID` smallint(6) unsigned NOT NULL,
 `categoryID` tinyint(3) unsigned NOT NULL,
 `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `userID` (`userID`),
 KEY `categoryID` (`categoryID`),
 CONSTRAINT `post_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`id`),
 CONSTRAINT `post_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8


CREATE TABLE `comment` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `postID` mediumint(8) unsigned NOT NULL,
 `userID` smallint(5) unsigned NOT NULL,
 `content` longtext NOT NULL,
 `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8


CREATE TABLE `category` (
 `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
 `name` varchar(12) CHARACTER SET latin1 NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8
