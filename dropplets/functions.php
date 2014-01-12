<?php

/*-----------------------------------------------------------------------------------*/
/* Include 3rd Party Functions
/*-----------------------------------------------------------------------------------*/

include('./dropplets/includes/feedwriter.php');

include('./bitzero/src/choosePhpMarkdown.php');

include('./dropplets/includes/phpass.php');
include('./dropplets/includes/actions.php');

/*-----------------------------------------------------------------------------------*/
/* Include All Plugins in BitZero Plugins Directory
/*-----------------------------------------------------------------------------------*/
foreach(glob('./bitzero/plugins/' . '*.php') as $plugin){
    include_once $plugin;
}

foreach(glob('./bitzero/inc/' . '*.php') as $plugin){
    include_once $plugin;
}

/*-----------------------------------------------------------------------------------*/
/* Include All Plugins in Dropplets Plugins Directory
/*-----------------------------------------------------------------------------------*/

foreach(glob('./plugins/' . '*.php') as $plugin){
    include_once $plugin;
}

/*-----------------------------------------------------------------------------------*/
/* User Machine
/*-----------------------------------------------------------------------------------*/

// Password hashing via phpass.
$hasher  = new PasswordHash(8,FALSE);

if (isset($_GET['action']))
{
    $action = $_GET['action'];
    switch ($action)
    {

        // Logging in.
        case 'login':
            if ((isset($_POST['password'])) && $hasher->CheckPassword($_POST['password'], $password)) {
                $_SESSION['user'] = true;

                // Redirect if authenticated.
                header('Location: ' . './');
            } else {
                
                // Display error if not authenticated.
                $login_error = 'Nope, try again!';
            }
            break;

        // Logging out.
        case 'logout':
            session_unset();
            session_destroy();

            // Redirect to dashboard on logout.
            header('Location: ' . './');
            break;
        
        // Fogot password.
        case 'forgot':
            
            // The verification file.
            $verification_file = "./verify.php";
            
            // If verified, allow a password reset.
            if (!isset($_GET["verify"])) {
            
                $code = sha1(md5(rand()));

                $verify_file_contents[] = "<?php\n";
                $verify_file_contents[] = "\$verification_code = \"" . $code . "\";";
                file_put_contents($verification_file, implode("\n", $verify_file_contents));

                $recovery_url = sprintf("%s/index.php?action=forgot&verify=%s,", $blog_url, $code);
                $message      = sprintf("To reset your password go to: %s", $recovery_url);

                $headers[] = "From: " . $blog_email;
                $headers[] = "Reply-To: " . $blog_email;
                $headers[] = "X-Mailer: PHP/" . phpversion();

                mail($blog_email, $blog_title . " - Recover your Dropplets Password", $message, implode("\r\n", $headers));
                $login_error = "Details on how to recover your password have been sent to your email.";
            
            // If not verified, display a verification error.   
            } else {

                include($verification_file);

                if ($_GET["verify"] == $verification_code) {
                    $_SESSION["user"] = true;
                    unlink($verification_file);
                } else {
                    $login_error = "That's not the correct recovery code!";
                }
            }
            break;
        
        // Invalidation            
        case 'invalidate':
            if (!$_SESSION['user']) {
                $login_error = 'Nope, try again!';
            } else {
                if (!file_exists($upload_dir . 'cache/')) {
                    return;
                }
                
                $files = glob($upload_dir . 'cache/*');
                
                foreach ($files as $file) {
                    if (is_file($file))
                        unlink($file);
                }
            }
            
            header('Location: ' . './');
            break;
    }
    
}
// not show warning or error if the element is not set
if (!(defined('LOGIN_ERROR'))) { 
	if (!isset($login_error)) { $login_error=''; } 
	define('LOGIN_ERROR', $login_error);
}


/*-----------------------------------------------------------------------------------*/
/* Get All Posts Function
/*-----------------------------------------------------------------------------------*/

