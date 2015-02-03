<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>feedory - personal feed memory</title>
<link rel="stylesheet" type="text/css" href="../style.css">
<link rel="shortcut icon" href="../images/favicon.png">
</head>

<body>

<table width=100% height=100% border=0>
<tr>
<td align="center" valign="middle">
<div style="text-align: left;width:75%">

<?php

if (isset($_REQUEST['dbhost'])) {
	$dbhost=$_REQUEST['dbhost'];
} else {
	$dbhost="";
}

if (isset($_REQUEST['db'])) {
	$db=$_REQUEST['db'];
} else {
	$db="";
}


if (isset($_REQUEST['dbport'])) {
	$dbport=$_REQUEST['dbport'];
} else {
	$dbport="3306";
}

$dbprefix="fy_";


if (isset($_REQUEST['dbuser'])) {
	$dbuser=$_REQUEST['dbuser'];
} else {
	$dbuser="";
}


if (isset($_REQUEST['dbpass'])) {
	$dbpass=$_REQUEST['dbpass'];
} else {
	$dbpass="";
}

if (isset($_REQUEST['appurl'])) {
	$appurl=$_REQUEST['appurl'];
} else {
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
		$scheme="https://";
	} else {
		$scheme="http://";
	}
	$apppath=substr_replace($_SERVER['SCRIPT_NAME'], "", -17);
	$appurl=$scheme . $_SERVER['HTTP_HOST'] . $apppath;
}

?>


<img src="../images/logo.png">

<div class="header">Installation</div>

<div class="header2">Database</div>
The database you plan to use must already exist. Install routine does not try to create the database.<p>

<form name="dbcheck" action="index.php" method="post">
<input type="hidden" name="action" value="checkinstall">
<table>
<tr>
<td>Database host:<td><input type="text" name="dbhost" value="<?php print $dbhost; ?>">
</tr>
<tr>
<td>Database port:<td><input type="text" name="dbport" value="<?php print $dbport; ?>">
</tr>
<tr>
<td colspan=2>&nbsp;
</tr>
<tr>
<td>Database name:<td><input type="text" name="db" value="<?php print $db; ?>">
</tr>
<tr>
<td>Database user:<td><input type="text" name="dbuser" value="<?php print $dbuser; ?>">
</tr>
<tr>
<td>Database password:<td><input type="password" name="dbpass" value="<?php print $dbpass; ?>">
</tr>
<tr>
<td colspan=2>&nbsp;
</tr>
<tr>
<td>URL of your installation:<br><small>Is this correct?</small><td><input type="text" size=60 name="appurl" value="<?php print $appurl ?>">
</tr>

<tr>
<td><td><input type="submit" value="Install">
</tr>
</table>

</form>

<?php

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "checkinstall") {

	print "<div class=\"messageblock\">\n";

	$link=@MySQL_connect($dbhost . ":" . $dbport, $dbuser, $dbpass);
	
	$error=0;
	
	if (! $link) {
		print "<div class=\"error\">No connection possible to given database server with given user/password!</div>\n";
		$error=1;
	}

	if(! @MySQL_select_db($db,$link)) {
		print "<div class=\"error\">Error using the given database!</div>\n";
		$error=1;
	}
		
	if (! $error) {
		$cfile="../config.php";
		if (! is_writable($cfile)) {
			print "<div class=\"error\">Trying to write config file, but it's not writable!</div>\n";
			print "Please try to set write access to config.php\n";
			die();
		} else {
		
			$urlparts=parse_url($appurl);
			$cbasehost=$urlparts['scheme'] . "://" . $urlparts['host'];
			$cbaseurl=$urlparts['path'];
			
			if (substr($cbaseurl, -1) != "/") {
				$cbaseurl .= "/";
			}
			
			$cstring .= "<?php\n\n";
			$cstring .= "\$link=MySQL_connect(\"{$dbhost}:{$dbport}\", \"{$dbuser}\", \"{$dbpass}\");
MySQL_select_db(\"{$db}\");
\$dbprefix=\"{$dbprefix}\";
\$basehost=\"{$cbasehost}\";
\$baseurl=\"{$cbaseurl}\"\n";
			$cstring.="\n?>\n";
		
			$cf=fopen($cfile, w);
			fwrite($cf, $cstring);
			fclose($cf);
		}
	
		$cachedir="../cache";
		if (! is_writable($cachedir)) {
			print "<div class=\"error\">/cache is not writable, it's needed for the feedfetcher!</div>\n";
			print "Please try to set write access to /cache\n";
			die();
		}
		
		print "<div class=\"success\">DB connection successful!</div>\n";
		
		if (! mysql_query(file_get_contents("feedory.sql"), $link)) {
			print "<div class=\"error\">Error creating feeds table!</div>\n";
			die(mysql_error());
		} 

		if (! mysql_query(file_get_contents("feedory2.sql"), $link)) {
			print "<div class=\"error\">Error creating items table!</div>\n";
			die(mysql_error());
		} 

		print "<div class=\"success\">Tables successfully created!</div>\n";
		
		print "Go on to your <a href=\"..\" target=\"_top\">fresh installation</a>...<p>\n";
		print "(please delete your install directory)\n";

	}
	
	print "</div>\n";
}

// TODO
// info zu first steps
// sec warning about install.php to delete
// link auf php info
// green check icons, if things go right and red if went wrong

?>

</div>
</td>
</tr>
</table>

</body>
