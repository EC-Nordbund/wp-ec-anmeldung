<?php 
/**
 * The SHARE THIS Function for LayerFramework
 *
 * Displays The social Bookmark icons on single posts page.
 *
 * @package LayerFramework
 * 
 * @since  LayerFramework 1.0
 */
global $optimizer;?>

<div class="social_bookmarks<?php if(!empty($optimizer['social_show_color'])) { ?> social_color<?php } ?> bookmark_<?php echo $optimizer['social_button_style'];?> bookmark_size_<?php echo $optimizer['social_bookmark_size']; ?>">
	  <?php do_action('optimizer_before_bookmarks'); ?>
	  <?php if(!empty($optimizer['facebook_field_id']) || is_customize_preview()){ ?>
      	<a target="_blank" class="ast_fb" href="<?php echo esc_url($optimizer['facebook_field_id']); ?>"><i class="fab fa-facebook"></i></a>
      <?php } ?>
      <?php if(!empty($optimizer['twitter_field_id']) || is_customize_preview()){ ?>
      	<a target="_blank" class="ast_twt" href="<?php echo esc_url($optimizer['twitter_field_id']); ?>"><i class="fab fa-twitter"></i></a><?php } ?>
      <?php if(!empty($optimizer['youtube_field_id']) || is_customize_preview()){ ?>
      	<a target="_blank" class="ast_ytb" href="<?php echo esc_url($optimizer['youtube_field_id']); ?>"><i class="fab fa-youtube-play"></i></a>
      <?php } ?>
      <?php if(!empty($optimizer['vimeo_field_id']) || is_customize_preview()){ ?>
      	<a target="_blank" class="ast_vimeo" href="<?php echo esc_url($optimizer['vimeo_field_id']); ?>"><i class="fab fa-vimeo"></i></a>
      <?php } ?>
      <?php if(!empty($optimizer['flickr_field_id']) || is_customize_preview()){ ?>
      	<a target="_blank" class="ast_flckr" href="<?php echo esc_url($optimizer['flickr_field_id']); ?>"><i class="fab fa-flickr"></i></a>
      <?php } ?>
      <?php if(!empty($optimizer['linkedin_field_id']) || is_customize_preview()){ ?>
      	<a target="_blank" class="ast_lnkdin" href="<?php echo esc_url($optimizer['linkedin_field_id']); ?>"><i class="fab fa-linkedin"></i></a>
      <?php } ?>
      <?php if(!empty($optimizer['pinterest_field_id']) || is_customize_preview()){ ?>
      	<a target="_blank" class="ast_pin" href="<?php echo esc_url($optimizer['pinterest_field_id']); ?>"><i class="fab fa-pinterest"></i></a>
      <?php } ?>
      <?php if(!empty($optimizer['tumblr_field_id']) || is_customize_preview()){ ?>
      	<a target="_blank" class="ast_tmblr" href="<?php echo esc_url($optimizer['tumblr_field_id']); ?>"><i class="fab fa-tumblr"></i></a>
      <?php } ?>
      <?php if(!empty($optimizer['dribble_field_id']) || is_customize_preview()){ ?>
      	<a target="_blank" class="ast_dribble" href="<?php echo esc_url($optimizer['dribble_field_id']); ?>"><i class="fab fa-dribbble"></i></a>
      <?php } ?>
      <?php if(!empty($optimizer['behance_field_id']) || is_customize_preview()){ ?>
      	<a target="_blank" class="ast_behance" href="<?php echo esc_url($optimizer['behance_field_id']); ?>"><i class="fab fa-behance"></i></a>
      <?php } ?>
      <?php if(!empty($optimizer['instagram_field_id']) || is_customize_preview()){ ?>
      	<a target="_blank" class="ast_insta" href="<?php echo esc_url($optimizer['instagram_field_id']); ?>"><i class="fab fa-instagram"></i></a>
      <?php } ?>  
      <?php if(!empty($optimizer['rss_field_id']) || is_customize_preview()){ ?>
      	<a target="_blank" class="ast_rss" href="<?php echo esc_url($optimizer['rss_field_id']); ?>"><i class="fas fa-rss"></i></a>
      <?php } ?>
      <?php do_action('optimizer_after_bookmarks'); ?>
</div>