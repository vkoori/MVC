<?php 
/*use kernel\plugins\common as func;
use model\QueryBuilder as db;
// use ClanCats\Hydrahon\Query\Sql\Func as F;
use Rakit\Validation\Validator;
use kernel\plugins\Cache;*/

/**
 * 
 */
class hasAccess extends \kernel\middlewareController {

	/**
	* 
	* @return 
	*/
	public function handle(&$params, $args) {
		$token = $this->getToken();

		$userClass = $this->useClass('controller\services\user\userController');
		$userid = $userClass->getUserId($token);

		$adminClass = $this->useClass('controller\services\admin\adminController');
		$accessList = $adminClass->getAccess($userid, $token);

		$has_acess = false;
		foreach ($args as $arg) {
			if (in_array($arg, $accessList)) {
				$has_acess = true;
				break;
			}
		}

		if (!$has_acess)
			return self::output([], 401, ['auth_failed']);

		$data = array(
			'adminId' => $userid,
		);

		$params = array_merge($params, ['middleware' => $data]);

		return $has_acess;
	}
}