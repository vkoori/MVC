<?php 
/*use kernel\plugins\common as func;
use model\QueryBuilder as db;
// use ClanCats\Hydrahon\Query\Sql\Func as F;
use Rakit\Validation\Validator;
use kernel\plugins\Cache;*/

/**
 * 
 */
class setUserid extends \kernel\middlewareController {

	/**
	* 
	* @return 
	*/
	public function handle(&$params, $args) {
		$token = $this->getToken();

		$userClass = $this->useClass('controller\services\user\userController');
		$userid = $userClass->getUserId($token);

		$data = array(
			'userid' => $userid,
		);

		$params = array_merge($params, ['middleware' => $data]);
		
		return true;
	}
}