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
    body {
        margin: 0;
        background-color: #2b2b2b;

        font-family: 'Cantarell', sans-serif;
        font-size: 16px;
        font-weight: 400;
    }

    a {
        text-decoration: none;
        color: inherit;
    }

    #header {
      position: absolute;
      top: 0;
      width: 100%;
      background-color: #8fb217;
      text-align: center;
      padding: 20px 0;
      z-index: 999;
    }
    
    #content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);

    }

    #footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        padding: 12px 0;
        background-color: #333333;
        text-align: center;
        color: #999999;
        font-size: 14px;
    }

    #footer-links ul {
        padding-left: 0;
    }

    #footer-links ul li {
        margin: 0 12px;
        padding: 4px;
        display: inline-block;
    }

    @media only screen and (max-width: 480px) {
        #footer-links ul li {
            padding: 10px;
            display: block;
        }
        .social .social_bookmarks a {
            padding: 16px 32px !important;
        }
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
    
    .copyright {
        padding-top: 32px;
    }
</style>
</head>

<body>
    <!--HEADER-->
            <?php get_template_part('template_parts/head','landingpage'); ?>
    <!--Header END-->
