CREATE TABLE  `fy_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` tinytext NOT NULL,
  `description` text NOT NULL,
  `content` text NOT NULL,
  `link` tinytext NOT NULL,
  `author` tinytext NOT NULL,
  `postid` tinytext NOT NULL,
  `date` datetime NOT NULL,
  `feedname` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
);
