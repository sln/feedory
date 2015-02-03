<?php

// üäöß

// ini_set ("display_errors", "1");
// error_reporting(E_ALL);

require_once("reader/simplepie.inc");
require_once("config.php");

$now = date("Y-m-d H:i:s");

function xmlEnts($in) {

	$raw=array("&#60;", "&lt;", "<",
				"&#62;", "&gt;", ">",
				"&#34;", "&quot;", "\"",
				"&apos;", "'",
				"&#38;", "&amp;", "&");
				
	$enc=array("&lt;","&lt;","&lt;",
				"&gt;", "&gt;", "&gt;",
				"&quot;", "&quot;", "&quot;",
				"&apos;", "&apos;",
				"&", "&", "&amp;");

	$out = str_replace($raw, $enc, $in);
	return $out;
}

function fetchfeed($feedid, $feedurl) {
	global $dbprefix, $now;
	
	// try {
		$feed = new SimplePie();
		$feed->set_feed_url($feedurl);
		$feed->set_cache_duration(600);
		$feed->set_timeout(25);
		$feed->set_useragent("feedory - (sourceforge.net/projects/feedory - newsfeed memory)");
		$feed->init();
		// $feed->handle_content_type();
	// } catch (Exception $e) {
		// print "Feed reader error\n";
		// print_r($e);
	// }

	if ($feed->error()) {
		print "Error updating feed...<br>\n";
		print $feed->error();
		return;
	}
	
	$feedenc=$feed->get_encoding();
	
	// reading stored articles for that feed
	$storeditems=array();
	
	$storeditemssql = mysql_query("select * from ".$dbprefix."items where feedname=\"{$feedid}\"");
	if ($storeditemssql != FALSE && mysql_num_rows($storeditemssql) > 0) {
		while($storeditemsrow = mysql_fetch_array($storeditemssql)) {
			array_push($storeditems, $storeditemsrow['postid']);
		}
	}
	// end reading stored articles

	// print $feed->get_channel_tags();
	@$feedtags=$feed->get_feed_tags('', 'channel');
	@$channelPubDate=$feedtags[0]['child']['']['pubDate'][0]['data'];
	
	if ($channelPubDate == null) {
		$channelPubDate = $now;
	}
	
	foreach ($feed->get_items() as $item) {

		// FIXME check if already stored
		if (in_array($item->get_id(), $storeditems)) {
			continue;
		}
	
		$author=$item->get_author();
		if ($author != null) {
			$an=$author->get_name();
			// $author=$author->get_name();
		} else {
			$an = "";
		}

		// $authorname=htmlentities($an, ENT_QUOTES, $feedenc);
		// $title=htmlentities($item->get_title(), ENT_QUOTES, $feedenc);
		// $description=htmlentities($item->get_description(), ENT_QUOTES, $feedenc);
		// $content=htmlentities($item->get_content(), ENT_QUOTES, $feedenc);
		// $link=htmlentities($item->get_permalink(), ENT_QUOTES, $feedenc);

		// $authorname=htmlspecialchars($an, ENT_QUOTES, $feedenc);
		// $title=xmlEnts($item->get_title());
		// // $description=htmlspecialchars($item->get_title(), ENT_QUOTES, $feedenc);
		// $description=htmlspecialchars($item->get_description(), ENT_QUOTES, $feedenc);
		// $content=htmlspecialchars($item->get_content(), ENT_QUOTES, $feedenc);
		// $link=htmlspecialchars($item->get_permalink(), ENT_QUOTES, $feedenc);

		$authorname=mysql_real_escape_string($an);
		$title=mysql_real_escape_string($item->get_title());
		$cleartitle=$item->get_title();
		$description=mysql_real_escape_string($item->get_description());
		$content=mysql_real_escape_string($item->get_content());
		$link=mysql_real_escape_string($item->get_permalink());

		$postid=$item->get_id();
		
		print $postid . "<br>";
		
		// print $item->get_date(false);
		$date=$item->get_date("Y-m-d H:i:s");
		
		if ($date == null) {
			$date = date("Y-m-d H:i:s", strtotime($channelPubDate));
		}

		print $cleartitle . "<br>\n";
		
		$itemsql="insert into ".$dbprefix."items values (
		0,
		'{$title}',
		'{$description}',
		'{$content}',
		'{$link}',
		'{$authorname}',
		'{$postid}',
		'{$date}',
		'{$feedid}')";
		$res=MySQL_query($itemsql) ||die (mysql_error());
		
		@ob_flush();
		@flush();
	}
	
	unset($feed);
}

?>
