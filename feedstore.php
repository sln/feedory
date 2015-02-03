<?php

// üäöß

// ini_set ("display_errors", "1");
// error_reporting(E_ALL);

print "<head>\n";
print "<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\">\n";
print "</head>\n";

require_once("config.php");
require_once("fetchfeed.php");
require_once("pageheader.php");
 
$result = mysql_query("select * from ".$dbprefix."feeds where active=1");

if ($result != FALSE && mysql_num_rows($result) > 0) {
	while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		print "<b>updating {$row['title']}</b><br>\n";
	
		fetchfeed($row['name'], $row['url']);
		
		@ob_flush();
	    @flush();
	}
} else {
	print "no subscribtions...\n";
}

?>
