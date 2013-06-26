<?php
/* I'm With Barkley (iwb)
 *
 *	Results Page where users can share their creations
 */
	require_once('inc/barkleydata.php');
	require_once('inc/facebook.php');
	
       // Get error
	$error = ($_GET["error"]);
	if ($error) { // access_denied
		echo 'Facebook authentication failed';
		header("Location:{$web_home}");
	}

	$new_joy = ($_GET["url"]);
	$iwb_status = ($_GET["iwb_status"]);
	
	$app = array(
		'appId'  => $app_id,
		'secret' => $app_secret,
	);
	$facebook = new Facebook($app);
	
	// Get User ID
	$user_id = $facebook->getUser();
	
	if ($user_id) { // Found authenticated user
		try {
			$user = $facebook->api('/me');
		} catch (FacebookApiException $e) {
			error_log($e);
			$user = null;
		}
	}
	
	// If user found, retrieve albums
	if ($user_id) {
		$logoutUrl = $facebook->getLogoutUrl();
		$access_token = $facebook->getAccessToken();
		
		// Show photo upload form to user and post to the Graph URL
		$photo_url= "https://graph.facebook.com/me/photos?"."access_token=" .$access_token;
	
		// Show photo upload form to user and post to the Graph URL
		$post_url= "https://graph.facebook.com/me/feed?"."access_token=" .$access_token;
	} else {
		// If no user found, create login URL and login
		$loginUrl = $facebook->getLoginUrl(array('scope' => "publish_stream,user_photos"));
		header("Location:{$loginUrl}");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US"> 
<head> 
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="description" content="I'm with Barkley: Everything's better when you're with Barkley" />
	
	<title>I'm With Barkley</title>
	<link href="/stylesheets/iwb.css" media="screen" rel="stylesheet" type="text/css" />
	<script src="/javascripts/jquery.min" type="text/javascript"></script> 
	<script src="/javascripts/jquery_ujs.js" type="text/javascript"></script> 
	<script src="/javascripts/application.js" type="text/javascript"></script>

	<script src="/javascripts/googleanalytics.js" type="text/javascript"></script>
	<script src="/javascripts/pictureswap.js" type="text/javascript"></script>
	<script src="http://connect.facebook.net/en_US/all.js" type="text/javascript"></script>

<script type="text/javascript">
// AJAX javascript to post to Facebook
function postFeed() {
	if (window.XMLHttpRequest) { // IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else { // IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
	}
	xmlhttp.open("POST","<?php echo $post_url; ?>",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");

	var picture=encodeURIComponent(document.getElementById("picture").value);	
	var caption=encodeURIComponent(document.getElementById("caption").value);
	var name=encodeURIComponent(document.getElementById("name").value);
	var link=encodeURIComponent(document.getElementById("link").value);
	//xmlhttp.send("caption="+caption+"&name="+name+"&link="+link);
	xmlhttp.send("caption="+caption+"&name="+name+"&link="+link+"&picture="+picture);
	alert ('Post sent to Facebook');
}
</script>

<script type="text/javascript">
// AJAX javascript to post to the Examples
function saveExample() {
	if (window.XMLHttpRequest) { // IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else { // IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
	}
	var link=encodeURIComponent(document.getElementById("link").value);
	xmlhttp.open("GET","inc/manage_examples.php?faceid=<?php echo $user_id ?>&link="+link);
	xmlhttp.send();
	alert ('Image sent to Examples');
}
</script>
</head>

<body>

<div id='blankcontent'>
<h2>Your improved memory</h2>

<span id='status'>
<?php
	if ($iwb_status) {
		echo '<h4>'.$iwb_status.'</h4>';
	}
?>
</span>

<div id='choosepanel'>
<img src="<?php echo $new_joy?>" alt="A better picture"/>
</div>

<form enctype="multipart/form-data" action="#" method="POST">
<?php
	//echo '<input id = "link" name="link" type="hidden" value="www.imwithbarkley.com">';
	echo '<input id = "link" name="link" type="hidden" value="'.$web_home.'">';
	echo '<input id = "picture" name="picture" type="hidden" value="'.$new_joy.'">';
	echo '<input id = "name" name="name" type="hidden" value="This Memory: Better With Barkley">';
	echo '<input id = "caption" name="caption" type="hidden" value="Now it\'s never been easier to add Charles Barkley to your treasured memories. Make your own at imwithbarkley.com">';
?>
	<table><tr>
	<h4>Share your creation on Facebook</h4>
	
	<input type="image" src="/images/wallButton.png" value="Post to Facebook" onclick="postFeed()"; return false;" />
	</tr>
	<hr>
	<tr>
	<h4>Submit your creation as an I'm With Barkley Example</h4>
	<input type="image" src="/images/exampleButton.png" value="Save Example" onclick="saveExample()"; return false;" />
	</tr></table>
</form>
<hr>
<div id='choose'>
	<table><tr>
	<td><a href="/addbarkley.php"><img src='images/photoButton.png' alt='New Picture'></a></td>
	<td><a href="/index.html"><img src='images/homeButton.png' alt='Home Page'></a></td>
	</tr></table>
</div>

<?php require('inc/template_footer.php'); ?>

</div> 
</body>
</html>