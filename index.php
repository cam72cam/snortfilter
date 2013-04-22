<?php
include_once "lib/db.php";
include_once "page.php";
include_once "formatter.php";

page_header(PAGE::HOME, true);

db::connect();

?>
<div style="background-color:#555; margin:10px; margin-left:20px; border-radius:8px; padding:10px; width:25%">
Welcome to our page, here is some info about us! <br>
Latest events: 
</div>
<?

event_table(array());


page_footer();
?>
