<?php
if(markdownType == "php") {
        // original from Dropplets
        include('./dropplets/includes/markdown.php');
        function MarkdownToHtml($text) {
           return Markdown($text);
        }     
} else if(markdownType == "ppd") {
    if(file_exists(BLOG_PATH . "bitzero/other_sources/parsedown/Parsedown.php")) {
        include('./bitzero/other_sources/parsedown/Parsedown.php');
        function MarkdownToHtml($text) {
            return Parsedown::instance()->parse($text);
        }
    } else {
        // original from Dropplets
        include('./dropplets/includes/markdown.php');
        function MarkdownToHtml($text) {
           return Markdown($text);
        }      
    }
} else if(markdownType == "pme") {
    if(file_exists(BLOG_PATH . "bitzero/other_sources/markdown-extra-extended/markdown_extended.php")) {
        include('./bitzero/other_sources/markdown-extra-extended/markdown_extended.php');
        function MarkdownToHtml($text) {
            return MarkdownExtended($text);
        }
    } else {
        // original from Dropplets
        include('./dropplets/includes/markdown.php');
        function MarkdownToHtml($text) {
            return Markdown($text);
        }
    }        
} else {
    // original from Dropplets
    include('./dropplets/includes/markdown.php');
    function MarkdownToHtml($text) {
        return Markdown($text);
    }
}  

?>