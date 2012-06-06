<?php
class HttpHelper
{
	public static function getQuery($key, $default = null)
	{
		return isset($_GET[$key]) ? $_GET[$key] : $default;
	}
	
	public static function postQuery($key, $default = null)
	{
		return isset($_POST[$key]) ? $_POST[$key] : $default;
	}
	
	public static function pushFriendlyParamsToGet()
	{
		$requestUri = $_SERVER["REQUEST_URI"];
		$scriptName = $_SERVER["SCRIPT_NAME"];
		$query = str_replace($scriptName."/", "", $requestUri);
		$params = explode("/", $query);
		for($i = 0; $i < count($params); $i = $i + 2)
		{
			$_GET[$params[$i]] = (isset($params[$i+1]) ? urldecode($params[$i+1]) : null);
		}
	}
	
	// creating the base url with which the js and css are called
	public static function getBaseURL()
	{
		$base_url = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
		$base_url .= $_SERVER['HTTP_HOST'];
		
		$placeOfInd = strpos($_SERVER['SCRIPT_NAME'], "/index.php");
		if($placeOfInd === false) $placeOfInd = strpos($_SERVER['SCRIPT_NAME'], "/auth.php");
		if($placeOfInd === false) $placeOfInd = strpos($_SERVER['SCRIPT_NAME'], "/logout.php");
    if($placeOfInd === false) $placeOfInd = strpos($_SERVER['SCRIPT_NAME'], "/index.php/action/my-media");
    if($placeOfInd === false) $placeOfInd = strpos($_SERVER['SCRIPT_NAME'], "/index.php/action/playlist-page");
		$base_url .= $placeOfInd !== false ? substr($_SERVER['SCRIPT_NAME'], 0 , $placeOfInd) : $_SERVER['SCRIPT_NAME'];
		return $base_url."/";
	}
	
	public static function makeFriendlyString($str)
	{
		// remove leading number and underscore
		$str = preg_replace("/^\d+_/", '', $str);
		$str = str_replace(" ", "_", $str);
		$str = urlencode($str);
		return strtolower($str);
	}
	
	public static function makeFriendlyURLsForSEO($cat, $subCat, $video = null, $entryId = null, $catId = null)
	{
		$friendlyUrl = HttpHelper::getBaseURL()."index.php/show/";
		$cat = self::makeFriendlyString($cat);
		$subCat = self::makeFriendlyString($subCat);
		if($cat) $friendlyUrl .= "$cat/$subCat/";
		if($video)
			$friendlyUrl .= self::makeFriendlyString($video)."?id=$entryId";
		else
			$friendlyUrl .= "?cat=$catId";
		return $friendlyUrl;
	}
	
	public static function getUrlCategories()
	{
		// Getting the category, sub-category and video from the url (if they exist)
		$urlCats = array('cat' => '', 'subcat' => '', 'video' => '', 'entryid' => '');
		$show = strpos($_SERVER['REQUEST_URI'], "/show/");
		if($show !== false) {
			$urlParts = explode("/", substr($_SERVER['REQUEST_URI'], $show+6));
      // PUT THIS BACK WHEN HANDLING MANY CATEGORIES
      if(@$_GET['cat']) {
        $urlCats['cat'] = $urlParts[0];
        $urlCats['subcat'] = $urlParts[1];
      }
//			if($urlParts[2]) {
//				$videoAndId = explode("?id=", $urlParts[2]);
//				$urlCats['video'] = StringHelper::shortenString($videoAndId[0], 60);
//				$urlCats['entryid'] = $videoAndId[1];
//			}
      if($urlParts[0] && isset($_GET['id'])) {
        $videoAndId = explode("?id=", $urlParts[0]);
				$urlCats['video'] = StringHelper::shortenString($videoAndId[0], 60);
				$urlCats['entryid'] = $videoAndId[1];
      }
		}
		return $urlCats;
	}
}