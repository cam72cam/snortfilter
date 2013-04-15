<?php

include_once "query.php";

$q = new query();

echo  $q->build() . "\n";
$q->orderby = ORDERBY::IP_SRC_ASC;
echo  $q->build() . "\n";
$q->ip_src = "128.153.145.1";
echo  $q->build() . "\n";
echo  $q->build() . "\n";
echo  $q->build() . "\n";
echo  $q->build() . "\n";


?>
