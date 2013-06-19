<?php

define( 'GRAVATAR_EMAIL', 'YOUR_EMAIL_ADDRESS' );
define( 'GRAVATAR_CACHE_TIME', 60 * 60 * 24 ); // Cache for 1 day
define( 'GRAVATAR_CACHE_FILE', 'gravatar-profile.json' );

if ( file_exists( GRAVATAR_CACHE_FILE ) && ( time() - filemtime( GRAVATAR_CACHE_FILE ) ) < GRAVATAR_CACHE_TIME ) {
	$json = file_get_contents( GRAVATAR_CACHE_FILE );
	$gravatar_profile = json_decode( $json );
} else {
	$hash = md5( strtolower( trim( GRAVATAR_EMAIL ) ) );
	$json = file_get_contents( sprintf( 'https://www.gravatar.com/%s.json', $hash ) );
	file_put_contents( GRAVATAR_CACHE_FILE, $json );
	$gravatar_profile = json_decode( $json );
}

if ( empty( $gravatar_profile ) ) exit( 'Gravatar profile was not found.' );
$gravatar_profile = $gravatar_profile->entry[0];

$services = array(
	'blogger' => 'Blogger',
	'facebook' => 'Facebook',
	'flickr' => 'Flickr',
	'foursquare' => 'Foursquare',
	'friendfeed' => 'FriendFeed',
	'goodreads' => 'Goodreads',
	'google' => 'Google',
	'linkedin' => 'LinkedIn',
	'posterous' => 'Posterous',
	'tripit' => 'TripIt',
	'tumblr' => 'Tumblr',
	'twitter' => 'Twitter',
	'vimeo' => 'Vimeo',
	'wordpress' => 'WordPress',
	'yahoo' => 'Yahoo',
	'youtube' => 'YouTube',
);
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $gravatar_profile->displayName; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" user-scalable="no" >
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body<?php echo empty( $gravatar_profile->profileBackground ) ? ' class="no-background"' : ''; ?>>

	<div id="container" class="h-card vcard">

		<?php if ( !empty( $gravatar_profile->profileBackground ) ) : ?>
			<div id="cover" style="background-image:url( <?php echo $gravatar_profile->profileBackground->url; ?> );"></div>
		<?php endif; ?>

		<div id="profile">

			<div id="bio" class="section">
				<img class="u-photo photo" height="80" width="80" src="<?php echo $gravatar_profile->thumbnailUrl; ?>?s=160" />
				<h1 class="p-name fn"><?php echo $gravatar_profile->displayName; ?></h1>
				<p class="p-note"><?php echo nl2br( $gravatar_profile->aboutMe ); ?></p>
			</div>

			<?php if ( !empty( $gravatar_profile->accounts ) ) : ?>
				<ul id="accounts" class="section">
					<?php foreach ( $gravatar_profile->accounts as $account ) : ?>
						<li class="<?php echo $account->shortname; ?>"><a class="u-url url" rel="me" href="<?php echo $account->url; ?>"><?php echo array_key_exists( $account->shortname, $services ) ? $services[ $account->shortname ] : $account->shortname; ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

		</div>
	</div>

</body>
</html>
