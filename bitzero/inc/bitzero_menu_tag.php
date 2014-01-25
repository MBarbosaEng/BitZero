<?php
/*
Plugin Name: Menu & Tags
Plugin filename: bitzero_menu_tag.php
Version: 1.0.0
 
 
LICENSE

Copyright © 2014, M Barbosa <MBarbosaEng@EngBit.com.br> 
Twitter: @MBarbosaEng
http://www.EngBit.com.br/ 
All rights reserved.

Redistribution and  use in source forms, with or without modification, are permitted provided that the following conditions are met:
- Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer 
  in the documentation and/or other materials provided with the distribution.
- Neither the name “BitZero” or “EngBit” nor the names of its contributors may be used to endorse or promote products derived
  from this software without specific prior written permission.
- Commercial use of this software or source code must be communicated to the copyright holders and contributors.

This software is provided by the copyright holders and contributors “as is” and any express or implied warranties, including, but not limited to, 
the implied warranties of merchantability and fitness for a particular purpose are disclaimed. In no event shall the copyright owner or 
contributors be liable for any direct, indirect, incidental, special, exemplary, or consequential damages (including, but not limited to, 
procurement of substitute goods or services; loss of use, data, or profits; or business interruption) however caused and on any theory of liability,
whether in contract, strict liability, or tort (including negligence or otherwise) arising in any way out of the use of this software, 
even if advised of the possibility of such damage.
*/

/*-----------------------------------------------------------------------------------*/
/* Get Posts for Selected Tag
/*-----------------------------------------------------------------------------------*/
 
 function get_posts_for_tag($tag) {
     $tag = trim(strtolower($tag));
     return get_all_posts(array("tag" => $tag));
 }