function get_all_posts($options = array()) {
    global $dropplets;

    if($handle = opendir(POSTS_DIR)) {

        $files = array();
        $filetimes = array();

        while (false !== ($entry = readdir($handle))) {
            if(substr(strrchr($entry,'.'),1)==ltrim(FILE_EXT, '.')) {

                // Define the post file.
                $fcontents = file(POSTS_DIR.$entry);

                // Define the post title.
                $post_title =  MarkdownToHtml(htmlspecialchars(trim(str_replace(array("\n", '-', '#'), '', $fcontents[0]))));
                $post_title = trim(str_replace(array("\n",'<h1>','</h1>'), '', $post_title));
        
                // Define the post author.
                $post_author = str_replace(array("\n", '-'), '', $fcontents[1]);

                // Define the post author Twitter account.
                $post_author_twitter = str_replace(array("\n", '- ', '@'), '', $fcontents[2]);

                // Define the published date.
                $post_date = str_replace('-', '', $fcontents[3]);

                // Define the post category.
                $post_category = str_replace(array("\n", '-'), '', $fcontents[4]);

                // Early return if we only want posts from a certain category
				if (isset($options["category"])) { // not show warning or error if the element is not set
					if($options["category"] && $options["category"] != trim(strtolower($post_category))) {
						continue;
					}
				}

                // Define the post status.
                $post_status = str_replace(array("\n", '- '), '', $fcontents[5]);
                               
                // Get the posts tags.
                $post_tags = explode(',', trim(str_replace(array("\n", '-'), '', $fcontents[6])));
                $post_tags = array_map('trim', $post_tags);

                if (isset($options["tag"])) { // not show warning or error if the element is not set
                    if($options['tag'] && !in_array($options["tag"], $post_tags)) {
                        continue;
                    }
                }

                             
                // Define the post intro.
                $post_intro =  MarkdownToHtml(htmlspecialchars(trim(str_replace(array("\n", '-'), '', $fcontents[7]))));

                // Define the post content
				$post_content = MarkdownToHtml(join('', array_slice($fcontents, 7, sizeof($fcontents) -1))); // change $fcontents.length to sizeof($fcontents)
                
				// Pull everything together for the loop.
                $files[] = array('fname' => $entry, 'post_title' => $post_title, 'post_author' => $post_author, 'post_author_twitter' => $post_author_twitter, 'post_date' => $post_date, 'post_category' => $post_category, 'post_status' => $post_status, 'post_tags' => $post_tags, 'post_intro' => $post_intro, 'post_content' => $post_content);
                $post_dates[] = $post_date;
                $post_titles[] = $post_title;
                $post_authors[] = $post_author;
                $post_authors_twitter[] = $post_author_twitter;
                $post_categories[] = $post_category;
                $post_statuses[] = $post_status;
                $post_tags[] = $post_tags;
                $post_intros[] = $post_intro;
                $post_contents[] = $post_content;
                // free memory
                unset($fcontents);
            }
        }
		if (count($files)>0) {
			array_multisort($post_dates, SORT_DESC, $files);
			return $files;
		} else {
			return false;
		}

    } else {
        return false;
    }
}


/*-----------------------------------------------------------------------------------*/
/* Get Posts for Selected Category
/*-----------------------------------------------------------------------------------*/

function get_posts_for_category($category) {
    $category = trim(strtolower($category));
    return get_all_posts(array("category" => $category));
}

/*-----------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------*/
/* Get Image for a Post
/*-----------------------------------------------------------------------------------*/
function get_post_image_url($filename)
{
    global $blog_url;
    $supportedFormats = array( "jpg", "png", "gif" );
    $slug = pathinfo($filename, PATHINFO_FILENAME);

    foreach($supportedFormats as $fmt)
    {
        $imgFile = sprintf("%s%s.%s", POSTS_DIR, $slug, $fmt);
        if (file_exists($imgFile))
            return sprintf("%s/%s.%s", "${blog_url}posts", $slug, $fmt);
    }

    return false;
}
/* Post Pagination
/*-----------------------------------------------------------------------------------*/

function get_pagination($page,$total) {

    $string = '';
    $string .= "<ul style=\"list-style:none; width:400px; margin:15px auto;\">";

    for ($i = 1; $i<=$total;$i++) {
        if ($i == $page) {
            $string .= "<li style='display: inline-block; margin:5px;' class=\"active\"><a class=\"button\" href='#'>".$i."</a></li>";
        } else {
            $string .=  "<li style='display: inline-block; margin:5px;'><a class=\"button\" href=\"?page=".$i."\">".$i."</a></li>";
        }
    }
    
    $string .= "</ul>";
    return $string;
}

