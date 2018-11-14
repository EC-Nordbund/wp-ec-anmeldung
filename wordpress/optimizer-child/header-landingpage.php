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
<title>Anmeldung bestätigen | EC-Nordbund</title>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo( 'charset' ); ?>" />	
<?php // Google Chrome Frame for IE ?>
<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"/>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
<style>
    * {
        box-sizing: border-box; 
    }

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
        padding: 3vh;
        background-color: #8fb217;
        text-align: center;
        height: 14vh;
        box-shadow: 0 2px 16px rgba(0,0,0,0.6);
    }

    #header img {
        height: 8vh;
    }

    #content {
        padding: 2em;
        min-height: 58vh;
    }

    #message {
        margin: 0 auto;
        max-width: calc(480px + 20vw);
        background: #eeeeee;
        color: #333333;
        padding: 1em;
        border-radius: 0.3em;
        box-shadow: 2px 8px 24px 0px rgba(0,0,0, 0.2)
    }

    #message h1 {
        font-size: 24px;
        color: #8fb217;
    }

    #message p {
        line-height: 1em;
    }

    #message a {
        color: #d0044d;
    }

    #footer {
        height: 28vh;
        background-color: #333333;
        text-align: center;
        color: #999999;
        font-size: 14px;
        padding: 1em;
    }

    #footer-links ul {
        padding-left: 0;
    }

    #footer-links ul li {
        margin: 0 12px;
        padding: 2vh;
        display: inline-block;
    }

    @media only screen and (max-width: 480px) {
        #footer-links ul li {
            padding: 10px;
            display: block;
        }

        #content {
            padding: 1em;
        }

        .social .social_bookmarks a {
            padding: 2vh 32px !important;
        }

        #footer {
            height: unset;
            padding-bottom: 8vh;
        }
    }

    .social .social_bookmarks a {
        font-size: 24px;
        display: inline-block;
        padding: 2vh 8px;
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
        padding-top: 1vh;
    }

    #loader {
        border: 6px solid #111;
        border-radius: 50%;
        border-top: 6px solid #8fb217;
        margin: 4em auto;
        width: 48px;
        height: 48px;
        -webkit-animation: spin 1s linear infinite; /* Safari */
        animation: spin 1s linear infinite;
    }

        /* Safari */
    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Add animation to "page content" */
    .animate-bottom {
        position: relative;
        -webkit-animation-name: animatebottom;
        -webkit-animation-duration: 0.5s;
        animation-name: animatebottom;
        animation-duration: 0.5s
    }

    @-webkit-keyframes animatebottom {
        from { bottom:-100px; opacity:0 } 
        to { bottom:0px; opacity:1 }
    }

    @keyframes animatebottom { 
        from{ bottom:-100px; opacity:0 } 
        to{ bottom:0; opacity:1 }
    }
</style>
</head>

<body>
    <!--HEADER-->
            <?php get_template_part('template_parts/head','landingpage'); ?>
    <!--Header END-->
