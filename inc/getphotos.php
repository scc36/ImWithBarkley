<?php	// Use ceainfo's get member by state to get the list of members
	require_once('barkleydata.php');
	require_once('facebook.php');
	
	// GET commands
	$ab_id = ($_GET["album"]);
	
	$app = array(
		'appId'  => $app_id,
		'secret' => $app_secret,
	);
	$facebook = new Facebook($app);
	
	// Get User ID
	$user_id = $facebook->getUser();
	
	// Check if album id was found
	if (!$ab_id) {
		$user_id = null;
		echo 'Album ID not found -> ';
	}
	
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
		$get_loc = '/'.$ab_id.'/photos';
		$get_photos = $facebook->api($get_loc);
		$photos = $get_photos['data'];
	} else {
		// If no user found, create login URL and login
		echo 'Authentication failed -> ';
		$photos = null;
	}
	
	//print_r($photos);
	$gallery_width = 4;
	if ($photos) {
		$i = $gallery_width;
		echo '<h2>Now choose a photo</h2>';
		echo '<table><tr>';
		foreach ($photos as $photo) {
			
			echo '<td><input type="image" id="'.$photo['id'].'" src="'.$photo['picture'].'" value="Edit photo" onclick="launchEditor(\''.$photo['id'].'\', \''.$photo['source'].'\'); return false;" /></td>';
			$i--;
			
			if ($i === 0) {
				echo '</tr><tr>';
				$i = $gallery_width;
			}
		}
		echo '</tr><tr><td colspan="'.$gallery_width.'"></td></tr></table>';
	}
	else {
		echo 'No Photos Found';
	}
?>