/*-----------------------------------------------------------------------------------*/
/* Get Installed Templates
/*-----------------------------------------------------------------------------------*/

function get_installed_templates() {
    
    // The currently active template.
    $active_template = ACTIVE_TEMPLATE;

    // The templates directory.
    $templates_directory = './templates/';

    // Get all templates in the templates directory.
    $available_templates = glob($templates_directory . '*');
    
    foreach ($available_templates as $template):

        // Generate template names.
        $template_dir_name = substr($template, 12);

        // Template screenshots.
        $template_screenshot = '' . $templates_directory . '' . $template_dir_name . '/screenshot.jpg'; {
            ?>
            <li<?php if($active_template == $template_dir_name) { ?> class="active"<?php } ?>>
                <div class="shadow"></div>
                <form method="POST" action="./dropplets/save.php">
                    <img src="<?php echo $template_screenshot; ?>">
                    <input type="hidden" name="template" id="template" required readonly value="<?php echo $template_dir_name ?>">
                    <button class="<?php if ($active_template == $template_dir_name) :?>active<?php else : ?>activate<?php endif; ?>" type="submit" name="submit" value="submit"><?php if ($active_template == $template_dir_name) :?>t<?php else : ?>k<?php endif; ?></button>
                </form>
            </li>
        <?php
        }
    endforeach;
}

/*-----------------------------------------------------------------------------------*/
/* Get Premium Templates
/*-----------------------------------------------------------------------------------*/

function get_premium_templates($type = 'all', $target = 'blank') {
    
    $templates = simplexml_load_file('http://dropplets.com/templates-'. $type .'.xml');
    
    if($templates===FALSE) {
        // Feed not available.
    } else {
        foreach ($templates as $template):
            
            // Define some variables
            $template_file_name=$template->file;
            $template_price=$template->price;
            $template_url=$template->url;
            
            { ?>
            <li class="premium">
                <img src="http://dropplets.com/demo/templates/<?php echo $template_file_name; ?>/screenshot.jpg">
                <a class="buy" href="http://gum.co/dp-<?php echo $template_file_name; ?>" title="Purchase/Download"><?php echo $template_price; ?></a> 
                <a class="preview" href="http://dropplets.com/demo/?template=<?php echo $template_file_name; ?>" title="Prview" target="_<?php echo $target; ?>">p</a>    
            </li>
            <?php } 
        endforeach;
    }
}

function count_premium_templates($type = 'all') {

    $templates = simplexml_load_file('http://dropplets.com/templates-'. $type .'.xml');

    if($templates===FALSE) {
        // Feed not available.
    } else {
        $templates = simplexml_load_file('http://dropplets.com/templates-'. $type .'.xml');
        $templates_count = $templates->children();
        echo count($templates_count);
    }
}

/*-----------------------------------------------------------------------------------*/
/* get current Home url
/*-----------------------------------------------------------------------------------*/
function get_HOME(){
    // Get the components of the current url.
    $protocol = @( $_SERVER["HTTPS"] != 'on') ? 'http://' : 'https://';
    $domain = $_SERVER["SERVER_NAME"];
    $port = $_SERVER["SERVER_PORT"];
    $path = $_SERVER["REQUEST_URI"];
    // Check if running on alternate port.
    if ($protocol === "https://") {
        if ($port === 443)
            $currentpage = $protocol . $domain;
        else
            $currentpage = $protocol . $domain . ":" . $port;
    } elseif ($protocol === "http://") {
        if ($port === 80)
            $currentpage = $protocol . $domain;
        else
            $currentpage = $protocol . $domain . ":" . $port;
    }
    $currentpage .= $path;
	return $currentpage;	
}
/*-----------------------------------------------------------------------------------*/
/* If is Home (Could use "is_single", "is_category" as well.)
/*-----------------------------------------------------------------------------------*/

$homepage = BLOG_URL;

