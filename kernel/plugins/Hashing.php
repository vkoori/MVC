<?php 
namespace kernel\plugins;

/**
 * 
 */
class Hashing {
	private static $method = null;
	
	function __construct() {
		self::$method = config("HASH");
	}

	public function hash($string) {
		if (self::$method == "argon2i") {
			return password_hash($string, PASSWORD_ARGON2I, ['memory_cost' => 1024, 'time_cost' => 2, 'threads' => 2]);
		} else {
			return $string;
		}
	}

	public function verify_hash($sting, $hash) {
		if (self::$method == "argon2i") {
			if(password_verify($sting, $hash))
				return true;
			return false;
		} else {
			if($sting == $hash)
				return true;
			return false;
		}
	}
}