<?php
include_once "lib/db.php";
include_once "ip2geolocation.php";

db::connect();

$query = "select ip_src, count(*) as cnt from iphdr group by ip_src;";
$res = db::query($query);

$array = array();
for($ip = $res->fetch_assoc(); $ip != NULL; $ip = $res->fetch_assoc()) {
	$data = Lookup(long2ip($ip['ip_src']));
	if($data->lat != NULL) {
		$array[] = ($data->lat);
		$array[] = ($data->lng);
		$array[] = ($ip['cnt']);
	}
}

echo json_encode($array);
?>
