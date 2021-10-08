<?php 
namespace kernel\plugins;

use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

/**
 * 
 */
class Cache {
	
	public static $InstanceCache = null;

	function __construct() {
		if (is_null(self::$InstanceCache)) {
			CacheManager::setDefaultConfig(new ConfigurationOption([
				'path' => dirname(__dir__, 2).'/storage/cache/'
			]));

			self::$InstanceCache = CacheManager::getInstance('files');
		}
	}
}