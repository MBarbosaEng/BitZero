<?php

session_start();

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
/*-----------------------------------------------------------------------------------*/
/* The Home Page (All Posts)
/*-----------------------------------------------------------------------------------*/

if ($filename==NULL) {

    $page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 1) ? $_GET['page'] : 1;
    $offset = ($page == 1) ? 0 : ($page - 1) * $posts_per_page;

    //Index page cache file name, will be used if index_cache = "on"

    $cachefile = CACHE_DIR . ($category ? $category : "index") .$page. '.html';

    //If index cache file exists, serve it directly wihout getting all posts
    if (file_exists($cachefile) && $index_cache != 'off') {
	
       // Get the cached post.
        include $cachefile;
        exit;

    // If there is a file for the selected permalink, display and cache the post.
    }

    if($category) {
        $all_posts = get_posts_for_category($category);
    } elseif($tag) {
        $all_posts = get_posts_for_tag($tag);
    } else {
        $all_posts = get_all_posts();
    }

    $pagination = ($pagination_on_off != "off") ? get_pagination($page,round(count($all_posts)/ $posts_per_page)) : "";
    define('PAGINATION', $pagination);
	if (count($all_posts)>0) {   
		//$posts = ($pagination_on_off != "off") ? array_slice($all_posts,$offset,($posts_per_page > 0) ? $posts_per_page : null) : $all_posts;
        if ($pagination_on_off != "off") {
            if (count($all_posts)>1) {
                if ($posts_per_page > 0) {
                    $pags = $posts_per_page;
                } else {
                    $pags = null;
                }
                $posts = array_slice($all_posts,$offset,$pags);
            } else {
                $posts = $all_posts;
            }
        } else {
            $posts = $all_posts;
        }
	} else {
		$posts = false;
	}

    if($posts) {
        ob_start();

		$content = '';
        foreach($posts as $post) {

            // Get the post title.
            $post_title = str_replace(array("\n",'<h1>','</h1>'), '', $post['post_title']);	
			
            // Get the post author.
            $post_author = $post['post_author'];

            // Get the post author twitter id.
            $post_author_twitter = $post['post_author_twitter'];

            // Get the published ISO date.
            $published_iso_date = $post['post_date'];

            // Generate the published date.
            $published_date = localDate($published_iso_date); //mod for i18n - original: date_format(date_create($published_iso_date), $date_format);

            // Get the post category.
            $post_category = $post['post_category'];
            
            // Get the post category link.
            $post_category_link = $blog_url.'category/'.urlencode(trim(strtolower($post_category)));

            // Get the post status.
            $post_status = $post['post_status'];   
            
            // Get the posts tags.
            $temp_tags = $post['post_tags'];
            if (count($temp_tags)>0) {
                $post_tags = "<li>Tags:";        
                    foreach($temp_tags as $tag) {
                        $post_tags .= "&nbsp;<a href='" . $blog_url. "tag/" . urlencode(trim(strtolower($tag))) . "'>" . trim($tag) . "</a>";
                    }
                $post_tags .= "</li>";
            } else {
                // static page - don't show tags
                $post_tags = "";
            }            
            
            
            
            // Get the post intro.
            $post_intro = $post['post_intro'];
                
            // Get the post content
            $post_content = $post['post_content'];

            // Get the post link.
            if ($category) {
                $post_link = trim(strtolower($post_category)).'/'.str_replace(FILE_EXT, '', $post['fname']);
            } else {
                $post_link = $blog_url.str_replace(FILE_EXT, '', $post['fname']);
            }

            // Get the post image url.
            $image = str_replace(array(FILE_EXT), '', POSTS_DIR.$post['fname']).'.jpg';
            if (file_exists($image)) {
                $post_image = $blog_url.str_replace(array(FILE_EXT, './'), '', POSTS_DIR.$post['fname']).'.jpg';
            } else {
			    $image = str_replace(array(FILE_EXT), '', POSTS_DIR.$post['fname']).'.png';
				if (file_exists($image)) {
					$post_image = $blog_url.str_replace(array(FILE_EXT, './'), '', POSTS_DIR.$post['fname']).'.png';
				} else {
					$post_image = get_post_img($post_author_twitter);
				}
            }
            
            if ($post_status == 'draft') continue;

            // Get the milti-post template file.
            include $posts_file;
        }
        echo $content;
        $content = ob_get_contents();

        // Get the site title
        $blog_title = trim($blog_title);
        $page_title = $blog_title;
		
		
        $blog_image = get_profile_img();

        // Get the page description and author meta.
        $get_page_meta[] = '<meta name="description" content="' . $meta_description . '">';
        $get_page_meta[] = '<meta name="author" content="' . $blog_title . '">';

        
        // Generate the Twitter card.
        $qTwitterId = '';
        if ($post_author_twitter != "") {
            $qTwitterId = $post_author_twitter;
        } else if (BLOG_TWITTER != "") {
            $qTwitterId = BLOG_TWITTER;
        }
        if ($qTwitterId != "") {
            $get_page_meta[] = '<meta name="twitter:card" content="summary">';
            $get_page_meta[] = '<meta name="twitter:site" content="' . $qTwitterId . '">';
            $get_page_meta[] = '<meta name="twitter:title" content="' . StrLimit($blog_title,70) . '">';
            $get_page_meta[] = '<meta name="twitter:description" content="' . StrLimit($meta_description,200) . '">';
            $get_page_meta[] = '<meta name="twitter:creator" content="' . $qTwitterId . '">';
            $get_page_meta[] = '<meta name="twitter:image:src" content="' . $blog_image . '">';
            $get_page_meta[] = '<meta name="twitter:domain" content="' . $blog_url . '">';
        }
        // Get the Open Graph tags.
        $get_page_meta[] = '<meta property="og:type" content="website">';
        $get_page_meta[] = '<meta property="og:title" content="' . StrLimit($blog_title,95) . '">';
        $get_page_meta[] = '<meta property="og:site_name" content="' . $blog_title . '">';
        $get_page_meta[] = '<meta property="og:url" content="' .$blog_url . '">';
        $get_page_meta[] = '<meta property="og:description" content="' . StrLimit($meta_description,297) . '">';
        $get_page_meta[] = '<meta property="og:image" content="' . $blog_image . '">';

        // Get all page meta.
        $page_meta = implode("\n", $get_page_meta);

        if ( $post_author_twitter != '') {
            $post_author = '<a href="https://twitter.com/'.$post_author_twitter.'" target="_blank">' . $post_author . '</a>';
        }        
        
        ob_end_clean();
    } else {
        ob_start();

        // Define the site title.
        $page_title = _t("Sorry, But That’s Not Here");
        $page_meta = '';
        
        // Get the 404 page template.
        include $not_found_file;

        //Get the contents
        $content = ob_get_contents();

        //Flush the buffer so that we dont get the page 2x times
        ob_end_clean();
    }
    
    ob_start();
    
    // Get the index template file.
    include_once $index_file;

    //Now that we have the whole index page generated, put it in cache folder
    if ($index_cache != 'off') {
        $fp = fopen($cachefile, 'w');
        fwrite($fp, ob_get_contents());
        fclose($fp);
    }
}

