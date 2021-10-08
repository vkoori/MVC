<?php 

/**
 * 
 */
class Env {
	private static $env = NULL;
	
	function __construct() {
		if (is_null(self::$env)) 
			self::$env = parse_ini_file(dirname(__DIR__, 1)."/.env",true,INI_SCANNER_RAW);
	}

	public function loader($item, $type) {
		if (isset(self::$env[$type][$item]))
			$value = self::$env[$type][$item];
		else
			$value = '';
		return $value;
	}

	public function setter($item, $value, $type="controller") {
		self::$env[$type][$item] = $value;
	}
}

function _env($item=null, $type="controller") {
	if (!is_null($item)) {
		$env = (new Env)->loader($item, $type);
		return $env;
	}
	return (new Env);
}