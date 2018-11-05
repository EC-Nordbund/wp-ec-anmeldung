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
<style>
    .header_wrap.layer_wrapper {
        /* height: 96px; */
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

</style>
</head>

<body>

<?php do_action('optimizer_body_top'); ?>

<!--HEADER-->
	<?php do_action('optimizer_before_header'); ?>
        <div class="header_wrap layer_wrapper">
            <?php get_template_part('template_parts/head','landingpage'); ?>
        </div>
    <?php do_action('optimizer_after_header'); ?>
<!--Header END-->
