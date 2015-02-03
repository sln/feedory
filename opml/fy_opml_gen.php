<?php

/*
	FY_OPML_GEN - OPML generator for feedory 0.1
	(C) 2009 SLN
	www.feedory.de
*/

class FY_OPML_GEN {

	var $source;

	function FY_OPML_GEN($source){
		$this->source=$source;
	}
	
	function genOPML() {

		require_once("config.php");
		
		$feedsql=mysql_query("select * from ".$dbprefix."feeds");

		print "<opml version=\"1.1\">
	<head>
		<title>feedory OPML export - source " . $this->source . "</title>
		<dateModified>" . date("r") . "</dateModified>
	</head>
	<body>\n";
		
		while ($feedrow=mysql_fetch_array($feedsql)) {
			if ($this->source=="feedory") {
				$feedurl=$basehost . $baseurl . "gate.php?feed=" . $feedrow['name'];
			} else {
				$feedurl=$feedrow['url'];
			}
			
			print "\t\t<outline text=\"{$feedrow['title']}\" title=\"{$feedrow['title']}\" type=\"rss\" xmlUrl=\"{$feedurl}\" htmlUrl=\"{$feedrow['link']}\"/>\n";
		}

		print "\t</body>
</opml>\n";

	}
}

?>
