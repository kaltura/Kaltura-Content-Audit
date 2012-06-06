<?php

class StringHelper {

	public static function formatDuration($value) {
		$hours = str_replace(" ", "", sprintf("%02d", $value / 3600));
		$mins = str_replace(" ", "", sprintf("%02d", ($value % 3600) / 60));
		$secs = sprintf("%02d", $value % 60);
		return $hours != "00" ? "$hours:$mins:$secs" : "$mins:$secs";
	}

	public static function shortenString($value, $maxLength) {
		return substr($value, 0, $maxLength);
	}

	public static function shortenDescription($desc, $maxLength = 200) {
		$desc = StringHelper::htmlEntities($desc);
		return (strlen($desc) > $maxLength) ? substr($desc, 0, $maxLength - 3) . "..." : $desc;
	}

	public static function escapeQuotes($value) {
		$value = iconv("UTF-8","UTF-8//IGNORE",$value);
		$value = htmlentities($value, ENT_QUOTES, "UTF-8");
/*		$value = str_replace('"', "&quot;", $value);
		$value = str_replace("'", "&rsquo;", $value);*/
		return $value;
	}

	public static function htmlEntities($value) {
		$value = iconv("UTF-8","UTF-8//IGNORE",$value);
		$value = htmlentities($value, ENT_QUOTES, "UTF-8");
		return $value;
	}
	
	
	public static function escapeComma($value) {
		$value = str_replace("‚", "&sbquo;", $value);
		return $value;
	}

	public static function formatCategories($categories) {
		$catsArr = array();
		$rootCat = Config::get('rootCategory');
		$rootCatLen = strlen($rootCat);
		foreach (explode(",", $categories) as $cat) {
			if (substr($cat, 0, $rootCatLen) == $rootCat) {
				$catParts = explode(">", $cat);
				array_shift($catParts);
				$catsArr[] = $catParts;
			}
		}
		return $catsArr;
	}

	public static function getAuthorNameFromTags($tagsStr) {
		$tags = explode(", ", $tagsStr);
		foreach ($tags as $tag) {
			if (strpos($tag, "displayname_") !== false) {
				$dispName = substr($tag, 12);
				return $dispName;
			}
		}
		return false;
	}

	public static function compareToLower($a,$b) {
		$same = false;
		if(strtolower($a) == strtolower($b))
			$same = true;
		return $same;
	}


}

