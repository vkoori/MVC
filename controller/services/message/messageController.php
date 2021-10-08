<?php 

/**
 * 
 */
class messageController extends \kernel\responseController{
	
	/**
	 * get userinfo with @param $userids
	 * @return json
	 */
	public function sms($mobile, $template, $params='{}') {
		$url = self::$env['message_base']."/api/v1/sms/".$template;
		$method = "POST";
		$body = [
			'params' => $params,
			'mobile' => $mobile
		];
		$header = array(
			"Clientid" => self::$env['clientid']
		);

		$resp = $this->serviceCaller($method, $url, $body, $header);

		return $resp;
	}
}