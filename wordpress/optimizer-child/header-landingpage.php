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
<?php // Meta Tags ?>
<?php wp_head(); ?>
</head>

<body <?php body_class();?>>

<?php do_action('optimizer_body_top'); ?>

<!--HEADER-->
	<?php do_action('optimizer_before_header'); ?>
        <div class="header_wrap layer_wrapper">
            <?php get_template_part('template_parts/head','landingpage'); ?>
        </div>
    <?php do_action('optimizer_after_header'); ?>
<!--Header END-->
