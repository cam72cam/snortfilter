<?php


function event_table($events) {
	static $table_id = 0;
	$counter = 1;
	?>
	<table id="event_table_<?php echo $table_id?>" style="width:100%;" cellpadding="0" cellspacing="0" border="0">
		<thead>
		<tr>
			<th></th>
			<th>Signature</th>
			<th>Timestamp</th>
			<th>Source IP</th>
		</tr>
		</thead>
		<?php
		foreach($events as &$event) {
			$ip = $event->get_packet()->get_ip();
			?>
		<tr>
			<td><?php echo $counter; ?></td>
			<td><?php echo $event->get_signature()->sig_name; ?></td>
			<td><?php echo $event->timestamp; ?></td>
			<td><?php echo $ip->ip_src; ?></td>
			
		</tr>
			<?php
			$counter++;
		}
		
		?>
	</table>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#event_table_<?php echo $table_id?>").dataTable({ bJQueryUI : true});
		});
	</script>
	<?php	
	$table_id++;
}

function test_table() {
	static $table_id = 0;
	?>
	<table id="test_table_<?php echo $table_id?>" style="width:100%;" cellpadding="0" cellspacing="0" border="0">
		<thead>
		<tr>
			<th>Signature</th>
			<th>Timestamp</th>
			<th>Source IP</th>
			<th>IP Protocall</th>
		</tr>
		</thead>
	</table>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#test_table_<?php echo $table_id?>").dataTable({ bJQueryUI : true, bProcessing: true, bServerSide: true, sAjaxSource: 'data_sources/test.php'});
		});
	</script>
	<?php	
	$table_id++;
}
?>
