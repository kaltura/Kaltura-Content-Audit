<?php
require_once('./lib/KalturaClient.php');
$page = "1";

if (($_POST['partnerId']!=="") && isset($_POST['username']) && isset($_POST['password']) ) {
$partnerId = $_POST['partnerId'];
$config = new KalturaConfiguration($partnerId);
$config->serviceUrl = 'http://www.kaltura.com/';
$client = new KalturaClient($config);
$expiry = null;
$privileges = null;
$client->setKs('');
$loginId = $_POST['username'];
$password = $_POST['password'];
try {$results = $client-> user ->loginByLoginId($loginId, $password, $partnerId, $expiry, $privileges); 
$client->setKs($results);

$filter = new KalturaBaseEntryFilter();
$filter->typeEqual = KalturaEntryType::MEDIA_CLIP;
$filter->orderBy = KalturaBaseEntryOrderBy::CREATED_AT_DESC;
$pager = new KalturaFilterPager();
$pager->pageSize = 500;
$page = (isset($_POST['page']))? $_POST['page'] : "1";
$pager->pageIndex = $page;
$results = $client->baseEntry->listAction($filter, $pager);
}
catch (Exception $e) { }
}

if(!$_POST['download']){
echo <<<_END
<html>
<head>
<title>
Man Utd - Content Audit</title>
<link rel="stylesheet" type="text/css" href="./css/table.css" />
</head>
<body>

<form class="inlineForm" action="contentAudit.php" method="post">
Partner id: <input type="text" name="partnerId" value="$partnerId" />
&nbsp;&nbsp;&nbsp;&nbsp;
Username: <input type="text" name="username" value="$loginId" />
&nbsp;&nbsp;&nbsp;&nbsp;
Password: <input type="password" name="password" value="$password" />
Page: <input type="text" name="page" value="$page" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" name="load" value="LOAD" />
<input type="submit" name="download" value="DOWNLOAD"  />
</form>
<br/><br/>

_END;
}



if(isset($results) && $_POST['download']){
$FileName = "Kaltura_Content_Audit_".date("d-m-y")."_Page_".$page . '.csv';
$Content = "TEST \n";

# Title of the CSV
$Content = "Entry ID, Name, Creation Date, Duration (secs) \n";

# fill data in the CSV
foreach ($results->objects as $entry) {
$date = date( 'd M Y', $entry->createdAt );

$Content .= $entry->id.",".str_replace(",", "",$entry->name).",".$date.",".$entry->duration." \n";
}

header('Content-Type: application/csv'); 
header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
echo $Content;
exit();  

}

elseif(isset($results) && $_POST['load']){
echo <<<_END
<table >
<tr class="headerRow">
<th>#</th>
<th>Entry Id</th>
<th>Name</th>
<th>Creation Date</th>
<th>Duration<br/>(seconds)</th>
</tr>
_END;

$num = 500*($_POST['page']-1);

foreach ($results->objects as $entry) {
$date = date( 'd M Y', $entry->createdAt );
$num++;
echo <<<_END
<tr>
<td>$num</td>
<td>$entry->id</td>
<td class="leftAlign">$entry->name</td>
<td>$date</td>
<td>$entry->duration</td>
</tr>
_END;
}



}

if(!$_POST['download']){
echo "</table><br/><br/>Note: Only up to 500 records are shown per page and commas are stripped from Names when downloaded.
</body>
</html>
";

}

?>



