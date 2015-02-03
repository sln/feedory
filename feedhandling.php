<?php

require_once("config.php");
require_once("pageheader.php");

$action=$_REQUEST['action'];
$feed=$_REQUEST['feed'];

$result = mysql_query("select * from ".$dbprefix."feeds where name=\"{$feed}\"");
$row = mysql_fetch_array($result);

$items=array();
$itemsquery = mysql_query("select title, date, link
							from ".$dbprefix."items
							where feedname=\"{$feed}\"
							order by date");

while ($itemsrow=mysql_fetch_array($itemsquery)) {
	array_push($items, array($itemsrow['title'], $itemsrow['date'], $itemsrow['link']));
}

$itemcount=count($items);
$lastitem=$items[$itemcount - 1];
$firstitem=$items[0];

if ($row['favicon'] != "") {
	$favicon="<img height=16 width=16 src=\"{$row['favicon']}\" align=\"top\"> ";
} else  {
	$favicon="";
}


// ask for resetting
if ($action == "reset" ) {
	print "<div class=\"header\">Resetting feed</div>\n";
	print "{$favicon} <b>{$row['title']}</b><p>\n";
	print "The feed contains <b>{$itemcount}</b> feeditems<p>\n";
	print "<dir>latest item: <b><a target=\"_blank\"  href=\"{$lastitem[2]}\">{$lastitem[0]}</a></b> - <i>{$lastitem[1]}</i><br>\n";
	print "<p><dir><img src=\"images/dots.png\"></dir></p>\n";
	print "oldest item: <b><a target=\"_blank\"  href=\"{$firstitem[2]}\">{$firstitem[0]}</a></b> - <i>{$firstitem[1]}</i></dir>\n";
	
	print "<p><input type=\"button\" onclick=\"document.location='feedlist.php'\" value=\"Cancel\">
	<input type=\"button\" onclick=\"document.location='feedhandling.php?action=doreset&feed={$feed}'\" value=\"Really reset feed\">\n";

// do the resetting
} elseif ($action == "doreset" ) {
	$doreset = mysql_query("delete from ".$dbprefix."items where feedname=\"{$feed}\"");
	// try {
		@unlink("cache/" . $feed . ".spc");
	// } catch (Exception $e) {
		// print_r($e);
	// }
	print "<script>document.location='feedlist.php';</script>\n";

// ask for deletion
} elseif ($action == "delete" ) {
	print "<div class=\"header\">Deleting feed</div>\n";
	print "{$favicon} <b>{$row['title']}</b><p>\n";
	print "The feed contains <b>{$itemcount}</b> feeditems<p>\n";
	print "<dir>latest item: <b><a target=\"_blank\"  href=\"{$lastitem[2]}\">{$lastitem[0]}</a></b> - <i>{$lastitem[1]}</i><br>\n";
	print "<p><dir><img src=\"images/dots.png\"></dir></p>\n";
	print "oldest item: <b><a target=\"_blank\"  href=\"{$firstitem[2]}\">{$firstitem[0]}</a></b> - <i>{$firstitem[1]}</i></dir>\n";
	
	print "<p><input type=\"button\" onclick=\"document.location='feedlist.php'\" value=\"Cancel\">
	<input type=\"button\" onclick=\"document.location='feedhandling.php?action=dodelete&feed={$feed}'\" value=\"Really delete feed\">\n";

// do the deletion
} elseif ($action == "dodelete" ) {
	$dodeleteitems = mysql_query("delete from ".$dbprefix."items where feedname=\"{$feed}\"");
	$dodeletefeed = mysql_query("delete from ".$dbprefix."feeds where name=\"{$feed}\"");

	// try {
		@unlink("cache/" . $feed . ".spc");
	// } catch (Exception $e) {
		// print_r($e);
	// }
	print "<script>document.location='feedlist.php';</script>\n";

} else {
	print "unknown action\n";
	exit;
}

?>