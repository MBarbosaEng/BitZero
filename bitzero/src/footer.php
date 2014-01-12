<?php 
    if (markdownType == "mkd") {
        echo '<script language="JavaScript" type="text/javascript" src="' . BLOG_URL . 'bitzero/other_sources/MarkdownDeep/MarkdownDeep.min.js"></script>';
?><script type="text/javascript">
if(!window.jQuery)
{
   var script = document.createElement('script');
   script.type = "text/javascript";
   script.src = "./bitzero/js/jquery-1.10.2.min.js";
   document.getElementsByTagName('head')[0].appendChild(script);
}
        $(document).ready(function(){
          if ( $('#contentPost').length > 0){
            var markdown = new MarkdownDeep.Markdown();
            markdown.ExtraMode = true;
            markdown.SafeMode = false;
            var output = markdown.Transform($('#contentPost').html());
            $("#contentPost").html(output);
          }
        });
</script><?php 
    } else if (markdownType == "js") {            
        echo '<script language="JavaScript" type="text/javascript" src="' . BLOG_URL . 'bitzero/other_sources/marked/marked.js"></script>';
?><script type="text/javascript">
        $(document).ready(function(){ 
          if ( $('#contentPost').length > 0){
            var output = marked($("#contentPost").html());
            $("#contentPost").html(output);
          }
        });
</script><?php
    }                
?>

	<div style="text-align:center; font-size:11px; bottom:0; position: fixed;">
			<?php echo trim(BLOG_COPYRIGHT); ?>&nbsp;-&nbsp;<?php _e("Developed with:"); ?>&nbsp;&nbsp;<a class="dp-link" href="http://bit.ly/BitZero" target="_blank">BitZero</a>&nbsp;&&nbsp;<a class="dp-link" href="http://bit.ly/Dropplets" target="_blank">Dropplets</a>&nbsp;&&nbsp;
            <a class="dp-link" href="http://fortawesome.github.io/Font-Awesome/" target="_blank">Font Awesome</a>&nbsp;&&nbsp;<a class="dp-link" href="http://modernizr.com/" target="_blank">Modernizr</a>
            <?php                
                if(markdownType == "mkd") { 
                    $linkMkd = 'toptensoftware/MarkdownDeep'; $nameMkd = 'MarkdownDeep';
                } else if(markdownType == "js") { 
                    $linkMkd = 'chjj/marked'; $nameMkd = 'Marked';
                } else if(markdownType == "php") { 
                    $linkMkd = 'michelf/php-markdown'; $nameMkd = 'PHP MarkDown';
                } else if(markdownType == "pme") { 
                    $linkMkd = 'egil/php-markdown-extra-extended'; $nameMkd = 'PHP MarkDown Extended';       
                    echo '<option value="ppd">PHP ParseDown</option>';  
                } else {
                    $linkMkd = 'erusev/parsedown'; $nameMkd = 'PHP ParseDown';
                }               
                echo '&nbsp;&&nbsp;<a class="dp-link" href="https://github.com/' . $linkMkd . '" target="_blank">' . $nameMkd . '</a>';    
            ?>            
	</div>   