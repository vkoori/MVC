<?php

# Register composer auto loader
require __DIR__.'/vendor/autoload.php';

# timezone
date_default_timezone_set(config("TIMEZONE"));

# error handler
if( $_SERVER['HTTP_HOST'] != 'localhost' AND !config("APP_DEBUG") ) {
	error_reporting(0);
	ini_set('error_reporting',0);
	ini_set('display_errors',0);
}

# Setup prefix (Group)
$web_prefix = config("APP_PREFIX");
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) use ($web_prefix) {
	# define routes
	$r->addGroup($web_prefix.'/api/v1', function (FastRoute\RouteCollector $r) {
		require __DIR__.'/routes/api_v1.php';
	});
});

# Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

# Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
	$uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
	case FastRoute\Dispatcher::NOT_FOUND:
		http_response_code(404);
		echo "404";
		break;
	case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
		http_response_code(405);
		$allowedMethods = $routeInfo[1];
		echo "405";
		break;
	case FastRoute\Dispatcher::FOUND:
		$handler = $routeInfo[1];
		if (gettype($handler) == "array")
			$controller = $handler['use'];
		else
			$controller = $handler;

		[$path_class, $method] = explode('@', $controller);

		if (strpos($path_class, '/') !== false)
			$class = substr($path_class, strrpos($path_class, '/') + 1);
		else
			$class = $path_class;

		$vars = $routeInfo[2];


		# set offset
		$page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;
		$offset = _env('limit') * ($page - 1);
		_env()->setter('offset', $offset);

		# set lang
		$lang = (isset(getallheaders()['lang'])) ? getallheaders()['lang'] : config("LANG");
		_setlocale($lang);

		# call middleware
		if (isset($handler['middleware'])) {
			$middleArgs = array();

			$middleware_middleArgs = $handler['middleware'];
			if (gettype($middleware_middleArgs) == "string")
				$middleware_middleArgs = [$middleware_middleArgs];

			foreach ($middleware_middleArgs as $m_a) {
				$m_a = explode(':', $m_a);
				
				$middleware = $m_a[0];
				if (isset($m_a[1])) {
					$middleArgs = $m_a[1];
					$middleArgs = explode(',', $middleArgs);
				}

				require_once __DIR__."/middleware/$middleware.php";
				
				$middlewareClass = new $middleware;
				$result = $middlewareClass->handle($vars, $middleArgs);
				if ($result !== true)
					die();
			}
		}

		require_once __DIR__."/controller/$path_class.php";

		if (sizeof($vars) > 0)
			(new $class)->$method($vars);
		else
			(new $class)->$method();
		break;
}