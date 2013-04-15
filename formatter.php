<?php


function event_table($events) {
	$counter = 1;
	?>
	<table class="event_table">
		<tr>
			<th></th>
			<th>Signature</th>
			<th>Timestamp</th>
			<th>Source IP</th>
		</tr>
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
	<?php	
}


?>
