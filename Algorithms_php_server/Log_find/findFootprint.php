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
	/*$q = "prefix ex: <http://example.com/ns/> describe<http://example.com/ns/ex:aq>";
	$result = querySparql($q);
	echo '<p>';
	var_dump($result);
	echo '</p>';*/
	// if(isset($_POST["mac"])){
		// $adrMac = $_POST["mac"];
	// }

	// if(isset($_POST["lieu"])){
		// $lieu = $_POST["lieu"];
	// }
	
	// echo "<hr>";
	// echo "Wifi footprint :";
	$listofFootprint = "";
	if(isset($_POST["signalsWifi"])){
		$tabWifi = $_POST["signalsWifi"];
		file_put_contents("/var/www/Log_find/find".date("Y-m-d")."T".date("G_i_s").".txt",$tabWifi);
			
		
		$tabWifi = json_decode($tabWifi,true);
		$listofFootprint = find_footprint($tabWifi);
		
		// var_dump($tabWifi);
	}
	// echo "<hr>";
	// echo "Bluetooth footprint :";
	// if(isset($_POST["signalsBle"])){
		// $tabBT = $_POST["signalsBle"];

		// $tabBT = json_decode($tabBT,true);
		// $tabBT = computeWifiFootprint($tabBT);
		
		// var_dump($tabBT);
	// }

	$maxScore = 0;
	$maxRssi = 0;
	$room = "NULL";
	foreach($listofFootprint as $footprint){
		if($footprint["score"] >=3){
			if($footprint["score"] == $maxScore){
				if($footprint["rssi_s"] < $maxRssi){
					$maxRssi = $footprint["rssi_s"];
					$room = $footprint["room"];
				}
			}elseif($footprint["score"] < $maxScore){
				$maxScore = $footprint["score"];
				$maxRssi = $footprint["rssi_s"];
				$room = $footprint["room"];
			}
		}
	}
	var_dump($listofFootprint);
	$return = json_encode(array("room" => $room));
	echo $return
	// var_dump($listofFootprint);
	
	// echo "<hr>";
?>