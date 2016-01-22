<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/Article">
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <base href="<?php echo config::get('system')['baseURL'] . config::get('system')['subDir']; ?>backend/">
        <meta name="robots" content="all, notranslate">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title><?php echo config::get('frontend')['title']; ?> - Backend</title>
                    
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="data/css/reset.css">
        <link rel="stylesheet" href="../data/css/basic/bootstrap.min.css">
        <link rel="stylesheet" href="data/css/vendor/tablesorter.theme.bootstrap.css">
        
        <link rel="stylesheet" href="data/css/style.css">

        <?php
        foreach($this->cssContents as $cssFile)
            echo '<link rel="stylesheet" href="' . $cssFile . '" type="text/css" media="all" title="no title" charset="utf-8">' . "\n";
        ?>
    </head>
    <body> 
        <div id="notifier"></div>
        <?php 
        echo $this->OutputContainer;
        ?>  

        <script src="../data/js/basic/jquery-2.1.4.min.js"></script>
        <script src="../data/js/basic/bootstrap.min.js"></script>
        
        <script src="../data/js/vendor/dropzone.js"></script>
        <script src="data/js/vendor/jquery.tablesorter.js"></script>
        <script src="data/js/vendor/jquery.tablesorter.widgets.js"></script>
        

        <script src="data/js/basic.js"></script>

        <script>
            clientId = <?php if(request::get(1) != '') echo request::get(1); else echo '0'; ?>
        </script>

        <?php

        foreach($this->jsContents as $jsFile)
            echo '<script src="' . $jsFile . '"></script>' . "\n";
        ?>  
    </body>
</html>



