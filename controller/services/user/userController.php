<?php 

/**
 * 
 */
class userController extends \kernel\responseController{
	
	/**
	 * get userid with @param $token
	 * @return json
	 */
	public function getUserId($token) {
		$url = self::$env['userinfo_base']."/api/v1/userid/".$token;
		$method = "GET";
		$body = [];
		$header = array(
			"Clientid" => self::$env['clientid']
		);

		$resp = $this->serviceCaller($method, $url, $body, $header);
		$data = $resp['body']->data;

		if (empty($data))
			die(self::output([], 401, ['user_not_found']));
		$userid = $data[0]->userid;
		return $userid;
	}

	/**
	 * get userinfo with @param $userids
	 * @return json
	 */
	public function getUsersInfo($userids) {
		$url = self::$env['userinfo_base']."/api/v1/userinfo/".$userids;
		$method = "GET";
		$body = [];
		$header = array(
			"Clientid" => self::$env['clientid']
		);

		$resp = $this->serviceCaller($method, $url, $body, $header);
		$data = $resp['body']->data;

		if (empty($data))
			die(self::output([], 401, ['user_not_found']));

		return $data;
	}
}