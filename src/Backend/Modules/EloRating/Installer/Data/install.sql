CREATE TABLE `elo_rating_games` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `player1` int(11) DEFAULT NULL,
  `player2` float DEFAULT NULL,
  `score1` float DEFAULT NULL,
  `score2` float DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` tinytext NULL,
  `active` enum('Y','N') DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `elo_rating_players` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext,
  `start_elo` int(11) DEFAULT '1450',
  `current_elo` int(11) DEFAULT '1450',
  `active` enum('N','Y') DEFAULT 'Y',
  `games_played` int(11) DEFAULT '0',
  `won` int(11) DEFAULT '0',
  `lost` int(11) DEFAULT '0',
  `draws` int(11) DEFAULT '0',
  `meta_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `elo_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `player` int(11) DEFAULT NULL,
  `elo` int(11) DEFAULT NULL,
  `game` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
