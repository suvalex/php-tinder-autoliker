<?php

require_once 'TinderAPI.php';

$cfg = parse_ini_file('config.ini', true);

try {
    $api = new TinderAPI($cfg['fb_id'], $cfg['fb_token']);

	$cnt = 0;
    while (true) {
        $profiles = $api->getProfiles();
        foreach($profiles as $profile) {                        
            $user_id = $profile['user']["_id"];
            $match = $api->like($user_id);
			$cnt++;
            $user = [
                "id" => $user_id,
                "name" => $profile['user']["name"],
                "birth_date" => date("d/M/y", strtotime($profile['user']["birth_date"])),
                "distance" => $profile["distance_mi"],
                "photo" => $profile['user']["photos"][0]["processedFiles"][0]["url"],
                "match" => $match
            ];
            if(!empty($cfg['results_file'])) {
                $f = fopen(realpath(dirname(__FILE__)) . $cfg['results_file'], 'a');
                fputcsv($f, array_values($user));
                fclose($f);
            }
        }
    }
} catch (Exception $e) {
    echo 'Liked ' . $cnt . ' profiles. ' . $e->getMessage();
}