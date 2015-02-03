<?php

// üäöß

$cfile="config.php";
$stat=stat($cfile);
if (! $stat[7] > 0 || ! is_readable($cfile)) {
	print "Erroneous access to config.php -> please try to <a href=\"install/\" target=\"_top\">install</a> feedory\n";
	exit;
}

require_once("config.php");
require_once("pageheader.php");

$result = mysql_query("select * from ".$dbprefix."feeds order by title");

if ($result != FALSE && mysql_num_rows($result) > 0) {
	print "<table cellpadding=4 cellspacing=4 width=\"100%\" border=0>\n";
	print "<tr class=\"tableheader\"><td width=\"50%\">Title<td>Itemcount<td>feedory RSS URL<td>Original RSS URL<td>Feed Management</tr>\n";

	while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

		$feedoryrss="{$basehost}{$baseurl}gate.php?feed={$row['name']}";
		$originalrss=$row['url'];

		$itemsquery = mysql_query("select count(*) AS itemcount from ".$dbprefix."items where feedname=\"{$row['name']}\"");
		
		$rowitems=mysql_fetch_array($itemsquery);

		if ($row['favicon'] != "") {
			$favicon="<img height=16 width=16 src=\"{$row['favicon']}\" align=\"top\"> ";
		} else  {
			$favicon="";
		}
		
		$feedhandling="<a href=\"#\" onclick=\"document.location='feedhandling.php?action=reset&feed={$row['name']}';return false;\"><img border=0 noborder src=\"images/reset.png\" title=\"Reset feed\"></a>
		<a href=\"#\" onclick=\"document.location='feedhandling.php?action=delete&feed={$row['name']}';return false;\"><img border=0 noborder src=\"images/delete.png\" title=\"Delete feed\"></a>
		";
		
		print "<tr>
		<td class=\"feedtitle\" width=\"50%\">$favicon{$row['title']}
		<td align=\"right\">{$rowitems['itemcount']}
		<td align=\"center\"><a href=\"{$feedoryrss}\"><img src=\"images/rss.png\" border=0 norborder></a>
		<td align=\"center\"><a href=\"{$originalrss}\"><img src=\"images/rss2.png\" border=0 norborder></a>
		<td align=\"center\">{$feedhandling}<tr>\n";
	}

	print "</table>\n";
} else {
	print "no subscribtions...\n";
}

?>
