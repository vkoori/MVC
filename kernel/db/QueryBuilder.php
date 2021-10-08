<?php 
namespace model;
use Illuminate\Database\Capsule\Manager as Capsule;
// use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

/**
 * All basic functions we need
 */
class QueryBuilder extends baseDB {

	private static $sdb = null;

	public static function conn(){
		if (is_null(self::$sdb)) {
			baseDB::init();

			$capsule = new Capsule;
			$capsule->addConnection([
				'driver' => 'mysql',
				'host' => self::$host,
				'database' => self::$db,
				'username' => self::$user,
				'password' => self::$pass,
				'charset' => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix' => '',
			]);

			// Set the event dispatcher used by Eloquent models... (optional)
			# need install: composer require illuminate/events
			// $capsule->setEventDispatcher(new Dispatcher(new Container));

			// Make this Capsule instance available globally via static methods... (optional)
			$capsule->setAsGlobal();

			// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
			$capsule->bootEloquent();

			self::$sdb = $capsule;
		}
		return self::$sdb;
	}
}