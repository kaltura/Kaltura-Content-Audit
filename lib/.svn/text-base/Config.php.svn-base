<?php

class Config {

	private static $iniFilePath = "config/config.ini.php";
	private static $roles = array();

	public static function setIniPath($path) {
		self::$iniFilePath = $path;
	}
	
	public static function getIniPath() {
		return self::$iniFilePath;
	}

	// NOTE: This is a work around for getting Mix types using the KalturaMediaEntryFilter filter
	// (and not the KalturaBaseEntryFilter one, that doesn't have a mediaType field)
	private static $mediaTypeForMix = 6;
	private static $mediaTypeForLiveStream = 7;
	public static $siteTimeout = 30;

	/**
	 * @return BaseUser
	 */
	public static function currentUserDetails() {
		$authClass = self::get('authClass', 'DemoAuth');
		if ($authClass) {
			$authObj = new $authClass;
			$user = $authObj->getCurrentUser();
			return $user;
		}
		return null;
	}

	public static function get($key, $default = null) {
		global $configtemplate;
		$config = parse_ini_file(self::$iniFilePath);
		if(!isset($config[$key]))
		{
			if(null !== $default)
			{
				return $default;
			}
			else
			{
				if(isset($configtemplate) && is_array($configtemplate) && isset($configtemplate[$key]) && isset($configtemplate[$key]['default']))
				{
					return $configtemplate[$key]['default'];
				}
			}
		}
		else
		{
			return $config[$key];
		}
	}

	public static function roleLookup($role, $default = null) {
		$result = array_search($role, self::getRolesArray());
		if($result !== false)
			return $result;
		return 'anonymousRole';
	}


	private static function getRolesArray() {
		if(count(self::$roles)) return self::$roles;

		$roles = array();
		$roles['viewerRole']			= self::get('viewerRole');
		$roles['adminRole']				= self::get('adminRole');
		$roles['privateOnlyRole']		= self::get('privateOnlyRole');
		$roles['unmoderatedAdminRole']	= self::get('unmoderatedAdminRole');

		self::$roles = $roles;
		return self::$roles;
	}

	public static function isRoleEnabled($role) {
 		return in_array(strtolower($role), array_map('strtolower', self::getRolesArray()));
	}


	public static function getArray($key, $default = null) {
		global $configtemplate;
		$config = parse_ini_file(self::$iniFilePath);
		if(isset($config[$key]))
		{
			$arr = $config[$key];
		}
		else
		{
			if(null !== $default)
			{
				$arr = $default;
			}
			else
			{
				if(isset($configtemplate) && is_array($configtemplate) && isset($configtemplate[$key]) && isset($configtemplate[$key]['values']))
				{
					if(is_array($configtemplate[$key]['values']))
					{
						$arr = array();
						foreach($configtemplate[$key]['values'] as $defaultval)
						{
							if(isset($defaultval['default']))
							{
								$arr[] =  $defaultval['default'];
							}
						}
					}
				}
			}
			
		}
		$newArr = array();
		if (isset($arr) && is_array($arr)) {
			foreach ($arr as $ind => $detailsStr) {
				$details = explode(",", $detailsStr);
				foreach ($details as $detail) {
					if (strlen($detail) == 0) continue;
					$pair = explode("=", $detail);
					$val = isset($pair[1]) ? $pair[1] : null;
					$newArr[$ind][$pair[0]] = $val; //$pair[1];
				}
			}
		}
		return $newArr;
	}

	public static function presentationsEnabled()
	{
		// this function checks that we have all we need in order for presentations to work
		return
				self::get('enablePresentations', false) && 
				self::get('kpwKcwId', false) &&
				self::get('ksuId', false) && 
				self::get('kpwId', false);
				
	}
	
	public static function getRootCategory() {
		$rootCategory = self::get("rootCategory");
		if ($rootCategory && strrpos($rootCategory, ">") !== strlen($rootCategory) - 1)
			$rootCategory .= ">";
		return $rootCategory;
	}

	public static function getVideosPerGalleryPage() {
		return 10;
	}

	public static function getMaxPagesInGallery() {
		return 8;
	}

	public static function getMaxItemsInGallery() {
		return self::getVideosPerGalleryPage() * self::getMaxPagesInGallery();
	}

	public static function updatingFilterToAllowedTypes(&$filter) {
		if(self::get("allowedMediaTypes"))
		{
			foreach (self::get("allowedMediaTypes") as $type) {
				switch ($type) {
					case "video" :
						$allowedMediaTypes[] = KalturaMediaType::VIDEO;
						break;
					case "audio" :
						$allowedMediaTypes[] = KalturaMediaType::AUDIO;
						break;
					case "image" :
						$allowedMediaTypes[] = KalturaMediaType::IMAGE;
						break;
				}
			}
		}
		else
		{
			$allowedMediaTypes[] = KalturaMediaType::VIDEO;
		}
		
		$filter->mediaTypeIn = implode(",", $allowedMediaTypes);
	
		/* removed code for filtering based on showMixes and showLiveStreams which have been depracated */
	}
	
	public static function getRolesForRestrictedCategory($category, &$roles_count) {
		$restricted_cats = Config::getArray('restrictedCategoriesRoles');
		$replace = '/[|]/';
		$allowed_roles = preg_replace($replace, ', ', $restricted_cats[0][$category], -1, $roles_count);

		return $allowed_roles;
	}
}

