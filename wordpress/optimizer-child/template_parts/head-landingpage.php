<?php 
/**
 * Header type 1 for LayerFramework
 *
 * Displays The Header type 1. This file is imported in header.php
 *
 * @package LayerFramework
 * 
 * @since  LayerFramework 1.0
 */
global $optimizer;?>

<!--HEADER STARTS-->
    <div id="header">
        <div class="center">    
            <div class="logo">
                
                <?php if(!empty($optimizer['logo_image_id']['url'])) { ?>
                    <a class="logo_image" title="<?php bloginfo('name') ;?>" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php $logo = $optimizer['logo_image_id']; echo $logo['url']; ?>" alt="<?php bloginfo('name') ;?>" <?php echo optimizer_image_attr( esc_url($optimizer['logo_image_id']['url']) ); ?> /></a>
                <?php } else { ?>
                    <h2><a href="<?php echo esc_url( home_url( '/' ) );?>"><?php bloginfo('name'); ?></a></h2>
                <?php } ?>

            </div>
        </div>
    </div>
<!--HEADER ENDS-->