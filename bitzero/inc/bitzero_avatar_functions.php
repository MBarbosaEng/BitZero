<?php
/*
Plugin Name: Avatars & Functions
Plugin filename: bitzero_avatar_functions.php
Version: 1.0.0
 
 
LICENSE

Copyright © 2014, M Barbosa <MBarbosaEng@EngBit.com.br> 
Twitter: @MBarbosaEng
http://www.EngBit.com.br/ 
All rights reserved.

Redistribution and  use in source forms, with or without modification, are permitted provided that the following conditions are met:
- Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer 
  in the documentation and/or other materials provided with the distribution.
- Neither the name “BitZero” or “EngBit” nor the names of its contributors may be used to endorse or promote products derived
  from this software without specific prior written permission.
- Commercial use of this software or source code must be communicated to the copyright holders and contributors.

This software is provided by the copyright holders and contributors “as is” and any express or implied warranties, including, but not limited to, 
the implied warranties of merchantability and fitness for a particular purpose are disclaimed. In no event shall the copyright owner or 
contributors be liable for any direct, indirect, incidental, special, exemplary, or consequential damages (including, but not limited to, 
procurement of substitute goods or services; loss of use, data, or profits; or business interruption) however caused and on any theory of liability,
whether in contract, strict liability, or tort (including negligence or otherwise) arising in any way out of the use of this software, 
even if advised of the possibility of such damage.
*/

/*-----------------------------------------------------------------------------------*/
/* Avatar
/*-----------------------------------------------------------------------------------*/
function getAvatar() {
	$default = avatar_default;	
	$avatares= array( 0 => "auto", 1 => "gravatar", 2 => "twitter", 3 => "facebook",4 => "tumblr", 5 => "google+" );
	foreach($avatares as $avatar){
		if($default == $avatar) { 
			echo '<option value="' . str_replace('+','',$avatar) .'" selected>' . strtoupper($avatar) . '</option>'; 
		} else {
			echo '<option value="' . str_replace('+','',$avatar) .'">' . strtoupper($avatar) . '</option>';
		}
	}
}


/*-----------------------------------------------------------------------------------*/
/* Get Profile Image
/*-----------------------------------------------------------------------------------*/
function get_myProfile_img($qImg,$qId){
        $qImg = trim($qImg);
        $qId = trim($qId);
	if ($qImg == '') { // need name
		return '';
	}
	if ($qId == '') { // need id
		return '';
	}	
	try {	
		if ($qId == null) {
			return '';
		}

		$image_url = $cache_image = '';
		// Set the cached profile image.
		switch($qImg) {
			case "gravatar":
					$cache_image =  './cache/gravatar.jpg';
					$image_url ="http://www.gravatar.com/avatar/" . md5(strtolower($qId)) . "&s=100";
					// to save image use this:
					//$grav_url = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?d=" . urlencode($default) . "&s=" . $size;
				break;
			case "facebook":
					$cache_image =  './cache/facebook_'.$qId.'.jpg';
					$image_url =  'https://graph.facebook.com/'.$qId.'/picture';			
				break;
			case "dtwitter":
					$cache_image =  './cache/twitter_d_'.$qId.'.jpg';
					$image_url = 'http://dropplets.com/profiles/?id='.$qId.'';					
				break;		
			case "twitter":
					return get_twitter_profile_imgV2($qId);					
				break;	
			case "tumblr":			
				$cache_image = './cache/tumblr_' . $qId . '.png';
				$image_url ="http://api.tumblr.com/v2/blog/" . $qId . ".tumblr.com/avatar";
				break;
			case "google":
					$cache_image = './cache/gplus_'.$qId.'.jpg';
					$image_url = 'https://profiles.google.com/s2/photos/profile/'.$qId;
				break;
		}

		if (!file_exists($cache_image)) {
            $image = file_get_contents($image_url);
            // sometimes have problems with windows - replace above for this:
            //$lurl = get_fcontent($image_url);
            //$image = $lurl[0];
     
			if (($image == "")||($image === FALSE)||(stripos($image,'404')>0)||(stripos($image,'status')>0)||(stripos($image,'meta')>0)||(stripos($image,'response')>0)) {
				return '';
			} else {
				// Cache the image if it doesn't already exist.
				file_put_contents($cache_image, $image);
                return $cache_image;
			} 
		}
	} catch (Exception $e) {
		$cache_image = '';
	}
	return $cache_image;
}

