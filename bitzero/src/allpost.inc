<?php
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
            $post_categories =  $post['post_categories'];
            $post_allCat = "";
            if (count($post_categories)>0) {
                if (strpos($filename,"pages")) { // Get static page
                    $post_category =  trim(str_replace(array("\n", '-', '#'), '', $post['post_title']));                
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
            $post_status = trim(strtolower($post['post_status']));   
            
            // Get the posts tags.
            $temp_tags = $post['post_tags'];
            if (count($temp_tags)>0) {
                $post_tags = "<li>Tags:";        
                    foreach($temp_tags as $tag) {
                        $post_tags .= "&nbsp;<a href='" . BLOG_URL . "tag/" . urlencode(trim(strtolower($tag))) . "'>" . trim($tag) . "</a>";
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
                $post_link = trim(strtolower($category)).'/'.str_replace(FILE_EXT, '', $post['fname']); // open post in category
            } else {
                $post_link = $blog_url.str_replace(FILE_EXT, '', $post['fname']);
            }
            $post_link = $blog_url.str_replace(FILE_EXT, '', $post['fname']); // make link unique
            
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
?>