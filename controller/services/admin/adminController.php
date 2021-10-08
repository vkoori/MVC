<?php 

/**
 * 
 */
class adminController extends \kernel\responseController{
	
	/**
	* call administrator webservice
	* @return 
	*/
	public function getAccess($userid, $token) {
		$url = self::$env['admin_base']."/api/v1/admin/{$userid}";
		$method = "GET";
		$body = [
			"service" 	=> self::$env['SERVICE_ADMIN']
		];
		$header = array(
			"Token" 	=> $token
		);

		$resp = $this->serviceCaller($method, $url, $body, $header);
		$data = $resp['body']->data;

		if (empty($data))
			die(self::output([], 401, ['auth_failed']));
		
		if ($data[0]->state != self::$env['VERIFIED'])
			$access = '';
		else
			$access = $data[0]->access;

		$accessList = explode(',', $access);
		return $accessList;
	}
}