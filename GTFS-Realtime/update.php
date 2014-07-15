<?php
$startTime = microtime(true);

$lat = $_GET["lat"];
$lon = $_GET["lon"];
$vehicle_id = $_GET["vehicle_id"];
$route_id = $_GET["route_id"];

$json = file_get_contents('realtimeData.json');
$theData = json_decode($json, true);
$entities = $theData["message"]["entities"];

function makeVehicle($lat, $lon, $vehicle_id, $route_id){
	$update["id"] = $vehicle_id;
        $update["vehicle"] = array(
	        "trip_update" => array(
                        "route_id" => $route_id
                ),
                "vehicle" => array(
                        "id" => $vehicle_id,
                        "label" => $vehicle_id,
                        "license_plate" => $vehicle_id
                ),
                "position" => array(
                        "latitude" => $lat,
                        "longitude" => $lon,
                        "bearing" => 0
        	)
	);
	return $update;
}

$updated = false;

for($i = 0; $i<sizeOf($entities); $i++){
	if(isset($entities[$i]["id"]) && isset($vehicle_id) && $entities[$i]["id"] == $vehicle_id){
		if(isset($lat) && isset($lon) && isset($vehicle_id) && isset($route_id)){
			$vehicles[] = makeVehicle($lat, $lon, $vehicle_id, $route_id);
			$updated = true;
		}
	}else{
		$vehicles[] = $entities[$i];
	}
}

if(!$updated){
	$vehicles[] = makeVehicle($lat, $lon , $vehicle_id, $route_id);
}

//print_r($vehicles);

$theData["message"]["header"]["timestamp"] = time();
$theData["message"]["header"]["gtfs_realtime_version"] = "1.0";
$theData["message"]["entities"] = $vehicles;
$theData["generationTime"] = microtime(true) - $startTime;
$theData["numberOfRoutes"] = sizeOf($vehicles);
$theData["numberOfVehicles"] = sizeOf($vehicles);
$jsonData = json_encode($theData, JSON_PRETTY_PRINT);
echo $jsonData;
file_put_contents('realtimeData.json', $jsonData);
