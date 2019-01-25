<?php
	include "sparqLib.php";

	if(isset($_POST["login"]) && $_POST["password"]){
		$login = $_POST["login"];
		$pwd = $_POST["password"];
	}else{
		echo "Not Authorized";
		exit;
	}
	
	if(!signIn($login,$pwd)){
		echo "Not Authorized";
		exit;
	}
?>
<?php
	$listofFootprint = "";
	if(isset($_POST["signalsWifi"])){
		$tabWifi = $_POST["signalsWifi"];
		//save the input locally 
		file_put_contents("/var/www/Log_find/find".date("Y-m-d")."T".date("G_i_s").".txt",$tabWifi);
			
		
		$tabWifi = json_decode($tabWifi,true);
		$listofFootprint = find_footprint($tabWifi);

	}

	$maxScore = 0;
	$minDist = 9999;
	$room = "NULL";
	//Algorithm to chose the best room
	foreach($listofFootprint as $footprint){
		if($footprint["score"] >=3){
			//by rank
			if(($footprint["rssi_s"]/$footprint["score"]) < $minDist){
				$minDist = ($footprint["rssi_s"]/$footprint["score"]);
				$room = $footprint["room"];
			}
			//by score then rssi
			/*if($footprint["score"] == $maxScore){
				if($footprint["rssi_s"] < $minDist){
					$minDist = $footprint["rssi_s"];
					$room = $footprint["room"];
				}
			}elseif($footprint["score"] > $maxScore){
				$maxScore = $footprint["score"];
				$minDist = $footprint["rssi_s"];
				$room = $footprint["room"];
			}*/
		}
	}
	//the room
	$return = json_encode(array("room" => $room));
	echo $return;
?>