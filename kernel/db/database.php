<?php 
namespace model;

/**
 * All basic functions we need
 */
class database extends baseDB {

	private static $sdb = null;

	/**
	 * This method is created using `Singleton Pattern Design`
	 * @return db connection
	 */
	private function connect(){
		if (is_null(self::$sdb)) {
			baseDB::init();

			self::$sdb = new \mysqli(self::$host,self::$user,self::$pass,self::$db);
			
			if (self::$sdb->connect_error)
				die("Connection failed: " . self::$sdb->connect_error);
		}


		return self::$sdb;
	}

	/**
	 * This method run queries
	 * @return db records or insertid or boolean (update, delete)
	 */
	public function query($sql, $bash=false){
		$conn = $this->connect();
		$result = $conn->query($sql);
		$q_type = strtolower(substr(trim($sql), 0, 6));
		if ($q_type === "select") {
			$result = $result->fetch_all(MYSQLI_ASSOC);
		} elseif ($q_type === "insert" && !$bash) {
			$result = $conn->insert_id;
		} elseif ($q_type === "update" OR $q_type === "delete") {
			$result = (int) $conn->affected_rows;
		}

		return $result;
	}

	/**
	* Escapes special characters in a string for use in an SQL statement, taking into account the current charset of the connection
	* @return string
	*/
	public function safe($str=''){
		$conn = $this->connect();
		$str = $conn->real_escape_string(trim(rawurldecode($str)));
		return $str;
	}
}