// Get the current page.    
// $currentpage  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] : 'https://'.$_SERVER["SERVER_NAME"];
//$currentpage .= $_SERVER["REQUEST_URI"];
$currentpage  = get_HOME();

// If is home. 
$is_home = ($homepage==$currentpage);
define('IS_HOME', $is_home);
define('IS_CATEGORY', (bool)strstr($_SERVER['REQUEST_URI'], '/category/'));
define('IS_TAG', (bool)strstr($_SERVER['REQUEST_URI'], '/tag/'));
define('IS_SINGLE', !(IS_HOME || IS_CATEGORY));

/*-----------------------------------------------------------------------------------*/
/* defines default blog options 
/*-----------------------------------------------------------------------------------*/
function getPaginationAuto() {
	if(paginationAuto == "on") { 
		echo '<option value="on" selected>' . _t("Pagination On") . '</option>';
        echo '<option value="off">' . _t("Pagination Off") . '</option>';        
	} else {
		echo '<option value="on">' . _t("Pagination On") . '</option>';    
		echo '<option value="off" selected>' . _t("Pagination Off") . '</option>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* String limit - defines the maximum size of the fields Twitter Card and Open Graph Tags
/*-----------------------------------------------------------------------------------*/
function StrLimit($sStrg,$iLmt){
	if (strlen($sStrg)>$iLmt) {
		return substr($sStrg,0,$iLmt);
	} else {
		return $sStrg;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Dropplets Header
/*-----------------------------------------------------------------------------------*/

function get_header() { ?>
    <!-- RSS Feed Links -->
    <link rel="alternate" type="application/rss+xml" title="Subscribe using RSS" href="<?php echo BLOG_URL; ?>rss" />
    <link rel="alternate" type="application/atom+xml" title="Subscribe using Atom" href="<?php echo BLOG_URL; ?>atom" />
    
    <!-- Dropplets Styles -->
    <link rel="stylesheet" href="<?php echo BLOG_URL; ?>dropplets/style/style.css">
	<?php
      	$file = BLOG_PATH . "favicon.png"; // windows compatible
    	if(file_exists($file)) {  
			echo '<link rel="shortcut icon" href="' . BLOG_URL . 'favicon.png">';
		} else {
			echo '<link rel="shortcut icon" href="' . BLOG_URL . 'dropplets/style/images/favicon.png">';
		}
		echo "<!-- jQuery & Required Scripts -->";
      	$file = BLOG_PATH . "bitzero/js/jquery-1.10.2.min.js"; // windows compatible
    	if(file_exists($file)) {  
            echo '<script language="JavaScript" type="text/javascript" src="' . BLOG_URL . 'bitzero/js/jquery-1.10.2.min.js"></script>';			
		} else {
			echo '<script language="JavaScript" type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>';
		}
        
      	$file = BLOG_PATH . "bitzero/js/modernizr.custom.js"; // windows compatible
    	if(file_exists($file)) {          
            echo "<!-- Modernizr Script -->";        
            echo "<script src='" . BLOG_URL . "bitzero/js/modernizr.custom.js'></script>";
        }
        
        echo "<!-- Fonts Merriweather & Source Sans Pro -->";
		$file = BLOG_PATH . "bitzero/other_sources/fonts/fonts.css";
    	if(file_exists($file)) {
            echo "<link href='" . BLOG_URL . "bitzero/other_sources/fonts/fonts.css' rel='stylesheet' type='text/css'>";        
		} else {
			echo "<link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,700' rel='stylesheet' type='text/css'>";
			echo "<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>";
		}     
        
		$file = BLOG_PATH  . "bitzero/other_sources/font-awesome/css/font-awesome.min.css";
    	if(file_exists($file)) {
            echo "<!-- Fonts Awesome -->";
            echo '<link rel="stylesheet" href="' . BLOG_URL  . 'bitzero/other_sources/font-awesome/css/font-awesome.min.css">'; 
        }        

        echo '<!-- Twitter & Tumblr Scripts -->';
        $file = BLOG_PATH  . "bitzero/other_sources/tumblr/share.js";
        if(file_exists($file)) {
            echo '<script src="' . BLOG_URL . 'bitzero/other_sources/tumblr/share.js"></script>';
        } else {
            echo '<script src="http://platform.tumblr.com/v1/share.js"></script>';	
        }
    
        $file = BLOG_PATH  . "bitzero/other_sources/twitter/widgets.js";
        if(file_exists($file)) {
            echo '<script id="twitter-wjs" src="' . BLOG_URL  . 'bitzero/other_sources/twitter/widgets.js"></script>';
        } else {
            echo '<script id="twitter-wjs" src="http://platform.twitter.com/widgets.js"></script>';	
        }
    ?>

    
   
	<!-- jQuery I8N Language Switcher -->
	<script type="text/javascript">
		$(document).ready(function() {
			$("#i18nLanguageOptions").on("change", function() {
				$.cookies.set('i18nLanguage', $("#i18nLanguageOptions option:selected").val());
				location.reload();
			});					
		});
		function OpenWind(w, h, qUrl) {
			window.open(qUrl, 'Window', 'width=' + w + ',height=' + h + ',location=yes,personalbar=no,menubar=no,resizable=yes,status=no,scrollbars=no,toolbar=no');
			return false;
		}
	</script>
    <!-- User Header Injection -->
    <?php echo HEADER_INJECT; ?>
    
    <!-- Plugin Header Injection -->
    <?php action::run('dp_header'); ?>
<?php 

} 

/*-----------------------------------------------------------------------------------*/
/* Dropplets Footer
/*-----------------------------------------------------------------------------------*/
// jquery moved to header
function get_footer() { ?>
	<!-- Post Pagination -->   
    <?php if (!IS_SINGLE && PAGINATION_ON_OFF !== "off") { ?>
    <script type="text/javascript">
			var infinite = true;
			var next_page = 1;
			var loading = false;
			var no_more_posts = false;
			$(function() {
				function load_next_page() {
					$.ajax({
						url: "index.php?page=" + next_page,
						beforeSend: function () {
							$('body').append('<article class="loading-frame"><div class="row"><div class="one-quarter meta"></div><div class="three-quarters"><?php
                                    $file = BLOG_PATH  . "bitzero/imgs/loading.gif";
                                    if(file_exists($file)) {
                                        echo '<img src="' . BLOG_URL . 'bitzero/imgs/loading.gif" alt="Loading" width="80" style="margin-left:20%;float:left;">';
									} else {
										echo '<img src="' . BLOG_URL . 'templates/' . ACTIVE_TEMPLATE . '/loading.gif" alt="Loading" width="180" style="margin-left:20%;float:left;">';	
									}							
							?></div></div></article>');
							$("body").animate({ scrollTop: $("body").scrollTop() + 250 }, 1000);
						},
						success: function (res) {
							next_page++;
							var result = $.parseHTML(res);
							var articles = $(result).filter(function() {
								return $(this).is('article');
							});
							if (articles.length < 2) {  //There's always one default article, so we should check if  < 2
								$('.loading-frame').html(<?php _t("You've reached the end of this list."); ?>);
								no_more_posts = true;
							}  else {
								$('.loading-frame').remove();
								$('body').append(articles.slice(1));
							}
							loading = false;
						},
						error: function() {
							$('.loading-frame').html(<?php _t("An error occurred while loading posts."); ?>);
							//keep loading equal to false to avoid multiple loads. An error will require a manual refresh
						}
					});
				}
				$(window).scroll(function() {
					var when_to_load = $(window).scrollTop() * 0.32;
					if (infinite && (loading != true && !no_more_posts) && $(window).scrollTop() + when_to_load > ($(document).height()- $(window).height() ) ) {
						// Sometimes the scroll function may be called several times until the loading is set to true.
						// So we need to set it as soon as possible
						loading = true;
						setTimeout(load_next_page,500);
					}
				});
			});
    </script>
	<?php				 
		}
        //##########################################################################################################
        include('./bitzero/src/footer.php');
        //##########################################################################################################
    ?>        
    
    <!-- Dropplets Tools -->
    <?php include('./dropplets/tools.php'); ?>
    
    <!-- User Footer Injection -->
    <?php echo FOOTER_INJECT; ?>
    
    <!-- Plugin Footer Injection -->
    <?php action::run('dp_footer'); ?>
<?php 

}
?>
