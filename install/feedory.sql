CREATE TABLE  `fy_feeds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` tinytext NOT NULL,
  `url` tinytext NOT NULL,
  `active` tinyint(1) NOT NULL,
  `favicon` tinytext,
  `type` varchar(45) NOT NULL,
  `encoding` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` tinytext DEFAULT NULL,
  `language` varchar(45) DEFAULT NULL,
  `pubdate` datetime DEFAULT NULL,
  `lastbuild` datetime DEFAULT NULL,
  `imagetitle` tinytext,
  `imagelink` tinytext NOT NULL,
  `link` tinytext,
  `imageurl` tinytext,
  PRIMARY KEY (`id`)
);
