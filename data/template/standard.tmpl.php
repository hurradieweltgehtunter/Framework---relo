<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/Article">
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <base href="<?php echo config::get('system')['baseURL'] . config::get('system')['subDir']; ?>">
        <meta name="robots" content="all, notranslate">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title><?php echo config::get('frontend')['title']; ?></title>
                    
        <meta name="description" content="<?php echo config::get('frontend')['description']; ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Schema.org markup for Google+ -->
        <meta itemprop="name" content="<?php echo config::get('frontend')['title']; ?>">
        <meta itemprop="description" content="<?php echo config::get('frontend')['description']; ?>">

        <!-- Twitter Card data -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@publisher_handle">
        <meta name="twitter:title" content="<?php echo config::get('frontend')['title']; ?>">
        <meta name="twitter:description" content="<?php echo config::get('frontend')['description']; ?>">
        <meta name="twitter:creator" content="@author_handle">

        <!-- Open Graph data -->
        <meta property="og:locale" content="de_DE">
        <meta property="og:title" content="<?php echo config::get('frontend')['title']; ?>" />
        <meta property="og:type" content="article" />
        <meta property="og:description" content="<?php echo config::get('frontend')['description']; ?>" />
        <meta property="og:site_name" content="<?php echo config::get('frontend')['title']; ?>" />
        <meta property="article:section" content="Photography" />
        
        <?php
            $tags = explode(',', config::get('frontend')['tags']);
            foreach($tags as $tag)
                echo '<meta property="article:tag" content="' . $tag . '" />' . "\r\n";
        ?>
        
        <meta property="fb:admins" content="<FB PROFILE ID>" />


        <!-- Schema.org markup for Google+ -->
        <meta itemprop="image" content="http://www.example.com/image.jpg">

        <!-- Twitter summary card with large image must be at least 280x150px -->
        <!-- <meta name="twitter:image:src" content="http://www.example.com/image.html"> -->

        <!-- Open Graph data -->
        <meta property="og:url" content="http://wearethekidsyourparentswarnedyouabout.com/" />
        <meta property="og:image" content="http://example.com/image.jpg" />
        <meta property="article:published_time" content="YYYY-mm-ddThh_mm:00+01:00" />
        <meta property="article:modified_time" content="YYYY-mm-ddThh_mm:00+01:00" />

        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="data/css/reset.css">
        <link rel="stylesheet" href="data/css/style.css">
        <link rel="stylesheet" href="data/css/basic/bootstrap.min.css">
        <link rel="stylesheet" href="data/css/style.css">


        <?php
        foreach($this->cssContents as $cssFile)
            echo '<link rel="stylesheet" href="' . $cssFile . '" type="text/css" media="all" title="no title" charset="utf-8">' . "\n";
        ?>
    </head>
    <body> 
        
        <?php 
        echo $this->OutputContainer;
        ?>  
        
        <script src="data/js/basic/jquery-2.1.4.min.js"></script>
        <script src="data/js/basic/bootstrap.min.js"></script>

        <script src="data/js/vendor/jquery.backstretch.min.js"></script>
        <script src="data/js/vendor/bootstrap.tab.js"></script>
        <script src="data/js/vendor/dropzone.js"></script>
        
        
        <script src="data/js/basic.js"></script>

        <?php
        foreach($this->jsContents as $jsFile)
            echo '<script src="' . $jsFile . '"></script>' . "\n";
        ?>
    </body>
</html>



