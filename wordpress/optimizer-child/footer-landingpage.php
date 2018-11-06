<?php 
/**
 * The Footer for Landingpage Template
 *
 * Displays the footer area of the template.
 *
 * @package LayerFramework
 * 
 * @since  LayerFramework 1.0
 */
global $optimizer;?>

<?php /*To Top Button */?>
	
    <a id="return-to-top" class="to_top animate-in" onclick="to_top()"><i class="fas fa-angle-up"></i></a>

    <script>
    window.onscroll = function() { to_top_button() }; 

    function to_top_button() {
        const x = document.getElementById("return-to-top");
        if (document.body.scrollTop >= 50 || document.documentElement.scrollTop >= 50) {        // If page is scrolled more than 50px
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
    function to_top() {      // When arrow is clicked
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0;
    }
    </script>
    
<!--Footer Start-->
    <div id="footer"<?php if (!empty ($optimizer['copyright_center'])) { ?> class="footer_center"<?php } ?> <?php optimizer_schema_item_type('footer'); ?>>
        <div class="center">
        <?php if ( is_active_sidebar( 'foot_sidebar' ) ) { ?>
            <!--Footer Widgets START-->
            <div class="widgets">
                <ul>
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( 'foot_sidebar' ) ) : ?><?php endif; ?>
                </ul>
            </div>
            <!--Footer Widgets END-->
        <?php } ?>  
        </div>
        
        <!--Copyright Footer START-->
        <div id="copyright" class="soc_right<?php if (!empty ($optimizer['copyright_center'])) { ?> copyright_center<?php } ?>">
            <div class="center">
        
                <!--FOOTER MENU START-->   
                <?php if (!empty ($optimizer['footmenu_id']) || is_customize_preview()) { ?>
                <div id="footer-links" class="<?php if (empty ($optimizer['footmenu_id'])) echo 'hide_footmenu'; ?>"><?php wp_nav_menu( array( 'container_class' => 'menu-footer', 'theme_location' => 'footer', 'depth' => '1') ); ?></div>
                <?php } ?>
                <!--FOOTER MENU END-->
                    
                <!--SOCIAL ICONS START-->  
                <div class="social"><?php if ($optimizer['social_bookmark_pos'] == 'footer' || $optimizer['social_bookmark_pos'] == 'topfoot'  || $optimizer['social_bookmark_pos'] == 'headfoot' || is_customize_preview()) { ?><?php get_template_part('framework/core','social'); ?><?php } ?></div>
                <!--SOCIAL ICONS END-->

                <!--Site Copyright Text START-->
                <div class="copytext"><p>© by EC-Nordbund</p></div>
                <!--Site Copyright Text END-->
                
            </div><!--Center END-->

        </div><!--Copyright Footer END-->
    </div><!--Footer END-->

</body>
</html>