<?php
include_once "lib/db.php";
include_once "ip2geolocation.php";

db::connect();

//$date = date("Y-m-d", strtotime("Today"));
$date = "2013-04-24";
$date_next = "2013-04-25";
$query = sprintf("select ip_src, count(*) as cnt from iphdr join event on event.sid = iphdr.sid and event.cid = iphdr.cid where timestamp>='%s' AND timestamp<'%s' group by ip_src;", $date, $date_next);
$res = db::query($query);
$array = array();
for($ip = $res->fetch_assoc(); $ip != NULL; $ip = $res->fetch_assoc()) {
	$data = Lookup(long2ip($ip['ip_src']));
	if($data->lat != NULL) {
		$array[] = ($data->lat);
		$array[] = ($data->lng);
		$array[] = ($ip['cnt']);
		echo long2ip($ip['ip_src']);
		echo "\r\n";
	}
}
$file = fopen(sprintf("geo_data/%s.json", $date), "w+");
fwrite($file, sprintf("['%s', ", $date));
fwrite($file, json_encode($array));
fwrite($file, "]");
fclose($file);

?>