/*-----------------------------------------------------------------------------------*/
/* RSS Feed
/*-----------------------------------------------------------------------------------*/

else if ($filename == 'rss' || $filename == 'atom') {
    ($filename=='rss') ? $feed = new FeedWriter(RSS2) : $feed = new FeedWriter(ATOM);

    $feed->setTitle($blog_title);
    $feed->setLink($blog_url);

    if($filename=='rss') {
        $feed->setDescription($meta_description);
        $feed->setChannelElement('language', $language);
        $feed->setChannelElement('pubDate', date(DATE_RSS, time()));
    } else {
        $feed->setChannelElement('author', $blog_title.' - ' . $blog_email);
        $feed->setChannelElement('updated', date(DATE_RSS, time()));
    }

    $posts = get_all_posts();

    if($posts) {
        $c=0;
        foreach($posts as $post) {
            if($c<$feed_max_items) {
                $item = $feed->createNewItem();

                // Remove HTML from the RSS feed.
                $item->setTitle(substr($post['post_title'], 4, -6));
                $item->setLink(rtrim($blog_url, '/').'/'.str_replace(FILE_EXT, '', $post['fname']));
                $item->setDate($post['post_date']);

                // Remove Meta from the RSS feed.
				$remove_metadata_from = file(rtrim(POSTS_DIR, '/').'/'.$post['fname']);

                if($filename=='rss') {
                    $item->addElement('author', str_replace('-', '', $remove_metadata_from[1]).' - ' . $blog_email);
                    $item->addElement('guid', rtrim($blog_url, '/').'/'.str_replace(FILE_EXT, '', $post['fname']));
                }

				// Remove the metadata from the RSS feed.
				unset($remove_metadata_from[0], $remove_metadata_from[1], $remove_metadata_from[2], $remove_metadata_from[3], $remove_metadata_from[4], $remove_metadata_from[5]);
				$remove_metadata_from = array_values($remove_metadata_from);

                $item->setDescription(MarkdownToHtml(implode($remove_metadata_from)));

                $feed->addItem($item);
                $c++;
            }
        }
    }
    $feed->genarateFeed();
}

