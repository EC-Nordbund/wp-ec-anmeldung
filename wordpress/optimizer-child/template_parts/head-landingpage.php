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
    <div class="header logo_center" width="100%">    
        <div class="center">
            <div class="head_inner">
            <!--LOGO START-->
                <div class="logo hide_sitetitle hide_sitetagline">
                	
					<?php if(!empty($optimizer['logo_image_id']['url'])) { ?>
                    	<?php do_action('optimizer_before_logo'); ?>
                        <a class="logoimga" title="<?php bloginfo('name') ;?>" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php $logo = $optimizer['logo_image_id']; echo $logo['url']; ?>" alt="<?php bloginfo('name') ;?>" <?php echo optimizer_image_attr( esc_url($optimizer['logo_image_id']['url']) ); ?> /></a>
                        <?php do_action('optimizer_after_logo'); ?>
                        <span class="desc logoimg_desc"><?php echo bloginfo('description'); ?></span>
                        
                    <?php } else { ?>
                        <?php do_action('optimizer_before_site_title'); ?>
                            
                        <h2><a href="<?php echo esc_url( home_url( '/' ) );?>"><?php bloginfo('name'); ?></a></h2>
                        <span class="desc"><?php echo bloginfo('description'); ?></span>

                        <?php do_action('optimizer_after_site_title'); ?>
                    <?php } ?>
                </div>
            <!--LOGO END-->
            </div>
        </div>
    </div>
<!--HEADER ENDS-->