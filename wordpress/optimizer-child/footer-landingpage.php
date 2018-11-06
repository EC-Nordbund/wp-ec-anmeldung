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

    <!--Footer Start-->
    <div id="footer">
           
    <!--FOOTER MENU START-->   
    <?php if (!empty ($optimizer['footmenu_id']) || is_customize_preview()) { ?>
    
        <div id="footer-links" class="<?php if (empty ($optimizer['footmenu_id'])) echo 'hide_footmenu'; ?>"><?php wp_nav_menu( array( 'container_class' => 'menu-footer', 'theme_location' => 'footer', 'depth' => '1') ); ?></div>
    
        <?php } ?>
    <!--FOOTER MENU END-->
        
    <!--SOCIAL ICONS START-->  
    <div class="social"><?php if ($optimizer['social_bookmark_pos'] == 'footer' || $optimizer['social_bookmark_pos'] == 'topfoot'  || $optimizer['social_bookmark_pos'] == 'headfoot' || is_customize_preview()) { ?><?php get_template_part('framework/core','social'); ?><?php } ?></div>
    <!--SOCIAL ICONS END-->

    <!--Site Copyright Text START-->
    <div class="copyright"><p>© by EC-Nordbund</p></div>
    <!--Site Copyright Text END-->

</div><!--Footer END-->

<script>
     window.addEventListener("resize", footerResize);
     footerResize();

    function footerResize() {
        const footer = document.getElementById("footer");

        var height = footer.offsetHeight;
        footer.style.bottom = "-" + height +"px";
    }
</script>

</body>
</html>