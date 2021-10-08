<?php 
namespace kernel;

/**
 * All basic functions we need
 */
class baseController {

	/**
	* call class in to method
	* if class exist : $use = namespace\ClassName
	* if class not exist : $use = directory_class_from_root\ClassName (directory_class_from_root=namespace)
	* @return class
	*/
	protected function useClass($use='') {
		$use = str_replace("/","\\",$use);
		if (!class_exists($use)) {
			$use = str_replace("\\","/",$use);
			require_once dirname(__DIR__, 2).'/'.$use.'.php';
			$use = str_replace("/","\\",$use);
			if (!class_exists($use))
				$use = substr($use, strrpos($use, '\\') + 1);
		}
		return new $use;
	}

	/**
	 * This method send curl request
	 * @return json
	 */
	protected function curl($method, $url, $body=[], $header=[]) {
		if ($method == 'GET')
			$url = $url . '?' . http_build_query($body);

		$headerReq = array();
		foreach ($header as $k => $value) {
			array_push($headerReq, "$k:$value");
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_POSTFIELDS => $body,
			CURLOPT_HTTPHEADER => $headerReq,
			// CURLOPT_HEADER => true,
			CURLOPT_SSL_VERIFYPEER => false
		));

		$response = curl_exec($curl);
		curl_close($curl);

		$result = array(
			'http_code' => curl_getinfo($curl)['http_code'],
			'body' => $response
		);

		return $result;
	}

	protected function serviceCaller($method, $url, $body=[], $header=[]) {
		$resp = $this->curl($method, $url, $body, $header);
		$resp['body'] = json_decode($resp['body']);
		return $resp;
	}

	/**
	 * This method use for parse put request
	 * @return array
	 */
	protected function parse_raw_http_request() {
		$pattern = "/^\w+\[\d+\]$/u";
		$data = array();
		// read incoming data
		$input = file_get_contents('php://input');

		// grab multipart boundary from content type header
		preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
		$boundary = $matches[1];

		// split content by boundary and get rid of last -- element
		$a_blocks = preg_split("/-+$boundary/", $input);
		array_pop($a_blocks);

		// loop data blocks
		foreach ($a_blocks as $id => $block) {
			if (empty($block))
				continue;

			// you'll have to var_dump $block to understand this and maybe replace \n or \r with a visibile char

			// parse uploaded files
			if (strpos($block, 'application/octet-stream') !== FALSE) {
				// match "name", then everything after "stream" (optional) except for prepending newlines 
				preg_match("/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s", $block, $matches);
			}
    		// parse all other fields
			else {
				// match "name" and optional value in between newline sequences
				preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
			}
		
			if(preg_match($pattern, $matches[1])) {
				[$key, $index] = explode('[', $matches[1]);
				$index = rtrim($index, ']');
				if (!isset($data[$key]))
					$data[$key] = array();
				$data[$key][$index] = (is_numeric($matches[2])) ? (int) $matches[2] : $matches[2];
			} else {
				$data[$matches[1]] = $matches[2];
			}
		}
		return $data;
	}

	protected function parse_raw_del() {
		$request = file_get_contents('php://input');
		parse_str($request, $delete);
		return $delete;
	}

	protected function parse_raw_put() {
		$put = json_decode(file_get_contents('php://input'), true);
		return $put;
	}
}