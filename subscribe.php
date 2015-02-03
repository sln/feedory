<?php

// üäöß

print "<head>\n";
print "<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\">\n";
print "</head>\n";

if (isset($_REQUEST['source']) && $_REQUEST['source'] == "bm") {
	print "<img src=\"images/logo_small.png\"><p>\n";
}

require_once("fetchfeed.php");
require_once("addfeed.php");

$feedurl=$_REQUEST['feedurl'];
$feedname=md5($feedurl);

if (addfeed($feedurl)) {
	print "<i>Successfully subscribed to feed, now trying to update feed items...</i>\n<p>";
	fetchfeed($feedname, $feedurl);
}

print "<p><a target=\"_top\" href=\"index.php\">Return to feed list...</a>\n";
if (isset($_REQUEST['source']) && $_REQUEST['source'] == "bm") {
	print "or <a href=\"" . $_REQUEST['feedurl'] . "\">return to where you were coming from</a>...\n";
}

?>
