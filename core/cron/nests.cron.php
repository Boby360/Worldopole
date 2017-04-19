<?php
	
// -----------------------------------------------------------------------------------------------------------
// Nests datas 
// 
// 
// -----------------------------------------------------------------------------------------------------------

$nest_exclude_pokemon_ids = implode(",", $config->system->nest_exclude_pokemon);

$req = "SELECT p.pokemon_id, max(p.latitude) as latitude, max(p.longitude) as longitude, count(p.pokemon_id) as total_pokemon, s.kind, s.latest_seen
FROM pokemon p 
INNER JOIN spawnpoint s ON (p.spawnpoint_id = s.id) 
WHERE p.disappear_time > UTC_TIMESTAMP - INTERVAL 24 HOUR 
AND p.pokemon_id NOT IN (".$nest_exclude_pokemon_ids.") 
GROUP BY p.spawnpoint_id, p.pokemon_id 
HAVING count(p.pokemon_id) >= 6 
ORDER BY p.pokemon_id";
$result = $mysqli->query($req);

while ($data = $result->fetch_object()) {
	$nests['pid'] = $data->pokemon_id;
	$nests['c'] = $data->total_pokemon;
	$nests['lat'] = $data->latitude;
	$nests['lng'] = $data->longitude;
	$starttime = $data->latest_seen - substr_count($data->kind, "s") * 900;
	if ($starttime < 0) {
		$starttime = 3600 + $starttime;
	}
	$nests['st'] = floor($starttime / 60);
	$nests['et'] = floor($data->latest_seen / 60);

	// Add the data to array
	$nestsdatas[] = $nests;
}

// Write file
file_put_contents($nests_file, json_encode($nestsdatas));
