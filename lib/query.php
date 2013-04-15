<?php

class ORDERBY {
	const IP_SRC_ASC = "iphdr.ip_src ASC";
	const IP_SRC_DESC = "iphdr.ip_src DESC";
	const SIG_ASC = "signature.sig_name ASC";
	const SIG_DESC = "signature.sig_name DESC";
	const DATE_ASC = "event.timestamp ASC";
	const DATE_DESC = "event.timestamp DESC";
	const PROTO_ASC = "iphdr.ip_proto ASC";
	const PROTO_DESC = "iphdr.ip_proto DESC";
}

class query {
	public $ip_src;
	public $signature;
	public $start;
	public $end;
	public $proto;
	public $orderby;
	public $start_limit;
	public $num_limit;

	function build() {
		$query = "SELECT event.* FROM event,signature,iphdr WHERE (event.sid, event.cid, event.signature) = (iphdr.sid, iphdr.cid, signature.sig_id) ";
		
		if(isset($this->ip_src)) {
			$query .= sprintf(" AND ip.src='%d'", ip2long($this->ip_src));
		}
		if(isset($this->proto)) {
			$query .= sprintf(" AND ip.ip_proto='%d'", $this->proto);
		}
		if(isset($this->signature)) {
			$query .= sprintf(" AND signature.sig_name LIKE %%d%", $this->signature);
		}
		
		if(isset($this->orderby)) {
			$query .= sprintf(" ORDER BY %s", $this->orderby);
		}
		
		if(isset($this->start_limit) && isset($this->num_limit)) {
			$query .= sprintf(" LIMIT %d,%d", $this->start_limit, $this->num_limit);
		}
		if(!isset($this->start_limit) && isset($this->num_limit)) {
			$query .= sprintf(" LIMIT %d", $this->num_limit);
		}

		return $query;
	}
}

?>
