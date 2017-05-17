Use the following SQL-Statements to create the new table:
=========================================================

--
-- Table structure for table `gymhistory`
--
CREATE TABLE IF NOT EXISTS `gymhistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gym_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `team_id` smallint(6) NOT NULL,
  `guard_pokemon_id` smallint(6) NOT NULL,
  `gym_points` int(11) NOT NULL DEFAULT '0',
  `last_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pokemon_uids` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gym_id` (`gym_id`),
  KEY `gym_points` (`gym_points`),
  KEY `last_modified` (`last_modified`),
  KEY `team_id` (`team_id`),
  KEY `last_updated` (`last_updated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8mb4_unicode_ci;

--
-- Add inital dataset for table `gymhistory`
--
INSERT INTO `gymhistory`
  (
    SELECT NULL, g.gym_id, g.team_id, g.guard_pokemon_id, g.gym_points, g.last_modified, g.last_modified as last_updated,
    (
      SELECT GROUP_CONCAT(DISTINCT pokemon_uid SEPARATOR ',')
      FROM gymmember AS gm
      WHERE gm.gym_id = g.gym_id GROUP BY gym_id
    ) AS pokemon_uids
    FROM gym AS g
  );


Use the following SQL-Statements to create the event to update the new table:
=============================================================================

--
-- Create event `gymhistory_update`
--
DELIMITER //
CREATE EVENT IF NOT EXISTS `gymhistory_update`
ON SCHEDULE EVERY 20 SECOND
DO BEGIN
  INSERT INTO gymhistory (SELECT NULL, g.gym_id, g.team_id, g.guard_pokemon_id, g.gym_points, g.last_modified, g.last_modified as last_updated, (SELECT GROUP_CONCAT(DISTINCT pokemon_uid SEPARATOR ',') FROM gymmember AS gm WHERE gm.gym_id = g.gym_id GROUP BY gym_id) AS pokemon_uids FROM gym AS g WHERE g.last_modified > (SELECT MAX(last_modified) FROM gymhistory));
  UPDATE gymhistory AS gh
  JOIN (SELECT gym_id, MAX(last_modified) as max_last_modified FROM gymhistory GROUP BY gym_id)
  AS gg ON gh.gym_id = gg.gym_id AND gh.last_modified = gg.max_last_modified
  JOIN (SELECT gym_id, last_scanned, GROUP_CONCAT(DISTINCT pokemon_uid SEPARATOR ',') AS pokemon_uids FROM gymmember AS gm GROUP BY gym_id)
  AS gm ON gh.gym_id = gm.gym_id
  SET gh.last_updated = gm.last_scanned, gh.pokemon_uids = gm.pokemon_uids
  WHERE gh.last_updated < gm.last_scanned;
END
//
DELIMITER ;

--
-- Enable MySQL event scheduler
--
SET GLOBAL event_scheduler = ON;