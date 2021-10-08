<?php 
namespace model;

/**
 * All database connection and other variables
 */
class baseDB {

	protected static $host = "";
	protected static $user = "";
	protected static $pass = "";
	protected static $db = "";

	/**
	 * get variables from .env and set variables
	 * @return true
	 */
	protected static function init() {
		self::$host = _env("host", "database");
		self::$user = _env("user", "database");
		self::$pass = _env("pass", "database");
		self::$db = _env("db", "database");
	}

}