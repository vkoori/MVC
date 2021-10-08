<?php 

/**
 * 
 */
class Config {
	private static $conf = NULL;
	
	public function loader($item, $type) {
		if (is_null(self::$conf)) 
			self::$conf = require dirname(__DIR__, 1)."/config.php";
		return self::$conf[$type][$item];
	}
}

function config($item, $type="app") {
	return (new Config)->loader($item, $type);
}

function cors($item) {
	return (new Config)->loader($item, "cors");
}
