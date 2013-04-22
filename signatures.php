<?php
include_once "lib/db.php";
include_once "page.php";
include_once "formatter.php";

page_header(PAGE::SIGNATURES, true);

db::connect();

$query = "SELECT sig_id, sig_name FROM signature";
$res = db::query($query);

?>


<select id="signatures" style="float:right">
	<option value="*">All</option>
	<?php
	for($sig = mysqli_fetch_assoc($res); $sig != NULL; $sig = mysqli_fetch_assoc($res)) {
		echo sprintf("\t<option value='%s'>%s</option>", $sig['sig_id'], $sig["sig_name"]);
	}

	?>
</select>
<div style="margin-top:10px">
<?php

$args = array();
$table = event_table($args);
?>
</div>


<script type="text/javascript">
	$(document).ready(function() {
		$("#<?php echo $table ?>_filter").hide();
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