function get_fcontent( $url,  $javascript_loop = 0, $timeout = 5 ) {
// http://stackoverflow.com/questions/697472/file-get-contents-returning-failed-to-open-stream-http-request-failed
    $url = str_replace( "&amp;", "&", urldecode(trim($url)) );

    $cookie = tempnam ("/tmp", "CURLCOOKIE");
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_ENCODING, "" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    $content = curl_exec( $ch );
    $response = curl_getinfo( $ch );
    curl_close ( $ch );

    if ($response['http_code'] == 301 || $response['http_code'] == 302) {
        ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");

        if ( $headers = get_headers($response['url']) ) {
            foreach( $headers as $value ) {
                if ( substr( strtolower($value), 0, 9 ) == "location:" )
                    return get_url( trim( substr( $value, 9, strlen($value) ) ) );
            }
        }
    }

    if ( ( preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value) ) && $javascript_loop < 5) {
        return get_url( $value[1], $javascript_loop+1 );
    } else {
        return array( $content, $response );
    }
}

function get_gravatar_profile_img($gvImg){
	return get_myProfile_img("gravatar",trim($gvImg));
}
function get_tumblr_profile_img($tbImg){
	return get_myProfile_img("tumblr",trim($tbImg));
}
function get_facebook_profile_img($fImg) {
	return get_myProfile_img("facebook",trim($fImg));
}
function get_gplus_profile_img($gImg) {
	return get_myProfile_img("google",trim($gImg));
}
function get_twitter_profile_img($tImg) {
	return get_myProfile_img("dtwitter",trim($tImg));
}

