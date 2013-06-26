<?php
/* I'm With Barkley (iwb)
 *
 *	Examples Page
 */

	require_once('inc/barkleydata.php');
	
	$db_server = mysql_connect($db_hostname, $db_username, $db_password);
	if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
	mysql_select_db($db_database) or die("Unable to select database: " . mysql_error());
	
	$post_start = intval($_POST['start']);
	$num_examples = 10;
	
	if ($post_start) {
		$query = "SELECT `link_loc` FROM examples ORDER BY `sub_time` DESC LIMIT ".$post_start.", ".$num_examples;
	}
	else {
		$query = "SELECT `link_loc` FROM examples ORDER BY `sub_time` DESC LIMIT ".$num_examples;
	}
	$result = mysql_query($query);
	if (!$result) die ("Database access failed: " . mysql_error());
	$rows = mysql_num_rows($result);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US"> 
<head> 
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="description" content="I'm with Barkley: Everything's better when you're with Barkley" />
	
	<title>I'm With Barkley</title>
	<link href="/stylesheets/iwb.css" media="screen" rel="stylesheet" type="text/css" />
	<script src="/javascripts/googleanalytics.js" type="text/javascript"></script>
	<script src="http://connect.facebook.net/en_US/all.js" type="text/javascript"></script>
</head>

<body>

<div id='blankcontent'>
<a href="/index.html"><img src='images/homeButton.png' alt='Home Page'></a>
<h2>Examples of I'm With Barkley</h2>

<div id='examplegallery'>
<?php
	if ($rows) {
		for ($i = 0; $i < $rows; $i++){
			$row = mysql_fetch_row($result);
			echo "<img src='".$row[0]."'> <hr>";
		}
	}
	else {	// Use generic pictures if no links found
		echo "<img src='images/After_1.jpg'> <hr><img src='images/After_2.jpg'> <hr><img src='images/After_3.jpg'> <hr>";
	}
?>
</div>

<?php require('inc/template_footer.php'); ?>

</div> 
</body>
</html>