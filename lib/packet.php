<?php

class IPPROTO {
	const ICMP=1;
	const TCP=6;
	const UDP=17;
	const EIGRP=88;
}

function proto2str($id) {
	switch ($id) {
		case IPPROTO::ICMP  : return "ICMP";
		case IPPROTO::TCP   : return "TCP";
		case IPPROTO::UDP   : return "UDP";
		case IPPROTO::EIGRP : return "EIGRP";
	}
	return "Unknown: $id";
}

class packet {
	private $cid;
	private $sid;
	private $ip_header;
	private $tcp_header;
	private $udp_header;
	private $icmp_header;
	private $data;
	
	private function __construct($cid, $sid) {
		$this->cid = $cid;
		$this->sid = $sid;
	}
	
	public function get_ip() {
		if(!isset($this->ip_header)) {
			$this->ip_header = ip_header::get($this->cid, $this->sid);
		}
		return $this->ip_header;
	}
	
	public function get_tcp() {
		if($this->get_ip()->ip_proto == IPPROTO::TCP) {
			if(!isset($this->tcp_header)) {
				$this->tcp_header = tcp_header::get($this->cid,$this->sid);
			}
			return $this->tcp_header;
		}
		throw new Exception("Can not read TCP information about a '".$this->get_ip()->ip_proto."' packet");
	}
	
	public function get_udp() {
		if($this->get_ip()->ip_proto == IPPROTO::UDP) {
			if(!isset($this->udp_header)) {
				$this->udp_header = udp_header::get($this->cid,$this->sid);
			}
			return $this->udp_header;
		}
		throw new Exception("Can not read UDP information about a '".$this->get_ip()->ip_proto."' packet");
	}
	
	public function get_icmp() {
		if($this->get_ip()->ip_proto == IPPROTO::ICMP) {
			if(!isset($this->icmp_header)) {
				$this->icmp_header = icmp_header::get($this->cid,$this->sid);
			}
			return $this->icmp_header;
		}
		throw new Exception("Can not read ICMP information about a '".$this->get_ip()->ip_proto."' packet");
	}
	
	public function get_data() {
		if(!isset($this->data)) {
			$this->data = data::get($this->cid,$this->sid);
		}
		return $this->data;
	}
	
	public static function get($cid, $sid) {
		return new packet($cid, $sid);
	}
}
class ip_header {
	private $cid;
	private $sid;

	public $ip_src;
	public $ip_dst;
	public $ip_ver;
	public $ip_hlen;
	public $ip_tos;
	public $ip_len;
	public $ip_id;
	public $ip_flags;
	public $ip_off;
	public $ip_ttl;
	public $ip_proto;
	public $ip_csum;
	
	public function __construct() {
		$this->ip_src = long2ip($this->ip_src);
		$this->ip_dst = long2ip($this->ip_dst);
	}
	
	public static function get($cid, $sid) {
		$result = db::query("SELECT * FROM iphdr WHERE cid=%d AND sid=%d", $cid, $sid);
		return $result->fetch_object('ip_header');
	}
}

class tcp_header {
	private $cid;
	private $sid;
	
	public $tcp_sport;
	public $tcp_dport;
	public $tcp_seq;
	public $tcp_ack;
	public $tcp_off;
	public $tcp_res;
	public $tcp_flags;
	public $tcp_win;
	public $tcp_csum;
	public $tcp_urp;
	
	public static function get($cid, $sid) {
		$result = db::query("SELECT * FROM tcphdr WHERE cid=%d AND sid=%d", $cid, $sid);
		return $result->fetch_object('tcp_header');
	}
}

class udp_header {
	private $cid;
	private $sid;
	
	public $udp_sport;
	public $udp_dport;
	public $udp_len;
	public $udp_csum;
	
	public static function get($cid, $sid) {
		$result = db::query("SELECT * FROM udphdr WHERE cid=%d AND sid=%d", $cid, $sid);
		return $result->fetch_object('udp_header');
	}
}

class icmp_header {
	private $cid;
	private $sid;
	
	public $icmp_type;
	public $icmp_code;
	public $icmp_csum;
	public $icmp_id;
	public $icmp_seq;
	
	public static function get($cid, $sid) {
		$result = db::query("SELECT * FROM icmphdr WHERE cid=%d AND sid=%d", $cid, $sid);
		return $result->fetch_object('icmp_header');
	}
}

class data {
	private $cid;
	private $sid;
	
	public $data_payload;
	
	public static function get($cid, $sid) {
		$result = db::query("SELECT * FROM data WHERE cid=%d AND sid=%d", $cid, $sid);
		return $result->fetch_object('data');
	}
}


?>

