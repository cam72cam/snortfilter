<?php

class host {
	public $ipaddress;
	public $type;
	public $os;

	public function __construct() {
		$this->ipaddress = long2ip($this->ipaddress);
		$this->os = empty($this->os) ? "Unknown" : $this->os;
	}

	public function num_attacks() {
		$query = sprintf("SELECT count(*) FROM iphdr WHERE ip_dst='%s'", ip2long($this->ipaddress));
		$res = db::query($query);
		$res = $res->fetch_array();
		return $res[0];
	}

	static function get() {
		$query = "SELECT * FROM host_ip_map";
		$res = db::query($query);
		$result = new ArrayObject();
		for($host = $res->fetch_object('host'); $host != NULL; $host = $res->fetch_object('host')) {
			$result->append($host);
		}
		return $result;
	}
}

?>
