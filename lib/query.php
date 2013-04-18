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
	private $orderby;
	public $start_limit;
	public $num_limit;
	public $select;

	public function __construct(){
		$this->select = "event.*";
		$this->orderby = array();
	}

	public function add_order($order) {
		$this->orderby[] = $order;
	}

	public function build_count() {
		$query = "SELECT count(*) from event ";
		$query = "SELECT count(event.sid) FROM event JOIN iphdr ON event.sid = iphdr.sid AND event.cid = iphdr.cid JOIN signature ON event.signature = signature.sig_id ";
		$query.= $this->parts(false);
		return $query;
	}

	public function build() {
		$query = "SELECT $this->select FROM event JOIN iphdr ON event.sid = iphdr.sid AND event.cid = iphdr.cid JOIN signature ON event.signature = signature.sig_id ";
		$query.= $this->parts(true);
		return $query;
	}
	private function parts($order) {
		$query = " WHERE true"; // EMPTY WHERE
		if(isset($this->ip_src)) {
			$query .= sprintf(" AND ip_src='%d'", ip2long($this->ip_src));
		}
		if(isset($this->proto)) {
			$query .= sprintf(" AND ip_proto='%d'", $this->proto);
		}
		if(isset($this->start)) {
			$query .= sprintf(" AND timestamp>='%s'", $this->start);
		}
		if(isset($this->end)) {
			$query .= sprintf(" AND timestamp<='%s'", $this->end);
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
		
		if(count($this->orderby) > 0 && $order) {
			$query .= " ORDER BY ";
			foreach($this->orderby as &$order) {
				$query .= sprintf(" %s, ", $order);
			}
			$query = substr_replace( $query, "", -2 );
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
