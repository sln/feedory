<?php

// üäöß

require_once("pageheader.php");
require_once("config.php");

$version=0.4;
?>

<body>

<table border=0 width=100% height=100%>

<tr><td>
<img src="images/logo.png">
<span style="margin-left:300px;margin-bottom:40px;"><a title="drag this bookmarklet to your bookmark bar to subscribe to feeds on the fly" href="javascript:location.href='<?php print "http://" . $_SERVER['SERVER_NAME'] . str_replace("index.php", "subscribe.php", $_SERVER['SCRIPT_NAME']) . "?source=bm&feedurl='+encodeURIComponent(location.href);void(0);" ?>">add2feedory</a></span>
</tr>

<tr class="headerbar"><td valign="middle">
<span class="barheader">Feed management
&nbsp;&nbsp;&nbsp;
<a target="feedlist" title="Reload your feed list" href="feedlist.php"><img src="images/reload.png" height= noborder border=0></a>
&nbsp;&nbsp;&nbsp;
<a target="feedlist" title="Update all feeds" href="feedstore.php"><img src="images/update.png" noborder border=0></a>
&nbsp;&nbsp;&nbsp;
<a target="feedlist" title="OPML Import &amp; Export feeds" href="opml.php"><img src="images/opml.png" noborder border=0></a>
</span>
</tr>

<tr height="100%"><td valign="top" align="middle">
<iframe width="80%" src="feedlist.php" height=100% frameborder=0 name="feedlist">
</iframe>
</tr>

<tr><td>
<tr class="headerbar"><td valign="middle">
<span class="barheader">Feed Subscription</span>
</tr>

<tr><td>
<table>
<form method="post" action=subscribe.php target="feedlist">
<tr><td>Feed URL<td><input type="text" name="feedurl" size=100></tr>
<tr><td colspan=2><input type="submit" value="Subscribe"></tr>
</form>
</table>
</tr>

<tr><td>
<hr size=1>
<a href="http://www.feedory.de">feedory</a> <?php print $version; ?> - <a href="about.php" target="feedlist">About</a>
</tr>
</table>
