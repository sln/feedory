<?php
header("Content-type: text/x-opml");
header('Content-Disposition: attachment; filename="feedory.opml"');

$source=$_REQUEST['source'];

require_once("opml/fy_opml_gen.php");

$opmlwriter = new FY_OPML_GEN($source);
$opmlwriter->genOPML();

?>
