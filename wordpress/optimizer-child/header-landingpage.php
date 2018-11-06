<?php 
/**
 * The Header for Landingpage Template
 *
 * @package LayerFramework
 * 
 * @since  LayerFramework 1.0
 */
 
global $optimizer;
$optimizer = optimizer_option_defaults();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?> <?php optimizer_schema_page_type(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo( 'charset' ); ?>" />	
<?php // Google Chrome Frame for IE ?>
<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"/>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
<style>
    html, body {
        margin: 0;
        padding: 0;
        background-color: #2b2b2b;
    }

    #header {
        height: 96px;
        width: 100%;
        position: fixed;
        left: 0;
        top: 0;
        box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
        z-index: 999;
        background-color: #8fb217;
        float: left;
        background-size: cover;
    }

    .center {
        width: 85%;
        margin: 0 auto;
    }

    .header_inner {
        position: relative;
        padding: 8px 0;
        width: 100%;
        float: left;
        display: table;
    }

    .logo {
        float: left;
        width: 100%;
        text-align: center;
    }

    a {
        background-color: transparent;
        text-decoration: none;
        -webkit-text-decoration-skip: objects;
        color: #8fb217;
    }

    img {
        border-style: none;
    }

    .logo img {
        border: none;
        margin: 10px 0;
        max-width: 100%;
        height: auto;
    }

    #content {
        width: 100%;
        margin-top: 64px;
        padding-bottom: 256px;
    }

    body .no_sidebar {
        width: 100%;
    }

    .message_wrap {
        max-width: 512px;
        margin: auto;
    }

    #return-to-top {
        width: 50px;
        height: 50px;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
        background-color: #8fb217;
        -webkit-transition: display 0.2s ease-out;
        -moz-transition: display 0.2s ease-out;
        -ms-transition: display 0.2s ease-out;
        -o-transition: display 0.2s ease-out;
        transition: display 0.2s ease-out;
        box-shadow: 0 6px 10px 0 rgba(0,0,0,0.4);
        position: fixed;
        bottom: 20px;
        right: 20px;
        cursor: pointer;
        display: none;
        z-index: 99;
        text-align: center;
        color: #fff;
        font-size: larger;
    }

    #return-to-top:hover {
        box-shadow: 0 6px 10px 0 rgba(0,0,0,0.4);
        transform: scale(1.10);
    }

    #return-to-top i {
        color: #ffffff;
        position: relative;
        margin: 0;
        font-size: 20px;
        top: 13px;
    }

    #footer {
        background-color: #333333;
        width: 100%;
        position: fixed;
        bottom: 0;
    }

    #copyright a,
    #copyright {
        width: 100%;
        float: left;
        text-align: center;
        color: #999999;
    }

    .copytext {
        padding: 20px 0;
        line-height: 1.9em;
    }

    #footer-links,
    #footer-links ul li {
        display: inline-block;
    }

    #footer-links ul li {
        margin: 0 12px;
        padding: 4px:
    }

    .social .social_bookmarks a {
        font-size: 24px;
        display: inline-block;
        padding: 2px 8px;
        text-align: center;
        opacity: 0.6;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        -webkit-transition: all 0.2s ease-out;
        -moz-transition: all 0.2s ease-out;
        -ms-transition: all 0.2s ease-out;
        -o-transition: all 0.2s ease-out;
        transition: all 0.2s ease-out;
    }
    

</style>
</head>

<body>
    <!--HEADER-->
            <?php get_template_part('template_parts/head','landingpage'); ?>
    <!--Header END-->
