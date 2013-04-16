<?php

include_once "lib/db.php";
include_once "page.php";
include_once "formatter.php";

page_header(PAGE::HOME, true);

db::connect();

?>
Welcome to our page, here is some info about us! <br>
Latest events: 
<?

$query = new query();
$query->num_limit = 20;
$query->orderby = ORDERBY::IP_SRC_ASC;

$events = event::query($query);

event_table($events);


page_footer();
?>