/*-----------------------------------------------------------------------------------*/
/* Single Post Pages
/*-----------------------------------------------------------------------------------*/

else {
    ob_start();

    // warning correction - if the file doesn't exist will not show error
    if (file_exists($filename)) { 
    	// Define the post file.
	    $fcontents = file($filename);
	    $slug_array = explode("/", $filename);
	    $slug_len = count($slug_array);

	    // This was hardcoded array index, it should always return the last index.
	    $slug = str_replace(array(FILE_EXT), '', $slug_array[$slug_len - 1]);

	    // Define the cached file.
	    $cachefile = CACHE_DIR.$slug.'.html';
	}
    // If there's no file for the selected permalink, grab the 404 page template.
    if (!file_exists($filename)) {

        //Change the cache file to 404 page.
        $cachefile = CACHE_DIR.'404.html';
        //variables preserved for backwards compatibility.
		$error_title = _t("Sorry, But That's Not Here");
		$error_text  = _t("Really sorry, but what you're looking for isn't here.") . " " . _t("Click the button below to find something else that might interest you.");
		// Define the site title.
        $page_title = $error_title;
		
        // Get the 404 page template.
        include $not_found_file;

        // Get the contents.
        $content = ob_get_contents();

        // Flush the buffer so that we dont get the page 2x times.
        ob_end_clean();

        // Start new buffer.
        ob_start();
		
	      // Get the index template file.
        include_once $index_file;

        // Cache the post on if caching is turned on.
        if ($post_cache != 'off')
        {
            $fp = fopen($cachefile, 'w');
            fwrite($fp, ob_get_contents());
            fclose($fp);
        }

    // If there is a cached file for the selected permalink, display the cached post.
    } else if (file_exists($cachefile)) {

        // Define site title
        $page_title = str_replace('# ', '', $fcontents[0]);
		
        // Get the cached post.
        include $cachefile;
        // free memory
        unset($fcontents);
        exit;

    // If there is a file for the selected permalink, display and cache the post.
    } else {

        // Get the post title.
        $post_title = MarkdownToHtml(htmlspecialchars(trim(str_replace(array("\n", '-', '#'), '', $fcontents[0]))));
        $post_title = trim(str_replace(array("\n",'<h1>','</h1>'), '', $post_title));
         
        // Get the post author.
        $post_author = trim(str_replace(array("\n", '-'), '', $fcontents[1]));

        // Get the post author Twitter ID.
        $post_author_twitter = trim(str_replace(array("\n", '-', '@'), '', $fcontents[2]));

        // Get the published date.
        $published_iso_date = trim(str_replace(array("\n", '-'), '', $fcontents[3]));

        // Generate the published date.
        $published_date = localDate($published_iso_date); //mod for i18n - original: date_format(date_create($published_iso_date), $date_format);

        // Get the post category.
        $post_category = trim(str_replace(array("\n", '-'), '', $fcontents[4]));
        
        // Get the post status.
        $post_status = trim(str_replace(array("\n", '-'), '', $fcontents[5]));
        
        // Get the posts tags.
        $temp_tags = explode(',', trim(str_replace(array("\n", '-'), '', $fcontents[6])));
        if (count($temp_tags)>0) {
            $post_tags = "<li>Tags:";        
            if (strpos($filename,"posts")) {
                foreach($temp_tags as $tag) {
                    $post_tags .= "&nbsp;<a href='" . $blog_url. "tag/" . urlencode(trim(strtolower($tag))) . "'>" . trim($tag) . "</a>";
                }
            } else {
                // static pages - don't use link on this! - don't use tag on this!
                foreach($temp_tags as $tag) {
                    $post_tags .= "&nbsp;" . trim($tag);
                }
            }
            $post_tags .= "</li>";
        } else {
            // static page - don't show tags
            $post_tags = "";
        }
        
         // Get the post intro.
        $post_intro = MarkdownToHtml(htmlspecialchars(trim(str_replace(array("\n", '-'), '', $fcontents[7]))));
        
        // Get the post category link.
        $post_category_link = $blog_url.'category/'.urlencode(trim(strtolower($post_category)));

        // Get the post link.
        $post_link = $blog_url.str_replace(array(FILE_EXT, POSTS_DIR), '', $filename);

        // Get the post image url.
        $image = str_replace(array(FILE_EXT), '', $filename).'.jpg';
        if (file_exists($image)) {
            $post_image = $blog_url.str_replace(array(FILE_EXT, './'), '', $filename).'.jpg';
		} else {	
            $image = str_replace(array(FILE_EXT), '', $filename).'.png';
			if (file_exists($image)) {
				$post_image = $blog_url.str_replace(array(FILE_EXT, './'), '', $filename).'.png';
			}  else {
				$post_image = get_post_img($post_author_twitter);
			}
		}
            
        // Get the site title.
        $post_author = trim($post_author);
        $page_title = trim(str_replace('#', '', $fcontents[0]));
		
        // Generate the page description and author meta.
        $get_page_meta[] = '<meta name="description" content="' . $post_intro . '">';
        $get_page_meta[] = '<meta name="authors" content="' . $post_author . '">';

        // Generate the Twitter card.
        $qTwitterId = '';
        if ($post_author_twitter != "") {
            $qTwitterId = $post_author_twitter;
        } else if (BLOG_TWITTER != "") {
            $qTwitterId = BLOG_TWITTER;
        }
        if ($qTwitterId != "") {
            $get_page_meta[] = '<meta name="twitter:card" content="summary">';
            $get_page_meta[] = '<meta name="twitter:site" content="' . $qTwitterId . '">';
            $get_page_meta[] = '<meta name="twitter:title" content="' . StrLimit($page_title,70) . '">';
            $get_page_meta[] = '<meta name="twitter:description" content="' . StrLimit($post_intro,200)  . '">';
            $get_page_meta[] = '<meta name="twitter:creator" content="' . $qTwitterId. '">';
            $get_page_meta[] = '<meta name="twitter:image:src" content="' . $post_image . '">';
            $get_page_meta[] = '<meta name="twitter:domain" content="' . $post_link . '">';
        }
        
        // Get the Open Graph tags.
        $get_page_meta[] = '<meta property="og:type" content="article">';
        $get_page_meta[] = '<meta property="og:title" content="' . StrLimit($page_title,95) . '">';
        $get_page_meta[] = '<meta property="og:site_name" content="' . $page_title . '">';
        $get_page_meta[] = '<meta property="og:url" content="' . $post_link . '">';
        $get_page_meta[] = '<meta property="og:description" content="' . StrLimit($post_intro,297) . '">';
        $get_page_meta[] = '<meta property="og:image" content="' . $post_image . '">';

        if ( $post_author_twitter != '') {
            $post_author = '<a href="https://twitter.com/'.$post_author_twitter.'" target="_blank">' . $post_author . '</a>';
        }
                
        // Generate all page meta.
        $page_meta = implode("\n", $get_page_meta);

        // Generate the post.
        $post = join('', array_slice($fcontents, 8, sizeof($fcontents) -1));
        if ((markdownType == "js") || (markdownType == "mkd")){
            $post_content  = $post_intro . "<span id='contentPost'>" . $post . "</span>";
        } else {
            $post_content = $post_intro . MarkdownToHtml($post); // change $fcontents.length to sizeof($fcontents)
        }
        
        // free memory
        unset($fcontents);

        // Get the post template file.
        include $post_file;

        $content = ob_get_contents();
        ob_end_clean();
        ob_start();

        // Get the index template file.
        include_once $index_file;

        // Cache the post on if caching is turned on.
        if ($post_cache != 'off')
        {
            $fp = fopen($cachefile, 'w');
            fwrite($fp, ob_get_contents());
            fclose($fp);
        }
    }
}

