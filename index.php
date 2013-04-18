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

event_table(array());


page_footer();
?>
