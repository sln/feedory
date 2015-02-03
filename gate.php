<?php

// ini_set ("display_errors", "1");
// error_reporting(E_ALL);  

if (!function_exists("htmlspecialchars_decode")) {
    function htmlspecialchars_decode($string, $quote_style = ENT_COMPAT) {
        return strtr($string, array_flip(get_html_translation_table(HTML_SPECIALCHARS, $quote_style)));
    }
}

// üäöß

$feed=$_REQUEST['feed'];

require_once("writer/FeedWriter.php");
require_once("config.php");

$feedresult = mysql_query("select * from ".$dbprefix."feeds where name=\"{$feed}\"");
$feedrow = mysql_fetch_array($feedresult);

$OutFeed = new FeedWriter(ATOM);

// $OutFeed->setTitle("fy# " . htmlspecialchars_decode($feedrow['title'], ENT_QUOTES));
// $OutFeed->setLink("{$feedrow['link']}");
// $OutFeed->setDescription(htmlspecialchars_decode($feedrow['description'], ENT_QUOTES));
// $OutFeed->setImage("{$feedrow['imagetitle']}","{$feedrow['imagelink']}","{$feedrow['imageurl']}");

$OutFeed->setTitle("fy# " . $feedrow['title'], ENT_QUOTES);
$OutFeed->setLink("{$feedrow['link']}");
$OutFeed->setDescription($feedrow['description'], ENT_QUOTES);
$OutFeed->setImage("{$feedrow['imagetitle']}","{$feedrow['imagelink']}","{$feedrow['imageurl']}");

$result = mysql_query("select * from ".$dbprefix."items where feedname=\"{$feed}\" order by date desc");

while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$newItem = $OutFeed->createNewItem();

	$newItem->setDate($row['date']);

	// $newItem->setTitle(html_entity_decode($row['title'], ENT_QUOTES));
	// $newItem->setLink(html_entity_decode($row['link'], ENT_QUOTES));
	// $newItem->setDescription(html_entity_decode($row['description'], ENT_QUOTES));

	// $newItem->setTitle(htmlspecialchars_decode($row['title'], ENT_QUOTES));
	// $newItem->setLink(htmlspecialchars_decode($row['link'], ENT_QUOTES));
	// $newItem->setDescription(htmlspecialchars_decode($row['description'], ENT_QUOTES));

	$newItem->setLink($row['link']);
	$newItem->setDescription($row['description']);
		
	$newItem->setTitle(strip_tags($row['title']));
	// $newItem->setLink($row['link']);
	// $newItem->setDescription($row['description']);

	$OutFeed->addItem($newItem);
}

$OutFeed->genarateFeed();

?>
