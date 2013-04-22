<?php
include_once "lib/db.php";
include_once "page.php";
include_once "formatter.php";

page_header(PAGE::ATTACKER, true);

db::connect();

$query = "SELECT ip_src FROM iphdr GROUP BY ip_src";
$res = db::query($query);

?>
<div style="background-color:#555; margin:10px; margin-left:20px; border-radius:8px; padding:10px; width:25%">
Attacks from 
<select id="ip_src">
	<?php
	for($ip = $res->fetch_array(); $ip != NULL; $ip = $res->fetch_array()) {
		$real = long2ip($ip[0]);
		echo sprintf("<option value='%s' %s>%s</option>", $real, $_GET['ip'] == $real ? "selected" : "", $real);
	}
	//echo $_GET['ip']; 
	?>
</select>
</div>
<?

$args = array();
$args['sourceIp'] = $_GET['ip'];
$table = event_table($args);
?>

<script type="text/javascript">
	$(document).ready(function() {
		var select = $("#ip_src");
		select.change(function() {
			var val = select.val();
			var str = "?sourceIp=" + val;
			update_table_source(<?php echo $table; ?>, str);
		});
	});

</script>

<?
page_footer();
?>
