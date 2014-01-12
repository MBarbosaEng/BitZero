<?php

session_start();

// File locations.
$settings_file = "../config.php";
$htaccess_file = "../.htaccess";
$phpass_file   = '../dropplets/includes/phpass.php';

// encode/decode config variables 
include('../dropplets/encdec.php');

$dir = '';

// Get existing settings.
if (file_exists($settings_file)) {
    include($settings_file);
}
if (file_exists($phpass_file))
{
    include($phpass_file);
    $hasher  = new PasswordHash(8,FALSE);
}
function settings_format($name, $value) {
    return sprintf("\$%s = \"%s\";", $name, $value);
}

/*-----------------------------------------------------------------------------------*/
/* Save Submitted Settings
/*-----------------------------------------------------------------------------------*/

// Should allow this only on first install or after the user is authenticated
// but this doesn't quite work. So back to default.
if ($_POST["submit"] == "submit" && (!file_exists($settings_file) || isset($_SESSION['user'])))
{
    // Get submitted setup values.
    if (isset($_POST["blog_email"])) {
        $blog_email = $_POST["blog_email"];
    }
    if (isset($_POST["blog_twitter"])) {
        $blog_twitter = $_POST["blog_twitter"];
    }

    if (isset($_POST["blog_facebook"])) {
        $blog_facebook = $_POST["blog_facebook"];
    }
	if(!isset($blog_facebook)) {
        $blog_facebook = "";
    }	
    if (isset($_POST["blog_googlep"])) {
        $blog_googlep = $_POST["blog_googlep"];
    }	
	if(!isset($blog_googlep)) {
        $blog_googlep = "";
    }	
    if (isset($_POST["blog_tumblr"])) {
        $blog_tumblr = $_POST["blog_tumblr"];
    }
	if(!isset($blog_tumblr)) {
        $blog_tumblr = "";
    }	
	
    if (isset($_POST["blog_url"])) {
        $blog_url = $_POST["blog_url"];
		$blog_url = str_replace("?action=login", "",$blog_url);
    }
    if (isset($_POST["blog_title"])) {
        $blog_title = $_POST["blog_title"];
    }
    if (isset($_POST["meta_description"])) {
        $meta_description = $_POST["meta_description"];
    }
    if (isset($_POST["intro_title"])) {
        $intro_title = $_POST["intro_title"];
    }
    if (isset($_POST["intro_text"])) {
        $intro_text = $_POST["intro_text"];
    }
    if (isset($_POST["template"])) {
        $template = $_POST["template"];
    }

    // There must always be a $password, but it can be changed optionally in the
    // settings, so you might not always get it in $_POST.
    if (!isset($password) || !empty($_POST["password"])) {
        $password = $hasher->HashPassword($_POST["password"]);
    }

    if (isset($_POST["consumerkey"])) {
        $consumerkey = Encode($password,$_POST["consumerkey"]);
    } 
    if(!isset($consumerkey)) {
        $consumerkey = "";
    }	
    if (isset($_POST["consumersecret"])) {
        $consumersecret = Encode($password,$_POST["consumersecret"]);
    } 
    if(!isset($consumersecret)) {
        $consumersecret = "";
    }	
    if (isset($_POST["accesstoken"])) {
        $accesstoken = Encode($password,$_POST["accesstoken"]);
    } 
    if(!isset($accesstoken)) {
        $accesstoken = "";
    }	
    if (isset($_POST["accesstokensecret"])) {
		$accesstokensecret = Encode($password,$_POST["accesstokensecret"]);
    } 
    if(!isset($accesstokensecret)) {
        $accesstokensecret = "";
    }	

    if(!isset($header_inject)) {
        $header_inject = "";        
    }

    if(isset($_POST["header_inject"])) {
        $header_inject = addslashes($_POST["header_inject"]);
    }

    if(!isset($footer_inject)) {
        $footer_inject = "";
    }
    
    if(isset($_POST["footer_inject"])) {
        $footer_inject = addslashes($_POST["footer_inject"]);
    }
    
    if (isset($_POST["show_market"])) {
         $show_market = $_POST["show_market"];
    }
    if(!isset($show_market)) {
        $show_market = "1";
    }
     
	if(isset($_COOKIE['i18nLanguage'])) { 
		$language_default = trim($_COOKIE['i18nLanguage']); 
	}
    if(!isset($language_default)) {
		$language_default = "en_US";
	}	
	
	if(isset($_POST["avatar_default"])) { 
		$avatar_default = trim($_POST["avatar_default"]); 
	}
    if(!isset($avatar_default)) {
		$avatar_default = "gravatar";
	}	
	
	if(isset($_POST["copyright"])) { 
		$copyright = trim($_POST["copyright"]); 
	} else {
		$copyright = "";
	}	
	
	if(isset($_POST["paginationAuto"])) { 
		$paginationAuto = $_POST["paginationAuto"]; 
	}
    if (!isset($paginationAuto)) { 
		$paginationAuto = "off"; 
	}	
    
	if(isset($_POST["markdownType"])) { 
		$markdownType = $_POST["markdownType"]; 
	}		
	if (!isset($markdownType)) { 
		$markdownType = "php"; 
	}		
    // Get subdirectory
    $dir .= str_replace('dropplets/save.php', '', $_SERVER["REQUEST_URI"]);

    // Output submitted setup values.
    $config[] = "<?php";
    $config[] = settings_format("blog_email", $blog_email);
    $config[] = settings_format("blog_twitter", $blog_twitter);
    $config[] = settings_format("consumerkey", $consumerkey);
    $config[] = settings_format("consumersecret", $consumersecret);
    $config[] = settings_format("accesstoken", $accesstoken);	
	$config[] = settings_format("accesstokensecret", $accesstokensecret);
	$config[] = settings_format("blog_facebook", $blog_facebook);
	$config[] = settings_format("blog_googlep", $blog_googlep);
	$config[] = settings_format("blog_tumblr", $blog_tumblr);	
    $config[] = settings_format("blog_url", $blog_url);
    $config[] = settings_format("blog_title", $blog_title);
    $config[] = settings_format("meta_description", $meta_description);
    $config[] = settings_format("intro_title", $intro_title);
    $config[] = settings_format("intro_text", $intro_text);
    $config[] = "\$password = '".$password."';";
    $config[] = settings_format("header_inject", $header_inject);
    $config[] = settings_format("footer_inject", $footer_inject);
    $config[] = settings_format("show_market", $show_market);
    $config[] = settings_format("template", $template);
    $config[] = settings_format("language_default", $language_default);
    $config[] = settings_format("avatar_default", $avatar_default);
    $config[] = settings_format("copyright", $copyright);
    $config[] = settings_format("paginationAuto", $paginationAuto);
    $config[] = settings_format("markdownType", $markdownType);
    $config[] = "?>";    
    // Create the settings file.
    file_put_contents($settings_file, implode("\n", $config));
    
    // Generate the .htaccess file on initial setup only.
    if (!file_exists($htaccess_file)) {
    
        // Parameters for the htaccess file.
        $htaccess[] = "# Pretty Permalinks";
        $htaccess[] = "RewriteRule ^(images)($|/) - [L]";
        $htaccess[] = "RewriteCond %{REQUEST_URI} !^action=logout [NC]";
        $htaccess[] = "RewriteCond %{REQUEST_URI} !^action=login [NC]";
        $htaccess[] = "Options +FollowSymLinks -MultiViews";
        $htaccess[] = "RewriteEngine on";
        $htaccess[] = "RewriteBase " . $dir;
        $htaccess[] = "RewriteCond %{REQUEST_URI} !index\.php";
        $htaccess[] = "RewriteCond %{REQUEST_FILENAME} !-f";
        $htaccess[] = "RewriteRule ^(.*)$ index.php?filename=$1 [NC,QSA,L]";
        $htaccess[] = "# Protect all files";
        $htaccess[] = '<FilesMatch "\.(htaccess|gitignore|md)$>';
        $htaccess[] = "order Allow,Deny ";
        $htaccess[] = "Deny from all";
        $htaccess[] = "</FilesMatch>";
        // Generate the .htaccess file.
        file_put_contents($htaccess_file, implode("\n", $htaccess));
    }

    // Redirect
    header("Location: " . $blog_url);
}

?>
