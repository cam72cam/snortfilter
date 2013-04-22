<?php

function event_table($params) {
	static $table_id = 0;
	$table_name = "event_table_".$table_id;
	
	$pre = "?";
	$args = "";
	foreach($params as $key => $val) {
		$args.=$pre.$key."=".$val;
		$pre = "&";
	}
	
	?>
<div style="margin-left:20px">
	<table id="<?php echo $table_name?>" cellpadding="0" cellspacing="0" border="0">
		<thead>
		<tr>
			<th>SID</th>
			<th>CID</th>
			<th>Signature</th>
			<th>Timestamp</th>
			<th>Source IP</th>
			<th>IP Protocall</th>
		</tr>
		</thead>
	</table>
</div>
	<script type="text/javascript">
		var table;
		$(document).ready(function() {

			function row_refresh() {
				var nodes = table.fnGetNodes();
				var data = table.fnGetData();
				for(var i in data) {
					nodes[i].data = data[i];
					var attacker = $(nodes[i].children[2]);
					attacker.html("<a style='color:#000' href='/attacker?ip=" + attacker.html() + "'>" + attacker.html() + "</a>");
					$(nodes[i]).dblclick(function () 
					{
						show_dialog('inspector.php?sid=' + this.data[0] + '&cid=' + this.data[1]);
					});
				}
			}
			var id = "#<?php echo $table_name?>";
			table = $(id).dataTable({ 
				bJQueryUI : true, 
				bProcessing: true,
				bServerSide: true,
				iDisplayLength: 25,
				sAjaxSource: 'data_sources/event.php<?php echo $args; ?>',
				aoColumns: [ { "bVisible": false, bSortable: false}, { "bVisible": false }, null, null,null,null ],
				fnDrawCallback: row_refresh,
			});
			table.css("width", "100%");
		});
		function update_table_source(tname,  args) {
			table.dataTableSettings[0].sAjaxSource = "data_sources/event.php" + args;
			table._fnAjaxUpdate();
		}
	</script>
	<?php	
	$table_id++;
	return $table_name;
}


?>
