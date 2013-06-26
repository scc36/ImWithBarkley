<?php
/* I'm With Barkley (iwb)
 *
 *	Page where users can select a photo and edit it using Aviary
 */
	require_once('inc/barkleydata.php');
	require_once('inc/facebook.php');
	
       // Get error
	$error = ($_GET["error"]);
	if ($error) { // access_denied
		echo 'Facebook authentication failed';
		header("Location:{$web_home}");
	}
	
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
		$get_albums = $facebook->api('/me/albums');
		$albums = $get_albums['data'];
		$access_token = $facebook->getAccessToken();
	} else {
		// If no user found, create login URL and login
		$loginUrl = $facebook->getLoginUrl(array('scope' => "publish_stream,user_photos"));
		header("Location:{$loginUrl}");
		//echo("<script>top.location.href='".$loginUrl."'</script>");
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
	<script src="/javascripts/getphotos.js" type="text/javascript"></script>
	<script src="http://connect.facebook.net/en_US/all.js" type="text/javascript"></script>

<script type="text/javascript">
    var _featherLoaded = false;

    Feather_APIKey = '<?php echo $av_key ?>';
    Feather_Theme = 'red';
    Feather_EditOptions = 'stickers';
    Feather_OpenType = 'lightbox';
    Feather_CropSizes = '320x240,640x480,800x600,1280x1024';
    Feather_Stickers = [
	["http://imwithbarkley.com/stickers/Sticker_01.png","http://imwithbarkley.com/stickers/Sticker_01.png"],
	["http://imwithbarkley.com/stickers/Sticker_02.png","http://imwithbarkley.com/stickers/Sticker_02.png"],
	["http://imwithbarkley.com/stickers/Sticker_03.png","http://imwithbarkley.com/stickers/Sticker_03.png"],
	["http://imwithbarkley.com/stickers/Sticker_04.png","http://imwithbarkley.com/stickers/Sticker_04.png"],
	["http://imwithbarkley.com/stickers/Sticker_05.png","http://imwithbarkley.com/stickers/Sticker_05.png"],
	["http://imwithbarkley.com/stickers/Sticker_06.png","http://imwithbarkley.com/stickers/Sticker_06.png"],
	["http://imwithbarkley.com/stickers/Sticker_07.png","http://imwithbarkley.com/stickers/Sticker_07.png"],
	["http://imwithbarkley.com/stickers/Sticker_08.png","http://imwithbarkley.com/stickers/Sticker_08.png"],
	["http://imwithbarkley.com/stickers/Sticker_09.png","http://imwithbarkley.com/stickers/Sticker_09.png"],
	["http://imwithbarkley.com/stickers/Sticker_10.png","http://imwithbarkley.com/stickers/Sticker_10.png"],
	["http://imwithbarkley.com/stickers/Sticker_11.png","http://imwithbarkley.com/stickers/Sticker_11.png"],
	["http://imwithbarkley.com/stickers/Sticker_12.png","http://imwithbarkley.com/stickers/Sticker_12.png"],
	["http://imwithbarkley.com/stickers/Sticker_13.png","http://imwithbarkley.com/stickers/Sticker_13.png"],
	["http://imwithbarkley.com/stickers/Sticker_14.png","http://imwithbarkley.com/stickers/Sticker_14.png"],
	["http://imwithbarkley.com/stickers/Sticker_15.png","http://imwithbarkley.com/stickers/Sticker_15.png"],
	["http://imwithbarkley.com/stickers/Sticker_16.png","http://imwithbarkley.com/stickers/Sticker_16.png"],
    ];

    Feather_OnSave = function(id, url) {
        var e = document.getElementById(id);
        //e.src = url;
        aviaryeditor_close();
        if (url) {
            window.location = "sharejoy.php?url=" + url;
        }
        else {
            alert ('Apologies, Aviary did not send us the location of your new image, so please apply Barkley again');
        }
    }

    Feather_OnLoad = function() {
        _featherLoaded = true;
    }

    function launchEditor(imageid, loc) {
        if (_featherLoaded) {
            aviaryeditor(imageid, loc);
        }
    }
</script>
<script type="text/javascript" src="http://feather.aviary.com/js/feather.js"></script>

</head>

<body>

<div id='blankcontent'>
<img src='images/slapButton.png'>
<h2>Choose an Album</h2>
<div id='myalbums'>
<?php
	$gallery_width = 5;
	//print_r($user);
	//print_r($albums);
	if ($albums) {
		$i = $gallery_width;
		echo '<table><tr><td colspan="'.$gallery_width.'"></td></tr><tr>';
		foreach ($albums as $album) {
			echo '<td><a href="javascript:void(0)" onclick="getPhotos(\''.$album['id'].'\')">';
			echo '<img src="https://graph.facebook.com/'.$album['cover_photo'].'/picture?type=thumbnail&access_token='.$access_token.'" alt="'.$album['id'].'" />';
			echo '</a><br>'.$album['name'];
			echo '<br>'.$album['count'].' photos';
			echo '</td>';
			$i--;
			
			if ($i === 0) {
				echo '</tr><tr>';
				$i = $gallery_width;
			}
		}
		echo '</tr></table>';
	}
	else {
		echo '<h3> Sorry, we were unable to find any Facebook albums</h3>';
	}
?>
</div>
<hr>
<div id="myphotos"></div>

<?php require('inc/template_footer.php'); ?>

</div> 

</body> 
</html>