function get_twitter_profile_imgV2($tImg) { // use new Twitter API
//As you rightly pointed out, as of June 11th 2013 you can't make unauthenticated requests, 
//or any to the 1.0 API any more, because it has been retired. 
//So OAuth is the way to make requests to the 1.1 API.
//https://dev.twitter.com/apps
//https://dev.twitter.com/docs/api/1.1/get/users/lookup
// The link below, get read-only access
// Give your application READ access, and hit "Update" at the bottom.
// http://stackoverflow.com/questions/12916539/simplest-php-example-for-retrieving-user-timeline-with-twitter-api-version-1-1/15314662#15314662
    $tImg = trim($tImg);
	if ($tImg == '') {
		return '';
	}	
	try {
		$twitter_image = '';
		if (!file_exists('./cache/twitter_'.$tImg.'.jpg')) {
			if ((TWITTER_TOKEN != "") || (TWITTER_TOKEN != "") || (TWITTER_TOKEN != "") || (TWITTER_TOKEN != "")) {
				$settings = array(
					'oauth_access_token' => TWITTER_TOKEN,
					'oauth_access_token_secret' => TWITTER_TSECRET,
					'consumer_key' => TWITTER_CKEY,
					'consumer_secret' => TWITTER_CSECRET
				);	
				/** URL for REST request, see: https://dev.twitter.com/docs/api/1.1/ **/
				$api_call = 'https://api.twitter.com/1.1/users/show.json';
				/** Perform a GET request and echo the response **/
				/** Note: Set the GET field BEFORE calling buildOauth(); **/
				$getfield = '?screen_name='.$tImg;
				$requestMethod = 'GET';
				/** Perform a POST request and echo the response **/
				$twitter = new TwitterAPIExchange($settings);
				$results = $twitter->setGetfield($getfield)->buildOauth($api_call,$requestMethod)->performRequest();			
				if (isset($results)) {
					$results = json_decode($results);
					if (isset($results)) {
						// Cache the image if it doesn't already exist.					
						$image = str_replace('_normal', '100', $results->profile_image_url);
						file_put_contents('./cache/twitter_'.$tImg.'.jpg', $image);
						// Set the cached profile image.
						$twitter_image =  './cache/twitter_'.$tImg.'.jpg';						
					}
				}
			}
		} else {
			// Get the cached profile image.
			$twitter_image =  './cache/twitter_'.$tImg.'.jpg';		
		}
	} catch (Exception $e) {
		$twitter_image = '';
	}
	// Return the image URL.
	return $twitter_image;
}
function get_profile_auto() {
	$imageProfile = '';
	$imgD = get_gravatar_profile_img(BLOG_EMAIL);
	if ($imgD == './cache/gravatar.jpg') {
		$imageProfile = $imgD;
	} else {	
		$imgD = get_twitter_profile_img(BLOG_TWITTER);
		if ($imgD == './cache/twitter_d_'.BLOG_TWITTER.'.jpg') {
			$imageProfile = $imgD;
		} else {	
			$imgD = get_twitter_profile_imgV2(BLOG_TWITTER);
			if ($imgD == './cache/twitter_'.BLOG_TWITTER.'.jpg') {
				$imageProfile = $imgD;
			} else {
				$imgD = get_facebook_profile_img(BLOG_FACEBOOK);
				if ($imgD == './cache/facebook_'.BLOG_FACEBOOK.'.jpg') {
					$imageProfile = $imgD;
				} else {
					$imgD = get_tumblr_profile_img(BLOG_TUMBLR);
					if ($imgD == './cache/tumblr_'.BLOG_TUMBLR.'.png') {
						$imageProfile = $imgD;
					} else {				
						$imgD = get_gplus_profile_img(BLOG_GOOGLEP);
						if ($imgD == './cache/gplus_'.BLOG_GOOGLEP.'.jpg') {
							$imageProfile = $imgD;
						}	
					}						
				}		
			}
		}
	}
	if ($imageProfile == '') {
		$imageProfile = './cache/dropplets.jpg';
	}	
	if (file_exists($imageProfile)) {
		return BLOG_URL.str_replace(array(FILE_EXT, './'), '',$imageProfile);
	}
}

function get_profile_img() {
    $imgD = $imgDc = '';
	switch(avatar_default) {
		case "auto":
				return get_profile_auto();
			break;
		case "gravatar":
				$imgD = get_gravatar_profile_img(BLOG_EMAIL);
				$imgDc = './cache/gravatar.jpg';
			break;		
		case "twitter":
				$imgD = get_twitter_profile_img(BLOG_TWITTER);
				$imgDc = './cache/twitter_d_'.BLOG_TWITTER.'.jpg';
			break;
		case "facebook":
				$imgD = get_facebook_profile_img(BLOG_FACEBOOK);
				$imgDc = './cache/facebook_'.BLOG_FACEBOOK.'.jpg';
			break;
		case "tumblr":
				$imgD = get_tumblr_profile_img(BLOG_TUMBLR);
				$imgDc = './cache/tumblr_'.BLOG_TUMBLR.'.png';
			break;
		case "google":
				$imgD = get_gplus_profile_img(BLOG_GOOGLEP);
				$imgDc = './cache/gplus_'.BLOG_GOOGLEP.'.jpg';
			break;	
		default:
				return get_profile_auto();
			break;		
	}
	$imageProfile = '';
	if ($imgD == $imgDc) {
		$imageProfile = $imgD;
	}	
	if ($imageProfile == '') {
		$imageProfile = './cache/dropplets.jpg';
	}	
	if (file_exists($imageProfile)) {
		return BLOG_URL.str_replace(array(FILE_EXT, './'), '',$imageProfile);
	}
}

