<!DOCTYPE html>
<html lang="<?php echo str_replace("_","-",strtolower(language)); ?>">
    <head>
        <meta charset="<?php echo strtolower(encoding); ?>">       
        <title><?php echo($page_title); ?></title>    
        <?php
						if (isset($page_meta)) { // warning correction
							echo($page_meta);
						}
		?> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="<?php echo($template_dir_url); ?>style.css">
        <link rel="stylesheet" href="<?php echo($template_dir_url); ?>subdiv.css">
		<?php get_header(); ?>
		<!-- personalization -->
        <link rel="stylesheet" href="<?php echo($template_dir_url); ?>personal.css" >
    </head>

    <body>
	<?php echo($menu_site); ?>

<article class="single not-found">
    <div class="row">
        <div class="one-quarter meta">
            <div class="thumbnail"><i class="fa fa-exclamation finfo"></i>
            </div>
        </div>

        <div class="three-quarters post">
            <h1><?php _e("Sorry, But That's Not Here"); ?></h1>
            <p><?php _e("Really sorry, but what you're looking for isn't here."); ?><br/>
				<?php _e("Click the button below to find something else that might interest you."); ?></p>

            <ul class="actions">
                <a href="<?php echo($blog_url); ?>" style="border-bottom: 0px !important;"><?php _e('More Articles'); ?>&nbsp;<i class="fa-df fa-map-marker"></i></a>
            </ul>
        </div>
    </div>
</article>

        <?php get_footer(); ?>
    </body>
</html>