<?php 
namespace kernel;

// use kernel\plugins\common as func;

/**
 * 
 */
class middlewareController extends responseController {

	/**
	* check is set Token into header?
	* @return Token
	*/
	protected function getToken() {
		if (!isset(getallheaders()['Token']))
			die(self::output([], 422, ['unprocessable_entity']));

		$token = getallheaders()['Token'];

		if ($token == '')
			die(self::output([], 422, ['unprocessable_entity']));

		return $token;
	}
}