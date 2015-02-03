
<?php

require_once("pageheader.php");

if (isset($_FILES['userfile']['tmp_name'])) {

	if($_FILES['userfile']['tmp_name']=="") {
		print "Please enter filename\n";
		exit;
	}

	require_once("addfeed.php");

	require_once('opml/iam_opml_parser.php');

	$parser = new IAM_OPML_Parser();
	$feeds=$parser->getFeeds("{$_FILES['userfile']['tmp_name']}");
	// print_r($feeds);
	
	foreach($feeds as $feed) {
		print "<p><i>OPML-Title</i> " . $feed['names'] . "<br>\n";
		if ($feed['feeds'] != "") {
			addfeed($feed['feeds']);
		} else {
			print "empty url for feed<p>\n";
		}
	}
	
} else {

?>

<div class="header">OPML import</div>

<form enctype="multipart/form-data" action="opml.php" method="POST">
    OPML file: <input name="userfile" type="file" />
    <input type="submit" value="Import OPML data" />
</form>

<div class="header">OPML export</div>
<a href="export.php?source=feedory">Export my feedory feeds as OPML</a><p>
<a href="export.php?source=orig">Export the original feeds from feedory as OPML</a>

<?php

}

?>
