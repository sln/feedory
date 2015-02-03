<?php

// üäöß

require_once("config.php");
require_once("reader/simplepie.inc");

function addfeed($feedurl) {
	global $link, $dbprefix;

	$checkfeedexists=mysql_query("select * from ".$dbprefix."feeds where name=\"" . md5($feedurl) . "\"");

	if ($checkfeedexists != FALSE && mysql_num_rows($checkfeedexists) > 0) {
		print "A feed with this URL already exists in your subscribtions";
		return false;
	}

	// try {
		$feed = new SimplePie($feedurl);
	// } catch (Exception $e) {
		// print "Feed reader error\n";
		// print_r($e);
		// return false;
	// }

	if($feed->error()) {
		print "Error subscribing to feed...<br>\n";
		print $feed->error();
		return false;
	}

	$feedenc=$feed->get_encoding();
	$feedtype=$feed->get_type();
	$favicon=$feed->get_favicon();
	if ($favicon==null) {
		print "no favicon...\n";
	}
	
	// $feedtitle=htmlspecialchars($feed->get_title(), ENT_QUOTES, $feedenc);
	$feedtitle=mysql_real_escape_string($feed->get_title());
	$feedname=md5($feedurl);
	// $description=htmlspecialchars($feed->get_description(), ENT_QUOTES, $feedenc);
	$description=mysql_real_escape_string(htmlspecialchars($feed->get_description()));
	$language=$feed->get_language();

	$pubdate=date("Y-m-d H:i:s");
	$lastbuild=date("Y-m-d H:i:s");
	
	$imagelink=$feed->get_image_link();
	$imagetitle=$feed->get_image_title();
	$imageurl=$feed->get_image_url();
	
	$link=$feed->get_permalink();
	$url=$feed->subscribe_url();
	
	
	print "<img src=\"$favicon\" align=\"top\" width=16 height=16> <b>$feedtitle</b><br>\n";

	$feedsql="insert into ".$dbprefix."feeds values (
			0,
			'{$feedtitle}',
			'{$url}',
			1,
			'{$favicon}',
			'{$feedtype}',
			'{$feedenc}',
			'{$feedname}',
			'{$description}',
			'{$language}',
			'{$pubdate}',
			'{$lastbuild}',
			'{$imagetitle}',
			'{$imagelink}',
			'{$link}',
			'{$imageurl}')";
			
			// print ">>$feedsql<<\n";
			
			$res=MySQL_query($feedsql);
			
    if (mysql_error()) {
		print "<font color=\"red\">" . mysql_error() . "</font>";
		return false;
	}
	
	return true;
}

?>
