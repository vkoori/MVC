<?php 

/**
 * 
 */
class I18 {
	private static $lang = NULL;
	private static $file = NULL;

	public function setLocale($lang) {
		self::$lang = $lang;
		
		self::$file = dirname(__DIR__, 1) . '/kernel/langs/' . $lang . '.json';
		self::$file = file_get_contents( self::$file );
		self::$file = json_decode(self::$file);
	}

	public function dictionary($key, $replace) {
		if (isset(self::$file->{$key})) {
			$message = self::$file->{$key};
			if (is_array($replace)) {
				foreach ($replace as $k => $v) {
					$message = str_replace(":$k", $v, $message);
				}
			}
		} else {
			$message = $key;
		}
		return $message;
	}
}

function __messages($key, $replace=null) {
	return (new I18)->dictionary($key, $replace);
}

function _setlocale($lang) {
	(new I18)->setLocale($lang);
}