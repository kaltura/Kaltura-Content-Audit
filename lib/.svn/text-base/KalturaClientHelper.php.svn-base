<?php
class KalturaClientHelper 
{
	private static $client = null;
	
	private static function createKS($partnerId, $adminSecret, $sessionType = KalturaSessionType::USER, $expiry = 7200)
	{
		$user = Config::currentUserDetails();
		$puserId = isset($user) && isset($user->username) ? strtolower($user->username) : '';
		$privileges = '';
		
		$rand = rand(0, 32000);
		$rand = microtime(true);
		$expiry = time() + $expiry;
		$fields = array($partnerId, '', $expiry, $sessionType, $rand, $puserId, $privileges);
		$str = implode(";", $fields);
		
		$salt = $adminSecret;
		$hashedStr = self::hash($salt, $str) . "|" . $str;
		$decodedStr = base64_encode($hashedStr);
		
		return $decodedStr;
	}
	
	private static function hash($salt, $str)
	{
		return sha1($salt . $str);
	}
	
	public static function getPartnerId()
	{
		return Config::get("partnerId", 0);
	}
	
	public static function getServiceUrl()
	{
		return Config::get("serviceUrl");
	}
	
	public static function getKs($type)
	{
		$partnerId = Config::get("partnerId");
		$secret = Config::get("adminSecret");
		$ks = self::createKS($partnerId, $secret, $type);
		
		return $ks;
	}
	
	/**
	 * @param KalturaSessionType $type
	 * @return KalturaClient
	 */
	public static function getClient($type = KalturaSessionType::USER)
	{
		if(self::$client)
			return self::$client;
			
		$partnerId = self::getPartnerId();
		$ks = self::getKs($type);
		
		$config = new KalturaConfiguration($partnerId);
		$config->serviceUrl = self::getServiceUrl();
		$config->curlTimeout = Config::$siteTimeout;
		
		// for logger
//		$myLoger = new logerClass("testLog.log");
//		$config->setLogger($myLoger);

		$client = new KalturaClient($config);
		$client->setKs($ks);
			
		self::$client = $client;
		
		return $client;
	}
}