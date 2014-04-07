<?php

include_once "lib/db.php";
include_once "lib/hosts.php";
include_once "page.php";
include_once "formatter.php";

page_header(PAGE::HOSTS, true);

db::connect();

if(!isset($_GET["ip"])) {

?>
<div style="margin-left:20px">
	<p style="font-size:20px">List of hosts:</p>

	<table id="host_table">
		<thead>
			<tr>
				<th>Address</th>
				<th>Type</th>
				<th>OS</th>
				<th>Attacked By</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$hosts = host::get();
			foreach($hosts as &$host) {
			?>
			<tr>
				<td><?php echo $host->ipaddress; ?></td>
				<td><?php echo $host->honeypot; ?></td>
				<td><?php echo $host->os; ?></td>
				<td><?php echo $host->num_attacks(); ?></td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	var table;
	$(document).ready(function() {
		var id = "#host_table";
		table = $(id).dataTable({ 
			bJQueryUI : true, 
			iDisplayLength: 25,
			fnDrawCallback: row_refresh,
		});
		function row_refresh() {
			if(table !== undefined) {
				var nodes = table.fnGetNodes();
				var data = table.fnGetData();
				for(var i in data) {
					nodes[i].data = data[i];
					$(nodes[i]).dblclick(function () 
					{
						document.location.href = "hosts.php?ip=" + this.data[0];
					});
				}
			}
		}
		row_refresh();

	});
</script>


<?php
} else {
	$ip = $_GET["ip"];
	?>

	<div style="margin-left:16px">
		<p style="font-size:20px"><?php echo $ip; ?></p>
	</div>

	<?php
	$args = array();
	$args["destIp"] = $ip;
	$table = event_table($args);


}




page_footer();
?>
