<?php
	class signature {
		private $sig_id;
		public  $sig_name;
		public  $sig_class_id;
		public  $sig_priority;
		public  $sig_rev;
		public  $sig_sid;
		
		public static function get($sig_id) {
			$result = db::query("SELECT * FROM signature WHERE sig_id=%d", $sig_id);
			return $result->fetch_object('signature');
		}
	}
?>
