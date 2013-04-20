<?php
include_once "lib/db.php";
include_once "page.php";
include_once "formatter.php";

page_header(PAGE::SIGNATURES, true);

db::connect();

$query = "SELECT sig_id, sig_name FROM signature";
$res = db::query($query);

?>


<select id="signatures" style="margin-left:5%">
	<option value="*">All</option>
	<?php
	for($sig = mysqli_fetch_assoc($res); $sig != NULL; $sig = mysqli_fetch_assoc($res)) {
		echo sprintf("\t<option value='%s'>%s</option>", $sig['sig_id'], $sig["sig_name"]);
	}

	?>
</select>
<?php

$args = array();
$table = event_table($args);
?>


<script type="text/javascript">
	$(document).ready(function() {
		var signatures = $("#signatures");
		$($(".fg-toolbar")[0]).append(signatures);
		signatures.change(function() {
			var val = signatures.val();
			var str = val == "*" ? "" : "?sigId=" + val;
			update_table_source(<?php echo $table; ?>, str);
		});
	});
</script>


<?php
page_footer();
?>
