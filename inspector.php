<?php

include_once "lib/db.php";
include_once "page.php";
include_once "formatter.php";

page_header(PAGE::PACKET_INSPECTOR, false);

db::connect();

$packet = packet::get($_GET['cid'], $_GET['sid']);

function pkt_td($data) {
	echo "<td>".$data."</td>";
}

$ip = $packet->get_ip();
?>

<table style="width:100%; text-align:left">
	<tr>
		<th colspan="100" style="text-align:center">IP Header</th>
	</tr>
	<tr>
			<th>Source</th>
			<th>Destination</th>
			<th>Version</td>
			<th>Header Len</th>
			<th>TOS</th>
			<th>Length</th>
			<th>Id</th>
			<th>Flags</th>
			<th>Offset</th>
			<th>TTL</th>
			<th>Protocall</th>
			<th>Checksum</th>
		</tr>
		<tr>
			<?php 
			pkt_td($ip->ip_src);
			pkt_td($ip->ip_dst);
			pkt_td($ip->ip_ver);
			pkt_td($ip->ip_hlen);
			pkt_td($ip->ip_tos);
			pkt_td($ip->ip_len);
			pkt_td($ip->ip_id);
			pkt_td($ip->ip_flags);
			pkt_td($ip->ip_off);
			pkt_td($ip->ip_ttl);
			pkt_td(proto2str($ip->ip_proto));
			pkt_td($ip->ip_csum);
			?>
		</tr>
	</tr>
	<tr>
		<th colspan="100" style="text-align:center">Application Header (<?php echo proto2str($ip->ip_proto); ?>)</th>
	</tr>
	<?php
	switch($ip->ip_proto) {
		case IPPROTO::TCP:
		?>
			<tr>
				<th>Source Port</th>
				<th>Dest Port</th>
				<th>Sequence</th>
				<th>ACK</th>
				<th>Offset</th>
				<th>Reset</th>
				<th>Flags</th>
				<th>Window</th>
				<th>Checksum</th>
				<th>URP</th>
			</tr>
			<tr>
				<?php
				$tcp = $packet->get_tcp();
				pkt_td($tcp->tcp_sport);
				pkt_td($tcp->tcp_dport);
				pkt_td($tcp->tcp_seq);
				pkt_td($tcp->tcp_ack);
				pkt_td($tcp->tcp_off);
				pkt_td($tcp->tcp_res);
				pkt_td($tcp->tcp_flags);
				pkt_td($tcp->tcp_win);
				pkt_td($tcp->tcp_csum);
				pkt_td($tcp->tcp_urp);
				?>
			</tr>
		<?php
		break;
		case IPPROTO::UDP:
		?>
			<tr>
				<th>Source Port</th>
				<th>Dest Port</th>
				<th>Length</th>
				<th>Checksum</th>
			</tr>
			<tr>
				<?php
				$udp = $packet->get_udp();
				pkt_td($udp->udp_sport);
				pkt_td($udp->udp_dport);
				pkt_td($udp->udp_len);
				pkt_td($udp->udp_csum);
				?>
			</tr>
		<?php
		break;
		case IPPROTO::ICMP:
		?>
			<tr>
				<th>Type</th>
				<th>Code</th>
				<th>Checksum</th>
				<th>Id</th>
				<th>Sequence</th>
			</tr>
			<tr>
				<?php
				$icmp = $packet->get_icmp();
				pkt_td($icmp->icmp_type);
				pkt_td($icmp->icmp_code);
				pkt_td($icmp->icmp_csum);
				pkt_td($icmp->icmp_id);
				pkt_td($icmp->icmp_seq);
				?>
			</tr>
		<?php
		break;
		default:
			pkt_td("No Data Available");
		break;
	}
	?>
	<tr>
		<th colspan="100" style="text-align:center">Raw Data</th>
	</tr>
	<tr>
		<td colspan="100" ><?php echo $packet->get_data()->data_payload; ?></td>
	</tr>
</table>

<?php

page_footer();
?>
