<?php 

/**
 * 
 */
class verificationController extends \kernel\responseController{
	
	/**
	 * get verified users
	 * @return json
	 */
	public function verifiedList($userids) {
		$url = self::$env['user_verification'].'/api/v1/verification-list/'.$userids;
		$method = "GET";
		$body = [];
		$header = array(
			// "Token" => getallheaders()['Token']
		);

		$resp = $this->serviceCaller($method, $url, $body, $header);
		return $resp;
	}
}