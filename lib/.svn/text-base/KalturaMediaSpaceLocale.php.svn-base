<?php
class KalturaMediaSpaceLocale
{
  public static function localize($term)
	{
    $locale = Config::get("locale");
    if(!$locale) {
      return $term;
    }
    else {
      $localize = parse_ini_file("locale/" . $locale . ".ini.php");
      $termArr = explode(" ", $term);
      $transTerms = array();
      foreach($termArr as $word) {
        $transTerms[] = isset($localize[$word]) ? $localize[$word] : $word;
      }
      return implode(" ", $transTerms);
    }
	}

  public static function getTranslations()
  {
    $locale = Config::get("locale");
    return (!$locale) ? false : parse_ini_file("locale/" . $locale . ".ini.php");
  }
}
