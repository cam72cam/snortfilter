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
	public $sig_id;
	public $start;
	public $end;
	public $proto;
	public $orderby;
	public $start_limit;
	public $num_limit;
	public $select;

	public function __construct(){
		$this->select = "event.*";
	}

	public function build_count() {
		$query = "SELECT count(*) FROM (%s LIMIT 10000) as l";
		$inner = $this->build_header();
		$this->parts($inner, false);
		$query = sprintf($query, $inner);
		return $query;
	}

	private function build_header() {
		if(isset($this->orderby)) {
			$table = explode(".", $this->orderby);
			$table = $table[0];
			switch($table) {
				case "iphdr":
					return "SELECT event.sid, event.cid, sig_name, timestamp, ip_src, ip_proto FROM iphdr LEFT JOIN event ON event.sid = iphdr.sid AND event.cid = iphdr.cid LEFT JOIN signature ON event.signature = signature.sig_id";
				case "signature":
					return "SELECT event.sid, event.cid, sig_name, timestamp, ip_src, ip_proto FROM signature LEFT JOIN event ON event.signature = signature.sig_id LEFT JOIN iphdr ON event.sid = iphdr.sid AND event.cid = iphdr.cid";
			}
		}
		return "SELECT event.sid, event.cid, sig_name, timestamp, ip_src, ip_proto FROM event LEFT JOIN iphdr ON event.sid = iphdr.sid AND event.cid = iphdr.cid LEFT JOIN signature  ON event.signature = signature.sig_id";
	}

	public function build() {
//		$query = "SELECT $this->select FROM event LEFT JOIN iphdr ON event.sid = iphdr.sid AND event.cid = iphdr.cid RIGHT JOIN signature ON event.signature = signature.sig_id ";
		$query = $this->build_header();
		$this->parts($query, true);
//		echo $query;
		return $query;
	}
	private function parts(&$query, $order) {
		$query .= " WHERE true"; // EMPTY WHERE
		if(isset($this->ip_src)) {
			$query .= sprintf(" AND ip_src='%d'", ip2long($this->ip_src));
		}
		if(isset($this->proto)) {
			$query .= sprintf(" AND ip_proto='%d'", $this->proto);
		}
		$foo = NULL;
		if(isset($this->start)) {
			$foo =  sprintf(" AND timestamp>='%s'", $this->start);
		}
		if(isset($this->end)) {
			$foo .= sprintf(" AND timestamp<='%s'", $this->end);
		}
		if($foo != NULL) {
			$query = str_replace(" event ", sprintf(" (SELECT * FROM event WHERE true %s) event ", $foo), $query);
		}
		if(isset($this->sig_id)) {
			$query .= sprintf(" AND sig_id=%d", $this->sig_id);
		}
		if(isset($this->signature)) {
			switch($this->sig_opt) {
			case "contains":
				$query .= " AND `sig_name` LIKE '%". $this->signature ."%'";
				break;
			case "begins":
				$query .= " AND `sig_name` LIKE '". $this->signature ."%'";
				break;
			case "ends":
				$query .= " AND `sig_name` LIKE '%". $this->signature ."'";
				break;
			default:
				$query .= " AND `sig_name` LIKE '". $this->signature ."'";
			}
		}
		
		if(isset($this->orderby) && $order) {
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