function get_post_img($qtwitterid) {
    $qtwitterid = trim($qtwitterid);
   if ( $qtwitterid == '') {
        return get_profile_img();
   } else {
        $imageProfile = '';
   		$imgD = get_twitter_profile_img($qtwitterid);
		if ($imgD == './cache/twitter_d_'.$qtwitterid.'.jpg') {
			$imageProfile = $imgD;
		} else {	
			$imgD = get_twitter_profile_imgV2($qtwitterid);
			if ($imgD == './cache/twitter_'.$qtwitterid.'.jpg') {
				$imageProfile = $imgD;
			}
        }
        if ($imageProfile == '') {
            $imageProfile =  get_profile_img();
        }	
        if (file_exists($imageProfile)) {
            return BLOG_URL.str_replace(array(FILE_EXT, './'), '',$imageProfile);
        }
   }
}

/*-----------------------------------------------------------------------------------*/
/* Functions
/*-----------------------------------------------------------------------------------*/
function getMarkdownType(){
	if(markdownType == "js") { 
		echo '<option value="js" selected>JS Marked</option>';
        echo '<option value="mkd">JS MarkDownDeep</option>';
        echo '<option value="php">PHP MarkDown</option>'; 
        echo '<option value="pme">PHP MarkDown Extended</option>';
        echo '<option value="ppd">PHP ParseDown</option>';          
	} else if(markdownType == "mkd") { 
		echo '<option value="js">JS Marked</option>';
        echo '<option value="mkd" selected>JS MarkDownDeep</option>';
        echo '<option value="php">PHP MarkDown</option>'; 
        echo '<option value="pme">PHP MarkDown Extended</option>';
        echo '<option value="ppd">PHP ParseDown</option>';  
	} else if(markdownType == "php") { 
		echo '<option value="js">JS Marked</option>'; 
        echo '<option value="mkd">JS MarkDownDeep</option>';        
		echo '<option value="php" selected>PHP MarkDown</option>';
        echo '<option value="pme">PHP MarkDown Extended</option>';          
        echo '<option value="ppd">PHP ParseDown</option>';  
	} else if(markdownType == "pme") { 
		echo '<option value="js">JS Marked</option>'; 
        echo '<option value="mkd">JS MarkDownDeep</option>';        
		echo '<option value="php">PHP MarkDown</option>';
        echo '<option value="pme" selected>PHP MarkDown Extended</option>';          
        echo '<option value="ppd">PHP ParseDown</option>';  
	} else {
		echo '<option value="js">JS Marked</option>'; 
        echo '<option value="mkd">JS MarkDownDeep</option>';        
		echo '<option value="php">PHP MarkDown</option>';
        echo '<option value="pme">PHP MarkDown Extended</option>';          
        echo '<option value="ppd" selected>PHP ParseDown</option>';  
	}
}

function getLanguages() {
	if(isset($_COOKIE['i18nLanguage'])) { 
		$default = trim($_COOKIE['i18nLanguage']); 
	} else {
		$default = language_default;
	}
	$local = './bitzero/plugins/locale/';
	foreach(glob($local . '*.po') as $lng){
		$lng = str_replace('.po','',str_replace($local,'',$lng));
		if($default == $lng) { 
			echo '<option id="' . $lng . '" value="' . $lng .'" selected>' . _t(str_replace('_','',$lng)) . '</option>'; 
		} else {
			echo '<option id="' . $lng . '" value="' . $lng .'">' . _t(str_replace('_','',$lng)) . '</option>';
		}
	}
}

function showHideTemplates() {
		if(SHOW_MARKET == "0") { 
			echo '<option value="0" selected>' . _t('Hide Templates') . '</option>';
            echo '<option value="1">' . _t('Show Templates') . '</option>';            
		} else {
			echo '<option value="0">' . _t('Hide Templates') . '</option>';        
			echo '<option value="1" selected>' . _t('Show Templates') . '</option>';
		}
}
?>