/*-----------------------------------------------------------------------------------*/
/* Run Setup if No Config
/*-----------------------------------------------------------------------------------*/

} else {
    // Get the components of the current url.
    $protocol = @( $_SERVER["HTTPS"] != 'on') ? 'http://' : 'https://';
    $domain = $_SERVER["SERVER_NAME"];
    $port = $_SERVER["SERVER_PORT"];
    $path = $_SERVER["REQUEST_URI"];

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
    // Check if the install directory is writable.
    $is_writable = (TRUE == is_writable(dirname(__FILE__) . '/'));
    ?>

    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8" />
            <title>Let's Get Started</title>
        <?php
            if (file_exists("bitzero/other_sources/font-awesome/css/font-awesome.min.css")) {
                    echo '<link rel="stylesheet" href="./bitzero/other_sources/font-awesome/extra/site.css" />';
                    echo '<link rel="stylesheet" href="./bitzero/css/style.css" />';
            } else {    
                    echo '<link rel="stylesheet" href="./dropplets/style/style.css" />';
            }
        ?>
            <link href='http://fonts.googleapis.com/css?family=Lato:100,300' rel='stylesheet' type='text/css'>
            <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400' rel='stylesheet' type='text/css'>
            <link rel="shortcut icon" href="./dropplets/style/images/favicon.png">
            <?php
                if (file_exists("bitzero/other_sources/font-awesome/css/font-awesome.min.css")) {
                    echo '<link href="bitzero/other_sources/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">';
                }
            ?>    
        </head>

        <body class="dp-install">
            <form method="POST" action="./dropplets/save.php">
            <?php
                if (file_exists("bitzero/other_sources/font-awesome/css/font-awesome.min.css")) {
                    echo '<i class="fa dp-icon-setup"></i>';
                } else {
                    echo '<a class="dp-icon-dropplets" href="http://dropplets.com" target="_blank"></a>';
                }
            ?>    
                <h2>Install</h2>
                <p>Welcome to an easier way to blog.</p>
            <?php
                if (file_exists("bitzero/other_sources/font-awesome/css/font-awesome.min.css")) { ?>   
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                  <input class="form-control" name="password" id="password" type="password" placeholder="Choose Your Password">
                </div>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                  <input class="form-control" name="password-confirmation" id="password-confirmation" required type="password" placeholder="Confirm Your Password" onblur="confirmPass()">
                </div>
                <div class="input-group margin-bottom-sm">
                  <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
                  <input class="form-control" type="text" name="blog_email" id="blog_email" value="" required placeholder="Email address">
                </div>                
            <?php } else { ?>    
                
                <input type="password" name="password" id="password" required placeholder="Choose Your Password">
                <input type="password" name="password-confirmation" id="password-confirmation" required placeholder="Confirm Your Password" onblur="confirmPass()">                
                <input type="text" name="blog_email" id="blog_email" value="" required placeholder="Choose Your E-mail">
			<?php	
                  }
            ?>                 
                <input hidden type="text" name="blog_twitter" id="blog_twitter" value="">
                <input hidden type="text" name="blog_url" id="blog_url" value="<?php echo($url) ?><?php if ($url == $domain) { ?>/<?php } ?>">
                <input hidden type="text" name="show_market" id="show_market" value="1">
                <input hidden type="text" name="template" id="template" value="simpleI8N">
                <input hidden type="text" name="blog_title" id="blog_title" value="Welcome">
                <textarea hidden name="meta_description" id="meta_description"></textarea>
                <input hidden type="text" name="intro_title" id="intro_title" value="Welcome">
                <textarea hidden name="intro_text" id="intro_text">In a flooded selection of overly complex solutions, Dropplets has been created in order to deliver a much needed alternative. There is something to be said about true simplicity in the design, development and management of a blog. By eliminating all of the unnecessary elements found in typical solutions, Dropplets can focus on pure design, typography and usability. Welcome to an easier way to blog.<br/> <span style='font-size:20px;'>BitZero</span> is a modification of Dropplets blogging platform. Uses the same concept, but has implementations that do not exist in Dropplets platform.</textarea>
            <?php
                if (file_exists("bitzero/other_sources/font-awesome/css/font-awesome.min.css")) { ?> 
                <button type="submit" name="submit" value="submit"><i class="fa fa-check"></i></button>
            <?php } else { ?>    
    		    <button type="submit" name="submit" value="submit">k</button>
            <?php } ?>   
    		</form>
                
            <?php if (!$is_writable) { ?>
                <p style="color:red;">It seems that your config folder is not writable, please add the necessary permissions.</p>
            <?php } ?>
            <script type="text/javascript">
            	function confirmPass() {
            		var pass = document.getElementById("password").value
            		var confPass = document.getElementById("password-confirmation").value
            		if(pass != confPass) {
            			alert('Your passwords do not match!');
            		}
            	}		
			</script>
		</body>
    </html>
<?php 

/*-----------------------------------------------------------------------------------*/
/* That's All There is to It
/*-----------------------------------------------------------------------------------*/

}

?>
