<?php
	
	//Log In Verification
	function signIn($login, $pwd){
		$trueLogin = "ISSProject";
		$truePwd = "ISSSmartCampus";
		if(isset($login) && isset($pwd)){
			if($login == $trueLogin && $pwd == $truePwd){
				return true;
			}
		}
		return false;
	}
	
	// Compute Wifi Signals of the Hardware Reference
	function computeWifiSignal($tabSignals){
		
		$tab = array();
		$tabCount = array();
		
		// Sum of all signals by ID
		foreach($tabSignals as $signal){
			if(array_key_exists($signal["id"],$tab)){
				$tab[$signal["id"]] += $signal["rssi"];
			}else{
				$tab[$signal["id"]] = $signal["rssi"];
			}
			
			if(array_key_exists($signal["id"],$tabCount)){
				$tabCount[$signal["id"]]++;				
			}else{
				$tabCount[$signal["id"]] = 1;
			}
		}
		
		// Divide by count
		foreach($tab as $key => $val){
			// if($tabCount[$key] != $scanNumber){
				// $tab[$key] = -1000;
			// }else{
				$tab[$key] = $val/$tabCount[$key];
			// }
			
		}
		arsort($tabCount);
		arsort($tab);
		$cpt = 0;
		$tabReturn = array();
		foreach(array_unique($tabCount) as $count){
			foreach($tab as $key => $val){
				if($tabCount[$key] == $count){
					// Uncomment to limit the footprint by 5 network
					//if($cpt >= 5){break 2;}
					array_push($tabReturn,array("bssid" => $key, "rssi" => $val));
					$cpt++;
				}
			}
		}
		return $tabReturn;
	}
	
	function computeWifiFootprint($tabSignals){
		
		$result = computeWifiSignal($tabSignals);
		//For more computation
		
		return $result;
	}
	
	//Generic Query SparQL
	function querySparql($query){
		global $prefix;
		
		//Query SparQL Localhost Fuseki
		$serverAddr = "http://localhost:3030/tdb/query?query=";
		$query = urlencode($prefix.$query);
		$format = "&format=json";
		
		
		//Curl Request
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $serverAddr.$query.$format);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);
        curl_close($ch);
		
		// var_dump($output);

		return $output;
	}
	
	//PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
	//PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
	//PREFIX owl: <http://www.w3.org/2002/07/owl#>
	//PREFIX sosa: <http://www.w3.org/ns/sosa/>
	//PREFIX iss: <http://www.semanticweb.org/iss_v1#>
	//PREFIX wlan: <http://www.semanticweb.org/wlan#>
	//PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

	$prefix = "PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> PREFIX owl: <http://www.w3.org/2002/07/owl#> PREFIX sosa: <http://www.w3.org/ns/sosa/> PREFIX iss: <http://www.semanticweb.org/iss_v1#> PREFIX wlan: <http://www.semanticweb.org/wlan#> PREFIX xsd: <http://www.w3.org/2001/XMLSchema#> ";

	function add_footprint($wifi,$hardware,$place){
		//create_footprint
		//name of the footprint, result
		$date = date("Y-m-d")."T".date("G:i:s");
		$hardware = str_replace(":","_",$hardware);
		$footprint = str_replace(":","_","Hardware".$hardware."Date".$date);
		$result = $footprint."-result";
		//values (BSSID & RSSI) of wifi signal
		$values = "";
		$i=0;
		while($i < count($wifi)){
			$values .= "iss:wifi".$i.$result." a wlan:Value. 
						iss:".$footprint." sosa:hasResult iss:wifi".$i.$result.". 
						iss:wifi".$i.$result." wlan:has_BSSID iss:BSSID_wifi".$i.$footprint.". 
			 			iss:wifi".$i.$result." wlan:has_RSSI iss:RSSI_wifi".$i.$footprint.". 
						iss:BSSID_wifi".$i.$footprint." wlan:has_value \"".$wifi[$i]["bssid"]."\"^^xsd:string. 
						iss:RSSI_wifi".$i.$footprint." wlan:has_value \"".$wifi[$i]["rssi"]."\"^^xsd:integer. "; 
			$i++;
		}

		$query = "INSERT DATA { 
			iss:".$footprint." a iss:Footprint. 
			iss:".$hardware." a iss:Hardware. 
			iss:".$footprint." sosa:madeBySensor iss:".$hardware.". 
			iss:".$hardware." sosa:madeObservation iss:".$footprint.". 
			iss:".$place." a iss:Place. 
			iss:".$place." rdfs:label \"".$place."\"^^xsd:string.
			iss:".$footprint." iss:is_in_place iss:".$place.". 
			iss:".$place." iss:has_footprint iss:".$footprint.". 
			iss:".$result." sosa:isResultOf iss:".$footprint.". 
			".$values."			
			iss:".$footprint." sosa:resultTime \"".$date."\"^^xsd:dateTime.
			}";
		update($query);
	} 
	
	//doesn't work, need to be review
	function delete_footprint($hardware){
		//find the footprint of the hardware
		$query = "select ?footprint where{ iss:".$hardware." sosa:madeObservation ?footprint. }";
		$response = json_decode(querySparql($query),true)["results"]["bindings"];
		var_dump($response);
		if($response == null){
			echo "<br>nothing to delete<br>";
			return;
		}
		$footprint= $response[0]["footprint"]["value"];
		$footprint= str_replace("http://www.semanticweb.org/iss_v1#","",$footprint);
		$result = $footprint."-result";
		//delete all data connected to the footprint
		//the valueS of wifi signal
		$values = "";
		for ($i=0; $i<10; $i++) {
			$values .= "iss:BSSID_wifi".$i.$footprint." ?a ?b".$i.". 
						iss:RSSI_wifi".$i.$footprint." ?a ?r".$i.".
						?bb".$i." ?z iss:BSSID_wifi".$i.$footprint.".  
						?rr".$i." ?y iss:RSSI_wifi".$i.$footprint.". 	
						";
		}
		$query = "DELETE WHERE { 
			iss:".$footprint." ?a ?b. 
			?c ?d iss:".$footprint.". 
			iss:".$result." ?e ?f. ".$values."}";
		var_dump($query);
		update($query);
	}
	
	// give a list of footprint with a score
	function find_footprint($wifi){
		$listOfFootprint = array();
		$listOfFootprintCompare = array();
		foreach($wifi as $element)
		{
			//select only one queries
			
			//AVG of RSSI of 1 room 
			//TO DO: if there are many data in the KB, this query will consume a lot of CPU
			//       -> Limit the result of the sub query, for example, the 100 lastest
			$query = "SELECT (?footprint3 as ?footprint) (AVG(?rssi1) AS ?rssi) ?place WHERE { 
				?bssid wlan:has_value \"".$element["bssid"]."\"^^xsd:string. 
				?result wlan:has_BSSID ?bssid. 
				?result wlan:has_RSSI ?wifi. 
				?wifi wlan:has_value ?rssi1. 
				?footprint1 sosa:hasResult ?result. 
				?place1 iss:has_footprint ?footprint1.
				?place1 rdfs:label ?place.
				
				{
					SELECT (MAX(?footprint2) AS ?footprint3) WHERE{
						?place1 iss:has_footprint ?footprint2
					}
					GROUP BY ?place1
				}
				?place1 iss:has_footprint ?footprint3.
				}
				GROUP BY ?place ?footprint3";
			
			
			//find the lastest footprint for 1 room
			/*$query = "SELECT ?bssid ?footprint ?reslut ?rssi ?place WHERE { 
				?bssid wlan:has_value \"".$element["bssid"]."\"^^xsd:string. 
				?result wlan:has_BSSID ?bssid. 
				?result wlan:has_RSSI ?wifi. 
				?wifi wlan:has_value ?rssi. 
				?footprint sosa:hasResult ?result. 
				?place1 iss:has_footprint ?footprint.
				?place1 rdfs:label ?place.
				filter not exists{
					?footprint sosa:resultTime ?time.
					?footprint2 sosa:resultTime ?time2.
					?place1 iss:has_footprint ?footprint2
					filter(?time < ?time2)
				}
				} ";*/
				
				//static one : works only if there are only one footprint 
				// for one room in the knowledge base
				/*$query = "SELECT ?bssid ?footprint ?result ?rssi ?place WHERE { 
				?bssid wlan:has_value \"".$element["bssid"]."\"^^xsd:string. 
				?result wlan:has_BSSID ?bssid. 
				?result wlan:has_RSSI ?wifi. 
				?wifi wlan:has_value ?rssi. 
				?footprint sosa:hasResult ?result. 
				?place1 iss:has_footprint ?footprint.
				?place1 rdfs:label ?place.
				} ";*/
				//echo $query."<br>";
			//get a liste of rssi corresponds the bssid
			// $list_result : all info demanded
			$list_result = json_decode(querySparql($query),true)["results"]["bindings"];
			//var_dump($list_result);
			foreach($list_result as $result){
				//var_dump($result);
				//if the footprint is not already in the list , add a new element to the list
				if (!in_array($result["footprint"]["value"], $listOfFootprintCompare)){
					
					//footprint + score
					$new = array('footprint'=>$result["footprint"]["value"],'score'=>1, 'rssi_s' => abs($element["rssi"]-$result["rssi"]["value"]), 'rssi_h' => $result["rssi"]["value"], 'room' => $result["place"]["value"]);
					//add new element 
					array_push($listOfFootprint, $new);
					array_push($listOfFootprintCompare, $result["footprint"]["value"]);
				}else{
					
					//footprint already in the list , add 1 score
					//find key of the element
					$key = array_search($result["footprint"]["value"], array_column($listOfFootprint, "footprint"));
					//change score : add 1
					$listOfFootprint[$key]["score"] = $listOfFootprint[$key]["score"] + 1;
					$listOfFootprint[$key]["rssi_s"] += abs($element["rssi"]-$result["rssi"]["value"]);
					$listOfFootprint[$key]["rssi_h"] += $result["rssi"]["value"];
				} // check if the footprint is already in the list 		
			} // loop through query result
		} // loop through wifi list
		
		// TO CHANGE !!
		foreach($listOfFootprint as $footprint){
			$footprint["rssi_h"] = floatval($footprint["rssi_h"])/floatval($footprint["score"]);
			//$footprint["rssi_s"] = floatval($footprint["rssi_s"])/floatval($footprint["score"]);
		}		
		return $listOfFootprint;
	}

	//function update()
	function update($query){
		global $prefix;
		//Curl Post Request
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://localhost:3030/tdb/update");
        curl_setopt($ch, CURLOPT_POST, 1);
		// echo "<hr>";
		// echo (http_build_query(array("update"=>$prefix.$query)));
		// echo "<hr>";
		// echo urlencode("update=".$prefix.$query);
		// echo "<hr>";
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query(array("update"=>$prefix.$query)));
		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		curl_close ($ch);
		return $server_output;
	}

?>