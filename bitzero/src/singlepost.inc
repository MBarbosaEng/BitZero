<?php
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
        $post_categories = explode(',', str_replace(array("\n", '-'), '', $fcontents[4]));
        $post_allCat = "";
        if (count($post_categories)>0) {
            if (strpos($filename,"pages")) { // Get static page
                $post_category =  trim(str_replace(array("\n", '-', '#'), '', $fcontents[0]));                
                $post_category_link = BLOG_URL . '?filename=' . trim(str_replace(array("./pages/", './', 'pages/','.md'), '',$filename));
                $post_allCat = "<a href='" . $post_category_link . "'>" . $post_category . "</a>"; 
            } else { // Get the post categories link.
                $post_categories = array_map("trim", $post_categories); 
                function blogPathCat($e) {  return BLOG_URL . 'category/'.urlencode(trim(strtolower($e))); }
                $post_allCat = _t('About') . ":"; 
                $post_categories_links = array_map("blogPathCat", $post_categories);
                $post_category_link = BLOG_URL . 'category/'.urlencode(trim(strtolower($post_categories[0])));
                $post_category = $post_categories[0];
                /* from template:
                    <?php foreach($post_categories_links as $key => $post_category_link): ?><a href="<?php echo($post_category_link); ?>"><?php echo($post_categories[$key]); ?></a> <?php endforeach; ?>            
                */    
                foreach($post_categories_links as $key => $post_category_link) {
                        $post_allCat .= "&nbsp;<a href='" . $post_category_link . "'>" . $post_categories[$key] . "</a>";                 
                }                  
            }
        } else {
            $post_category_link = "";
            $post_category = "";        
        }
        
        // Get the post status.
        $post_status = trim(strtolower(str_replace(array("\n", '-'), '', $fcontents[5])));
        
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
 ?>