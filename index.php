<?php

// this variable use only for debug error codes
$localdebug = true;

session_start();

include('./bitzero/src/functions.inc'); 

/*-----------------------------------------------------------------------------------*/
/* If There's a Config Exists, Continue
/*-----------------------------------------------------------------------------------*/
if (file_exists('./config.php')) {

    /*-----------------------------------------------------------------------------------*/
    /* Get Settings & Functions
    /*-----------------------------------------------------------------------------------*/
    include('./dropplets/settings.php');
    include('./dropplets/functions.php');

    /*-----------------------------------------------------------------------------------*/
    /* Reading File Names
    /*-----------------------------------------------------------------------------------*/
    $category = NULL;
    $tag = NULL;
    if (empty($_GET['filename'])) {
        $filename = NULL;
    } else if($_GET['filename'] == 'rss' || $_GET['filename'] == 'atom') {
        $filename = $_GET['filename'];
    }  else {
        
        //Filename can be /some/blog/post-filename.md We should get the last part only
        $filename = explode('/',$_GET['filename']);

        // File name could be the name of a category
        if (((count($filename)>=2)?$filename[count($filename) - 2]:"") == "category") { // test offset before if
            $category = $filename[count($filename) - 1];
            $filename = null;
        } elseif (((count($filename)>=2)?$filename[count($filename) - 2]:"") == "tag") { 
            $tag = $filename[count($filename) - 1];
            $filename = null;
            
        } else {
            // static or normal page
            if (file_exists(PAGE_DIR . $filename[count($filename) - 1] . FILE_EXT)){
                // Individual Page - static
                $filename = PAGE_DIR . $filename[count($filename) - 1] . FILE_EXT;
            } else {    
                // Individual Post
                $filename = POSTS_DIR . $filename[count($filename) - 1] . FILE_EXT;
            }
        }
    }

    // Get Menu.
    $menu_site = get_Menu();
    // Get Copyright	
    $Blog_Copyright = BLOG_COPYRIGHT;

    
    if ($filename==NULL) {
        /*-----------------------------------------------------------------------------------*/
        /* The Home Page (All Posts)
        /*-----------------------------------------------------------------------------------*/
        include('./bitzero/src/allpost.inc'); 
    } else if ($filename == 'rss' || $filename == 'atom') {
        /*-----------------------------------------------------------------------------------*/
        /* RSS Feed
        /*-----------------------------------------------------------------------------------*/
        include('./bitzero/src/rss.inc'); 
    } else {
        /*-----------------------------------------------------------------------------------*/
        /* Single Post Pages
        /*-----------------------------------------------------------------------------------*/
        include('./bitzero/src/singlepost.inc');    
    }

} else {
    // Get the components of the current url.
    $protocol = @( $_SERVER["HTTPS"] != 'on') ? 'http://' : 'https://';
    $domain = $_SERVER["SERVER_NAME"];
    $port = $_SERVER["SERVER_PORT"];
    $path = $_SERVER["REQUEST_URI"];

    //Fix bug where if the site is called with index.php in the URL
    $path_last_element = end(explode("/", $path));
    $file_name = end(explode("/", __FILE__));
    if ($path_last_element === $file_name) {  
          /* If the last part of the URI is the same as the
          * last part of the __FILE__ (the file's name). 
          * Remove it from the end.
          *
          * Using substr() since str_replace() could remove more than 
          * we would like.
          */
         $path = substr ($path, 0, -strlen($path_last_element));
    }
    
    // Check if running on alternate port.
    if ($protocol === "https://") {
        if ($port === 443)
            $url = $protocol . $domain;
        else
            $url = $protocol . $domain . ":" . $port;
    } elseif ($protocol === "http://") {
        if ($port === 80)
            $url = $protocol . $domain;
        else
            $url = $protocol . $domain . ":" . $port;
    }

    $url .= $path;
    // Check if the cache directory is writable.
    $is_writable = is__writable(dirname(__FILE__) . '/cache/'); // (TRUE == is_writable(dirname(__FILE__) . '/cache/'));
   
    /*-----------------------------------------------------------------------------------*/
    /* Run Setup if No Config
    /*-----------------------------------------------------------------------------------*/
    include('./bitzero/src/setup.inc');
}
/*-----------------------------------------------------------------------------------*/
/* That's All There is to It
/*-----------------------------------------------------------------------------------*/
?>
