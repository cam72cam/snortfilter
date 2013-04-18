<?php

function event_table($params) {
	static $table_id = 0;
	
	$pre = "?";
	$args = "";
	foreach($params as $key => $val) {
		$args.=$pre.$key."=".$val;
		$pre = "&";
	}
	echo $args;
	
	?>
	<table id="test_table_<?php echo $table_id?>" style="width:100%;" cellpadding="0" cellspacing="0" border="0">
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
			var id = "#test_table_<?php echo $table_id?>";
			table = $(id).dataTable({ 
				bJQueryUI : true, 
				bProcessing: true,
				bServerSide: true,
				sAjaxSource: 'data_sources/event.php<?php echo $args; ?>',
				aoColumns: [ { "bVisible": false }, { "bVisible": false }, null, null,null,null ],
				fnDrawCallback: row_refresh,
			});
		});
	</script>
	<?php	
	$table_id++;
}
?>
