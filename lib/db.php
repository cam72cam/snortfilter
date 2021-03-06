<?php

set_include_path(get_include_path() . PATH_SEPARATOR . '../.');
include_once "lib/packet.php";
include_once "lib/signature.php";
include_once "lib/event.php";
include_once "lib/query.php";
include_once "lib/hosts.php";

db::$instance = new db();

class db {
	private static $host 		= "localhost";
	private static $user 		= "root";
	private static $password	= "honeypass";
	private static $name 		= "snort";
	public static $instance;

	public $conn;

	public function connect() {
		self::$instance->conn = new mysqli(self::$host, self::$user, self::$password, self::$name);
	}

	public static function escape($str) {
		return self::$instance->conn->real_escape_string($str);
	}

	public static function query($query) {
		$args = func_get_args();
		if(count($args)>1) {
			array_shift($args); //remove first element, query
			$query = vsprintf($query, $args);
		}
		return self::$instance->conn->query($query);
	}
}

?>
