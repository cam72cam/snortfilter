<?php
function QueryWhoisServer($whoisserver, $domain) {
	$port = 43;
	$timeout = 10;
	$fp = @fsockopen($whoisserver, $port, $errno, $errstr, $timeout) or die("Socket Error " . $errno . " - " . $errstr);
	fputs($fp, $domain . "\r\n");
	$out = "";
	while(!feof($fp)){
		$out .= fgets($fp);
	}
	fclose($fp);
	$res = "";
	if((strpos(strtolower($out), "error") === FALSE) && (strpos(strtolower($out), "not allocated") === FALSE)) {
		$rows = explode("\n", $out);
		foreach($rows as $row) {
			$row = trim($row);
			if(($row != '') && ($row{0} != '#') && ($row{0} != '%')) {
				$res .= $row."\n";
			}
		}
	}
	return $res;
}

function LookupIP($ip) {
/*	$whoisservers = array(
		"whois.arin.net", // North America only
	);
	$results = array();
	foreach($whoisservers as $whoisserver) {
		$result = QueryWhoisServer($whoisserver, $ip);
		if($result && !in_array($result, $results)) {
			$results[$whoisserver]= $result;
		}
	}
	$res = "RESULTS FOUND: " . count($results);
	foreach($results as $whoisserver=>$result) {
		$res .= "\n\n-------------\nLookup results for " . $ip . " from " . $whoisserver . " server:\n\n" . $result;
	}*/
	$res = array();
	exec(sprintf("/usr/bin/whois %s", $ip), $res);
	return $res;
}

function LongLat($city, $state, $country) {
	$address = urlencode($city.',+'.$state.',+'.$country);
	$geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false');
	$output = json_decode($geocode);
	return $output->results[0]->geometry->location;
}

function LookUp($ip) {
	$lookup =  LookupIP($ip);
	$address = NULL;
	$city = NULL;
	$state = NULL;
	$country = NULL;
	foreach($lookup as &$entry) {
		if(strstr($entry, 'address:')) {
			$address = trim(str_replace('address:', '', $entry));
		}
		if(strstr($entry, 'city:')) {
			$city = trim(str_replace('city:', '', $entry));
		}
		if(strstr($entry, 'stateProv:')) {
			$state = trim(str_replace('stateProv:', '', $entry));
		}
		if(strstr($entry, 'country:')) {
			$country = trim(str_replace('country:', '', $entry));
		}

		if(strstr($entry, 'Address:')) {
			$address = trim(str_replace('Address:', '', $entry));
		}
		if(strstr($entry, 'City:')) {
			$city = trim(str_replace('City:', '', $entry));
		}
		if(strstr($entry, 'StateProv:')) {
			$state = trim(str_replace('StateProv:', '', $entry));
		}
		if(strstr($entry, 'Country:')) {
			$country = trim(str_replace('Country:', '', $entry));
		}
	}
	$address="";

	$spot  = LongLat($address . "+" .$city, $state, $country);
	return $spot;
}

/*$spot =  LookUp("201.201.150.134");
#$spot =  LookUp("128.153.145.1");
echo "Lattitude: ".$spot->lat. "\n";
echo "Longitude: ".$spot->lng. "\n";*/
