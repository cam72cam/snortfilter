<?php


include_once "../lib/db.php";

db::connect();

$query = "select sig_name, cnt from (select *, count(cid) as cnt from event WHERE event.signature<>1 AND event.signature<>2 AND event.signature<>3 GROUP BY signature) event JOIN signature ON event.signature=signature.sig_id;";

$res = db::query($query);

$data = array();

for($sig = $res->fetch_assoc(); $sig != NULL; $sig = $res->fetch_assoc()) {
	$data[] = array($sig["sig_name"], intval($sig["cnt"]));
}

echo json_encode($data);

?>
