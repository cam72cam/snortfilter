<?php


include_once "../lib/db.php";

$query = 'select DATE_FORMAT(event.timestamp, "%Y-%c-%d")as ymd , count(*) as cnt from event group by ymd;';

db::connect();

$res = db::query($query);

$dates = array();
$values = array();

for($event = $res->fetch_assoc(); $event != NULL; $event = $res->fetch_assoc()) {
	$dates[] = $event["ymd"];
	$values[] = intval($event["cnt"]);
}

echo json_encode(array($dates, $values));
?>
