<?php
/* I'm With Barkley (iwb)
 *
 *	General information file containing global data
 *	All iwb pages should require this file to load the desired data
 */

	require_once('barkleydata.php');
	
	$db_server = mysql_connect($db_hostname, $db_username, $db_password);
	if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
	mysql_select_db($db_database) or die("Unable to select database: " . mysql_error());
	
	//$post_id = mysql_real_escape_string($_GET['faceid']);
	$post_id = intval($_GET['faceid']);
	$post_link = mysql_real_escape_string($_GET['link']);
	
	// Check validity of submitted link
	$valid = '/^http:\/\/featherfiles.aviary.com/';
	
	if (preg_match($valid, $post_link) && $post_id) {
		// Look to see if any examples have already been uploaded by user
		$query = "SELECT `face_id` FROM examples WHERE face_id = ".$post_id;
		$result = mysql_query($query);
		if (!$result) die ("Database access failed: " . mysql_error());
		$row = mysql_fetch_row($result);
		
		if ($row) {
			$mysqldate = date('Y-m-d H:i:s');
			$update = "UPDATE examples SET sub_time = '".$mysqldate ."', link_loc = '".$post_link."' WHERE face_id = ".$post_id;
			echo $update;
			$result = mysql_query($update);
		}
		else {
		// if nothing found Insert link
			$mysqldate = date('Y-m-d H:i:s');
			$insert = "INSERT INTO examples (sub_time, link_loc, face_id) VALUES ('".$mysqldate."', '".$post_link."', '".$post_id."')";
			$result = mysql_query($insert);
		}
	}
?>