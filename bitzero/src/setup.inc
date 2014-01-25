<?php
if (file_exists('./config.php')) {
    echo "<p style='color:red;'>It seems that you had config! Please don't run files without permissions.</p>";
} else { ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8" />
            <title>Let's Get Started</title>
        <?php
            if (file_exists("./bitzero/other_sources/font-awesome/css/font-awesome.min.css")) {
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
                if (file_exists("./bitzero/other_sources/font-awesome/css/font-awesome.min.css")) {
                    echo '<link href="./bitzero/other_sources/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">';
                }
            ?>    
        </head>

        <body class="dp-install">
            <form method="POST" action="./dropplets/save.php">
            <?php
                if (file_exists("./bitzero/other_sources/font-awesome/css/font-awesome.min.css")) {
                    echo '<i class="fa dp-icon-setup"></i>';
                } else {
                    echo '<a class="dp-icon-dropplets" href="http://dropplets.com" target="_blank"></a>';
                }
            ?>    
                <h2>Install</h2>
                <p>Welcome to an easier way to blog.</p>
            <?php
                if (file_exists("./bitzero/other_sources/font-awesome/css/font-awesome.min.css")) { ?>   
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
                if (file_exists("./bitzero/other_sources/font-awesome/css/font-awesome.min.css")) { ?> 
                <button type="submit" name="submit" value="submit"><i class="fa fa-check"></i></button>
            <?php } else { ?>    
    		    <button type="submit" name="submit" value="submit">k</button>
            <?php } ?>   
    		</form>
                
            <?php if (!$is_writable) { ?>            
                <p style="color:red;">It seems that your cache folder is not writable, please add the necessary permissions.</p>
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
}
?> 