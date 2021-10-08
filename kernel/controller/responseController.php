<?php 
namespace kernel;

// use kernel\plugins\common as func;

/**
 * 
 */
class responseController extends baseController {

	/**
	* 
	* 
	* @return 
	*/
	protected static function output($data=null, $status=200, $message=[], $header=["Content-Type" => "application/json"]) {
		self::cors();
		http_response_code($status);
		
		$response = (object) array();
		
		if (isset($data['paginate'])) {
			$response->paginate = $data['paginate'];
			unset($data['paginate']);
		}
		
		$response->data = $data;
		
		if (sizeof($message)>0)
			$response->message = $message;
		

		foreach ($header as $key => $value) {
			header("$key: $value");
		}

		echo json_encode($response);
		exit();
	}

	private static function cors() {
		if (!empty( cors('allowed_origins') ))
			header('Access-Control-Allow-Origin: '.implode(',', cors('allowed_origins') ));

		if (!empty( cors('allowed_headers') ))
			header('Access-Control-Allow-Headers: '.implode(',', cors('allowed_headers') ));

		if (!empty( cors('allowed_methods') ))
			header('Access-Control-Allow-Methods: '.implode(',', cors('allowed_methods') ));

		if (!empty( cors('exposed_headers') ))
			header('Access-Control-Expose-Headers: '.implode(',', cors('exposed_headers') ));

		header('Access-Control-Max-Age: '.cors('max_age'));
		header('Access-Control-Allow-Credentials: '.cors('supports_credentials'));
	}

	protected function paginate($q) {
		if (isset($_GET['page']) AND $_GET['page'] == 0) {
			$res = current($q->get());
		} else {
			$res = $q->take(_env('limit'))
			->skip(_env('offset'))
			->get()
			->toArray();
			
			$res['paginate'] = $this->pagenumber($q);
		}

		return $res;
	}

	protected function pagenumber($q) {
		if (!is_null($q->groups) OR $q->distinct)
			$total = sizeof(current($q->get()));
		else
			$total = $q->count();

		$currentPage = (isset($_GET['page'])) ? (int) $_GET['page'] : 1 ;
		$lastPage = (int) ceil($total / _env('limit'));
		
		return array(
			'currentPage' => $currentPage,
			'lastPage' => $lastPage,
			'total' => $total
		);
	}
}