<?php

class event {
	private $sid;
	private $cid;
	private $packet;
	private $signature;
	private $signature_obj;
	public  $timestamp;
	
	public function get_packet() {
		if(!isset($this->packet)) {
			$this->packet = packet::get($this->cid, $this->sid);
		}
		return $this->packet;
	}
	
	public function get_signature() {
		if(!isset($this->signature_obj)) {
			$this->signature_obj = signature::get($this->signature);
		}
		return $this->signature_obj;
	}

	public static function get($cid, $sid) {
		$result = db::query("SELECT * FROM event WHERE cid=%d AND sid=%d", $cid, $sid);
		return $result->fetch_object('event');
	}

	public static function query($query) {
		$result = db::query($query->build());
		$events = new ArrayObject();
		for($event = $result->fetch_object('event'); $event != false; $event = $result->fetch_object('event')) {
			$events->append($event);
		}
		return $events;
	}
}
