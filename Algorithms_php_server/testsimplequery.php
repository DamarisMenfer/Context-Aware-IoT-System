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

		if(isset($_POST["mac"])){
			$adrMac = $_POST["mac"];
		}
		
		if(isset($_POST["room"])){
			$lieu = $_POST["room"];
		}
		
		if(isset($_POST["signalsWifi"])){
			$tabWifi = $_POST["signalsWifi"];
			file_put_contents("/var/www/Log_processing/".$_POST["room"]."_raw".date("Y-m-d")."T".date("G_i_s").".txt",$tabWifi);
			

			$executionStartTime = microtime(true);
			$tabWifi = json_decode($tabWifi,true);
			
			$time = (microtime(true)-$executionStartTime);
			echo "before computation : ".$time."<br>";
			
			$executionStartTime = microtime(true);
			$tabWifi = computeWifiFootprint($tabWifi);
			$time = (microtime(true)-$executionStartTime);
			
			echo "after computation : ".$time."<br>footprint<br>";
			foreach($tabWifi as $element){
				echo $element["bssid"]."     =>     ".$element["rssi"]."<br>";
			}
		}
		
		/*echo "Bluetooth footprint :";
		if(isset($_POST["signalsBle"])){
			$tabBT = $_POST["signalsBle"];

			$tabBT = json_decode($tabBT,true);
			$tabBT = computeWifiFootprint($tabBT);
			
			var_dump($tabBT);
		}*/
		
		add_footprint($tabWifi, $adrMac, $lieu);
	?>


