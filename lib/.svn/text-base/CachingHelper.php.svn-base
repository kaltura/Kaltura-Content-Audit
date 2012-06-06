<?php 
class CachingHelper 
{
	const EXPIRE_FILE_PREFIX = '.expire';
	
	public static function put($key, $object)
	{
		if (self::getExpiry() == 0) // don't use cache
			return;

		$expireFile = self::getExpireFilePath($key);
		$cacheFile = self::getCacheFilePath($key);
		$created = time(); // + self::getExpiry();
		
		$content = serialize($object);
		file_put_contents($cacheFile, $content);
		file_put_contents($expireFile, $created);
	}
	
	public static function get($key)
	{
		if (self::exists($key))
		{
			$cacheFile = self::getCacheFilePath($key);
			$content = file_get_contents($cacheFile);
			$object = unserialize($content);
			return $object;
		}
		return null;
	}
	
	public static function exists($key)
	{
		if (self::getExpiry() == 0) // don't use cache
			return false;
			
		$expireFile = self::getExpireFilePath($key);
		$cacheFile = self::getCacheFilePath($key);
		if (file_exists($expireFile) && file_exists($cacheFile))
		{
			if (self::getExpiry() == -1) // indefinite cache
				return true;
			
			$created = file_get_contents($expireFile);
			$expiry = (int)$created + self::getExpiry();
			
			if (!$expiry || time() > $expiry)
				return false;
			else
				return true;
		}
		else
		{
			return false;
		}
	}
	
	protected function getExpireFilePath($key) 
	{
		$dir = self::getCacheDirectory();
		return $dir . DIRECTORY_SEPARATOR . $key . self::EXPIRE_FILE_PREFIX;
	}
	
	protected function getCacheFilePath($key)
	{
		$dir = self::getCacheDirectory();
		return $dir . DIRECTORY_SEPARATOR . $key;
	}
	
	protected function getExpiry()
	{
		$expiry = Config::get('cache.expire', 0);
		return (int)$expiry;
	}
	
	protected static function getCacheDirectory()
	{
		$dir = Config::get('cache.directory');
		if (is_null($dir))
			throw new Exception('cache.directory not found in config');
			
		$realDirectory = realpath($dir);
		if ($realDirectory === false) 
			mkdir($dir);
		
		if (!is_dir($realDirectory))
			throw new Exception('Directory ['.$dir.'] not found');
			
		return $dir;
	}
}