/*-----------------------------------------------------------------------------------*/
/* Get all categories and tags for make menu
/*-----------------------------------------------------------------------------------*/
function get_Menu() {
    $menuTags = '';
    $post_tags = array();
    $TagFile = BLOG_PATH . "bitzero/other_sources/tagcanvas/jquery.tagcanvas.min.js";
    
	$menu  = '<script type="text/javascript">';
	$menu .= "function OpenWin(w, h, qUrl) {window.open(qUrl, 'Window', 'width=' + w + ',height=' + h + ',location=yes,personalbar=no,menubar=no,resizable=no,status=no,scrollbars=no,toolbar=no'); return false;}";
	$menu .= "function changeMenu(){ $('#mnu').toggle(); $('#btnMenu').toggle(); }";
	$menu .= "function changeCat(){ $('#mnuCat').toggle(); $('#idTagsMenu').toggle(); }";    
  	$menu .= '</script>';  
  		$file = BLOG_PATH . "templates/" . ACTIVE_TEMPLATE . "/menu.css";
    	if(file_exists($file)) {   
            $menu .= '<link rel="stylesheet" href="' . BLOG_URL . 'templates/' . ACTIVE_TEMPLATE . '/menu.css" type="text/css">';
		} else {
			$menu .= '<link rel="stylesheet" href="' . BLOG_URL . 'bitzero/css/menu.css" type="text/css">';
		}	 
	$menu .= "<div id='btnMenu'><button onclick='javascript:changeMenu()' class='myButton'><i class='fa fa-list'></i></button>";
    $menu .= "<br/><button onclick=\"window.location.href='" . BLOG_URL . "';\" class='myButton pright'><i class='fa fa-map-marker'></i></button>";
    $menu .= "</div>";	
	$menu .= "<div class='mnuright' id='mnu' style='display:none;'>";
	$menu .= "<span class='mnuSpan'><button onclick='javascript:changeMenu()' class='myButton'><i class='fa fa-list'></i></button></span>";

    $menu .= "<ul id='mnuPages' class='mnurightUl'>";
    //static pages listing
    if($handle = opendir(PAGE_DIR)) {
        $pages = array();
        while (false !== ($entry = readdir($handle))) {
            if(substr(strrchr($entry,'.'),1)==ltrim(FILE_EXT, '.')) {
                // Define the post file.
                $fcontents = file(PAGE_DIR.$entry);
                // Define the page name.
                $page = str_replace(array("\n", '#'), '', $fcontents[0]) . "|" . str_replace(FILE_EXT, '', $entry);
                // Pull everything together for the loop.
                if (in_array($page,$pages)){
                    // value is in array - nothing to do
                } else {
                    $pages[] = $page;
                }
            }
        }

        $paglnk = array();
        if (count($pages)>0) {
            // don't need sort
            foreach($pages as $lnk)
            {
                if (strpos($lnk,'|') !== false) {
                    $paglnk = explode('|',$lnk); 
                                        
                    //$paglnk = split('\|',$lnk);
                    // http://localhost/bitzero/?filename=bitzero
                    $menu .= "<li class='menu'><a href='" . BLOG_URL . "?filename=" . str_replace(' ','+',trim($paglnk[1])) . "'>" . trim($paglnk[0]) . "</a></li>";
                }
            }
        }
    }
        
    // Menu Categories
    $menu .= "<li class='menu'><a href='javascript:changeCat()'>" . _t("Categories") . "</a></li>"; 	
        $menu .= '<ul id="mnuCat" class="mnurightUl" style="display:none;">';       
        // listing directories
        if($handle = opendir(POSTS_DIR)) {
            $post_categories = array();
            while (false !== ($entry = readdir($handle))) {
                if(substr(strrchr($entry,'.'),1)==ltrim(FILE_EXT, '.')) {
                    // Get the post file.
                    $fcontents = file(POSTS_DIR.$entry);                  

                    // Get the post category
                    $temp_tags = explode(',', trim(str_replace(array("\n", '-'), '', $fcontents[4])));
                    // Pull everything together for the loop.                    
                    if (count($temp_tags)>0) {
                        asort($temp_tags);
                        foreach($temp_tags as $post_category) {
                            // Pull everything together for the loop.
                            if (in_array($post_category, $post_categories)){
                                // value is in array - nothing to do
                            } else {
                                $post_categories[] = $post_category;
                            }
                        }                    
                    }
                    
                    // Get the post tags
                    if(file_exists($TagFile)) {  
                        $temp_tags = explode(',', trim(str_replace(array("\n", '-'), '', $fcontents[6])));
                        // Pull everything together for the loop.                    
                        if (count($temp_tags)>0) {
                            asort($temp_tags);
                            foreach($temp_tags as $post_tag) {
                                // Pull everything together for the loop.
                                if (in_array($post_tag, $post_tags)){
                                    // value is in array - nothing to do
                                } else {
                                    $post_tags[] = $post_tag;
                                }
                            }                    
                        }
                    }
                }
            }
            if (count($post_categories)>0) {
                asort($post_categories);
                foreach($post_categories as $lnk)
                {
                    $menu .= "<li class='menu'><a href='" . BLOG_URL . "category/" . str_replace(' ','+',trim($lnk)) . "'>" . trim($lnk) . "</a></li>";
                }
            }
        }
        $menu  .= "</ul>";	  
    $menu .= "</ul>";
    
    // tag cloud
    $tags = '';
    if (count($post_tags)>0) {
        asort($post_tags);

        foreach($post_tags as $lnk)
        {
            $tags .= "<li><a href='" . BLOG_URL . "tag/" . urlencode(trim(strtolower($lnk))) . "'>" . trim($lnk) . "</a></li>";
        }
    
        if (!($tags == '' )) {
            $menuTags = '<!--[if lt IE 9]><script type="text/javascript" src="' . BLOG_URL . 'bitzero/other_sources/tagcanvas/excanvas.js"></script><![endif]-->';
            $menuTags .= '<script src="' . BLOG_URL . 'bitzero/other_sources/tagcanvas/jquery.tagcanvas.min.js" type="text/javascript"></script>';
            $menuTags .= '<div id="myCanvasContainer">';
            $menuTags .= '<canvas width="170" height="200" id="myCanvas"><p>Anything in here will be replaced on browsers that support the canvas element</p></canvas>';
            $menuTags .= '</div><div id="tagList"><ul>' . $tags . '</ul></div>';
            $menuTags .= '<script type="text/javascript">';
            $menuTags .= "$(document).ready(function() { if( ! $('#myCanvas').tagcanvas({ textColour : '#000000', outlineThickness : 1, maxSpeed : 0.03, depth : 0.75 },'tagList')) { ";
                        // TagCanvas failed to load
            $menuTags .= "$('#myCanvasContainer').hide(); } });";
            $menuTags .= "</script>";  
            
            $menu .= "<span style='text-align:center;' id='idTagsMenu'>" . $menuTags . "</span>";        
        }
    }
    
	$menu .= "<span class='mnuSpan' style='text-align:center;'>";
	$menu .= "<button onclick=\"window.location.href='" . BLOG_URL . "';\" class='myButton'><i class='fa fa-map-marker'></i></button>&nbsp;";
	$menu .= "<button onclick=\"window.location.href='mailto:" . BLOG_EMAIL . "?subject=Contact from " . BLOG_TITLE . "';\" class='myButton'><i class='fa fa-envelope-o fa-2'></i></button>&nbsp;";
	$menu  .= "<button onclick=\"javascript:OpenWin('600', '250','https://twitter.com/intent/tweet?text=" . BLOG_TITLE . "%20>%20" . BLOG_URL . "')\" title='" . _t('Comment on') . " Twitter' class='myButton'><i class='fa fa-comment-o'></i></button></span>";
	$menu  .= "</div>";	
	return $menu;
}
 
 
function get_Only_Tags(){ 
    $file = BLOG_PATH . "bitzero/tagcanvas/jquery.tagcanvas.min.js";
    $menuTags = '';
    if(file_exists($file)) {    
            // listing directories
            $tags = "";    
            if($handle = opendir(POSTS_DIR)) {
                $post_tags = array();
                while (false !== ($entry = readdir($handle))) {
                    if(substr(strrchr($entry,'.'),1)==ltrim(FILE_EXT, '.')) {
                        // Define the post file.
                        $fcontents = file(POSTS_DIR.$entry);
                        // Define the post category.
                        $temp_tags = explode(',', trim(str_replace(array("\n", '-'), '', $fcontents[6])));
                        if (count($temp_tags)>0) {
                            foreach($temp_tags as $post_tag) {
                                // Pull everything together for the loop.
                                if (in_array($post_tag, $post_tags)){
                                    // value is in array - nothing to do
                                } else {
                                    $post_tags[] = $post_tag;
                                }
                            }                    
                        }
                    }
                }
                if (count($post_tags)>0) {
                    asort($post_tags);
                    foreach($post_tags as $lnk)
                    {
                        $tags .= "<li><a href='" . BLOG_URL . "tag/" . urlencode(trim(strtolower($lnk))) . "'>" . trim($lnk) . "</a></li>";
                    }
                }
            }

            if (!($tags == '' )) {
                $menuTags = '<!--[if lt IE 9]><script type="text/javascript" src="' . BLOG_URL . 'bitzero/tagcanvas/excanvas.js"></script><![endif]-->';
                $menuTags .= '<script src="' . BLOG_URL . 'bitzero/tagcanvas/jquery.tagcanvas.min.js" type="text/javascript"></script>';
                $menuTags .= '<div id="myCanvasContainer">';
                $menuTags .= '<canvas width="170" height="200" id="myCanvas"><p>Anything in here will be replaced on browsers that support the canvas element</p></canvas>';
                $menuTags .= '</div><div id="tagList"><ul>' . $tags . '</ul></div>';
                $menuTags .= '<script type="text/javascript">';
                $menuTags .= "$(document).ready(function() { if( ! $('#myCanvas').tagcanvas({ textColour : '#000000', outlineThickness : 1, maxSpeed : 0.03, depth : 0.75 },'tagList')) { ";
                            // TagCanvas failed to load
                $menuTags .= "$('#myCanvasContainer').hide(); } });";
                $menuTags .= "</script>";               
            }
                    
    }       
    return $menuTags;        
}
/*-----------------------------------------------------------------------------------*/
/* Get all categories for make menu
/*-----------------------------------------------------------------------------------*/
function get_Only_Menu() {
	$menu  = '<script type="text/javascript">';
	$menu .= "function OpenWin(w, h, qUrl) {window.open(qUrl, 'Window', 'width=' + w + ',height=' + h + ',location=yes,personalbar=no,menubar=no,resizable=no,status=no,scrollbars=no,toolbar=no'); return false;}";
	$menu .= "function changeMenu(){ $('#mnu').toggle(); $('#btnMenu').toggle(); }";
	$menu .= "function changeCat(){ $('#mnuCat').toggle(); $('#idTagsMenu').toggle(); }";    
  	$menu .= '</script>';  
  		$file = BLOG_PATH . "templates/" . ACTIVE_TEMPLATE . "/menu.css";
    	if(file_exists($file)) {   
            $menu .= '<link rel="stylesheet" href="' . BLOG_URL . 'templates/' . ACTIVE_TEMPLATE . '/menu.css" type="text/css">';
		} else {
			$menu .= '<link rel="stylesheet" href="' . BLOG_URL . 'bitzero/css/menu.css" type="text/css">';
		}	 
	$menu .= "<div id='btnMenu'><button onclick='javascript:changeMenu()' class='myButton'><i class='fa fa-list'></i></button>";
    $menu .= "<br/><button onclick=\"window.location.href='" . BLOG_URL . "';\" class='myButton pright'><i class='fa fa-map-marker'></i></button>";
    $menu .= "</div>";		
	$menu .= "<div class='mnuright' id='mnu' style='display:none;'>";
	$menu .= "<span class='mnuSpan'><button onclick='javascript:changeMenu()' class='myButton'><i class='fa fa-list'></i></button></span>";

    $menu .= "<ul id='mnuPages' class='mnurightUl'>";
    //static pages listing
    if($handle = opendir(PAGE_DIR)) {
        $pages = array();
        while (false !== ($entry = readdir($handle))) {
            if(substr(strrchr($entry,'.'),1)==ltrim(FILE_EXT, '.')) {
                // Define the post file.
                $fcontents = file(PAGE_DIR.$entry);
                // Define the page name.
                $page = str_replace(array("\n", '#'), '', $fcontents[0]) . "|" . str_replace(FILE_EXT, '', $entry);
                // Pull everything together for the loop.
                if (in_array($page,$pages)){
                    // value is in array - nothing to do
                } else {
                    $pages[] = $page;
                }
            }
        }

        $paglnk = array();
        if (count($pages)>0) {
            asort($pages);
            foreach($pages as $lnk)
            {
                if (strpos($lnk,'|') !== false) {
                    $paglnk = explode('|',$lnk);                    
                    //$paglnk = split('\|',$lnk);
                    // http://localhost/bitzero/?filename=bitzero
                    $menu .= "<li class='menu'><a href='" . BLOG_URL . "?filename=" . str_replace(' ','+',trim($paglnk[1])) . "'>" . trim($paglnk[0]) . "</a></li>";
                }
            }
        }
    }
        
    // Menu Categories
    $menu .= "<li class='menu'><a href='javascript:changeCat()'>" . _t("Categories") . "</a></li>"; 	
        $menu .= '<ul id="mnuCat" class="mnurightUl" style="display:none;">';       
        // listing directories
        if($handle = opendir(POSTS_DIR)) {
            $post_categories = array();
            while (false !== ($entry = readdir($handle))) {
                if(substr(strrchr($entry,'.'),1)==ltrim(FILE_EXT, '.')) {
                    // Define the post file.
                    $fcontents = file(POSTS_DIR.$entry);
                    // Define the post category.
                    $post_category = str_replace(array("\n", '-'), '', $fcontents[4]);
                    
                    // Pull everything together for the loop.

                    if (in_array($post_category, $post_categories)){
                        // value is in array - nothing to do
                    } else {
                        $post_categories[] = $post_category;

                    }
                }
            }
            if (count($post_categories)>0) {
                asort($post_categories);
                foreach($post_categories as $lnk)
                {
                    $menu .= "<li class='menu'><a href='" . BLOG_URL . "category/" . str_replace(' ','+',trim($lnk)) . "'>" . trim($lnk) . "</a></li>";
                }
            }
        }
        $menu  .= "</ul>";	  
    $menu .= "</ul>";
    $menu .= "<span style='text-align:center;' id='idTagsMenu'>" . get_Menu_Tags() . "</span>";  

    
	$menu .= "<span class='mnuSpan' style='text-align:center;'>";
	$menu .= "<button onclick=\"window.location.href='" . BLOG_URL . "';\" class='myButton'><i class='fa fa-map-marker'></i></button>&nbsp;";
	$menu .= "<button onclick=\"window.location.href='mailto:" . BLOG_EMAIL . "?subject=Contact from " . BLOG_TITLE . "';\" class='myButton'><i class='fa fa-envelope-o fa-2'></i></button>&nbsp;";
	$menu  .= "<button onclick=\"javascript:OpenWin('600', '250','https://twitter.com/intent/tweet?text=" . BLOG_TITLE . "%20>%20" . BLOG_URL . "')\" title='" . _t('Comment on') . " Twitter' class='myButton'><i class='fa fa-comment-o'></i></button></span>";
	$menu  .= "</div>";	
	return $menu;
}
?>