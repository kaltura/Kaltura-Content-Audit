<?php
class TagSorter
{
  private $tagArr;

  function numOfMatches($entry) {
    return count(array_intersect($this->tagArr, explode(", ", $entry->tags)));
  }

  function cmp($a, $b) {
    return $this->numOfMatches($a) < $this->numOfMatches($b) ? 1 : -1;
  }

  public function tagSort($tags, $arr, $limit) {
    $this->tagArr = $tags;
    usort($arr, array($this, 'cmp'));
    $arr = array_slice($arr, 0, $limit);
    return $arr;
  }
}
