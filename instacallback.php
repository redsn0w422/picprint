<?php

/**
 * Instagram PHP API
 * 
 * @link https://github.com/cosenary/Instagram-PHP-API
 * @author Christian Metz
 * @since 01.10.2013
 */

require_once 'instagram.class.php';

// initialize class
$instagram = new Instagram(array(
	'apiKey'			=> '359a0cb55e014c2a853b77fed4769564',
	'apiSecret'	 => 'a03c4453898846abbdadca739b4c1dde',
	'apiCallback' => 'http://yashamostofi.com/drinkspls/insta/callback.php'
));

// receive OAuth code parameter
$code = $_GET['code'];

// check whether the user has granted access
if (isset($code)) {

	// receive OAuth token object
	$data = $instagram->getOAuthToken($code);
	$username = $username = $data->user->username;
	
	// store user access token
	$instagram->setAccessToken($data);

	// now you have access to all authenticated user methods
	$result = $instagram->getUserMedia();

} else {

	// check whether an error occurred
	if (isset($_GET['error'])) {
		echo 'An error occurred: ' . $_GET['error_description'];
	}

}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Instagram - photo stream</title>
		<link href="https://vjs.zencdn.net/4.2/video-js.css" rel="stylesheet">
		<script src="https://vjs.zencdn.net/4.2/video.js"></script>
		<script src="jquery.js"></script>
		<script src="quickprint_interface.js?3"></script>
	</head>
	<body>
		<div class="container">
			<header class="clearfix">
				<h1>Instagram photos <span>taken by <? echo $data->user->username ?></span></h1>
			</header>
			<div class="main">
				<ul class="grid">
				<?php
					echo "<form action=\"quickprint_launch.php\" method=\"post\">";
					// display all user uploads
					foreach ($result->data as $media)
					{
						$content = "<li>";
						
						// output media
						$image = $media->images->low_resolution->url;
						$url = $media->images->standard_resolution->url;
						$content .= "<input type=\"checkbox\" name=\"checklist[]\" value=\"{$url}\">";
						$content .= "<img class=\"media\" src=\"{$image}\"/>";
						
						// create meta section
						$avatar = $media->user->profile_picture;
						$username = $media->user->username;
						$comment = $media->caption->text;
						$content .= "<div class=\"content\">
													 <div class=\"comment\">{$comment}</div>
												 </div>";
						
						// output media
						echo $content . "</li>";
						print_r($media->images->standard_resolution->url);
					}
					echo "<input type=\"submit\">";
					echo "</form>";
				?>
				</ul>
			</div>
		</div>
	</body>
</html>
