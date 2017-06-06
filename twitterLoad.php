<?php
SESSION_START();
require_once __DIR__ . '/vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
use Facebook\Facebook;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

/*
Plugin Name: Twitter Media Plugin
Description: This plugin will display the five most recent tweets from the users twitter account, and displays them on their wordpress page. 
Creation Date: May 2016
Author: Sean Legge
Author URI: http://www.sean-legge.com
Version: 0.1
Date Update: June 5th 2017
Updates: 
June 5th 2016: Improved on comments in comments in the project. 


*/
//-------------------------------------------
//Admin Functions
//This allows you to create a shortcode to be used to call the function on the 
//wordpress site.
add_shortcode('socialMedia', 'displaySocialMedia');
//This function will add a custom menu
//Parameters Order: Page title, menu title, capabilities, slug, function
function add_socialMedia_menu(){
	add_menu_page("Social Media Options", "Social Media Options", "manage_options", "Social Media Options", "show_socialMedia_options");
}

function displaySocialMedia(){
//Call the twitter function
twitter_Work();
}
//The Function that handles the twitter side of the app.
function twitter_Work(){
//Connect to the twitter account
$connection = new TwitterOAuth("kabwu8NJNGmmLeIu9e8waLXCi", "5qjX7zDHsX8DGUZkDb7yS1faz7MSztcWGM8YLf1VuP9CFl3qIx", "728073377982767104-S88g9SKypE9dNXspSF8qE0sAN4fZsPK", "GbzTdz3iAeve5Io6VYqN3luKZivMPZnuKUoHDvRRUNFdZ");
//Post saying that we are past the authentication
//Get the verify credentials
$content = $connection->get("account/verify_credentials");
//Get the screen name
$screenName = $content->screen_name;
//Get the profile picture
$userImage = $content->profile_image_url_https;

//Get the twitter timeline of the user.
$tweets = $connection->get('statuses/user_timeline', array('screen_name' => $screenName, 'count' => 5));


//While there are sweets print them out.
if(!empty($tweets)) {
	foreach($tweets as $tweet){
		
		$tweetText = $tweet->text;
		$tweetSource = $tweet->source;
		$tweetExpand = $tweet->expanded_url;

        # Output the tweets to the website. 
		echo $tweetExpand;
		?>
			<html>
			<head></head>
			<body>		
			<article>
            	<aside style="border: solid; padding-bottom: 50px; background-color: gray; height: auto;">
				<h2 style="float: right; margin: 5px; font-size: 10px;" ><?php echo $tweet->created_at; ?></h2>
				<p style="float: left; margin: 5px;">
						<img src=<?php echo $userImage; ?> style="border: solid; height: 75px; width: auto;">
				</p>		
				<br>
				<p style="font-weight: bold; margin: 0 auto;" ><?php echo $tweet->text; ?></p>
				
            	</aside>
			<br>
          		<br>
        		</article>
			</body>
		</html>
		<?php
		echo "\n";
	}//End of foreach
   }//End of If 
}//End of TwitterWork()
function show_socialMedia_page(){
?>
<form id="socialMediaMain" method="POST" action="page=socialMedia+Main">
<h2>Social Media Main</h2>

</form>
<?php
}
//Display admin page
function show_socialMedia_options(){	
?>
<!-- These options don't have the code to get specific items, ran out of time.-->
<form id="socialMedia-form" method="POST" action="/admin.php?page=socialMedia+Options">
	<h2>Social Media Options</h2>
</form>
<?php	
}
//Add an action hook
add_action('admin_menu', 'add_socialMedia_menu');
?>