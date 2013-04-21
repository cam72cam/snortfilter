<?php

include_once "lib/db.php";
include_once "lib/hosts.php";
include_once "page.php";
include_once "formatter.php";

page_header(PAGE::HOSTS, true);

db::connect();

?>
List of hosts:

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
<script type="text/javascript">
	var table;
	$(document).ready(function() {

		function row_refresh() {
			var nodes = table.fnGetNodes();
			var data = table.fnGetData();
			for(var i in data) {
				nodes[i].data = data[i];
				$(nodes[i]).dblclick(function () 
				{
					show_dialog('inspector.php?sid=' + this.data[0] + '&cid=' + this.data[1]);
				});
			}
		}
		var id = "#host_table";
		table = $(id).dataTable({ 
			bJQueryUI : true, 
			iDisplayLength: 25,
			fnDrawCallback: row_refresh,
		});
	});
</script>


<?php
page_footer();
?>
