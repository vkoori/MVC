<?php 
use model\QueryBuilder AS db;
use Rakit\Validation\Validator;
// use kernel\plugins\ValidationCall;
use kernel\plugins\Cache;

/**
 * 
 */
class sampleController extends kernel\responseController {

	/**
	* 
	* @return 
	*/
	public function test() {
		// $db = new db;
		// $x = $db::conn()->table('portfolios');
		// $res = $this->paginate($x);
		self::output($res,400, [__messages('test')]);
		var_dump(cors('allowed_origins'));
		echo __messages('test', ["test" => '-_-_-']);
	}

}