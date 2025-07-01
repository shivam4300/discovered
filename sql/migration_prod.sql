ALTER TABLE users ADD playfab_id varchar(16) NULL;
CREATE INDEX users_playfab_id_IDX USING BTREE ON users (`playfab_id`);

DROP TABLE IF EXISTS `gamification_log`;
CREATE TABLE `gamification_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL,
  `subject` varchar(255) NOT NULL,
  `verb` varchar(255) NOT NULL,
  `complement` varchar(255) NOT NULL,
  `params` TEXT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
CREATE INDEX gamification_log_event_IDX USING BTREE ON gamification_log (`subject`,`verb`,`complement`);
CREATE INDEX gamification_log_timestamp_IDX USING BTREE ON gamification_log (`timestamp`);