<?php
add_shortcode('LRTM-STYLE3', 'lergs_shortcode_style3' );
function lergs_shortcode_style3() {
ob_start();?>
    <div class="row">
        <?php
		$args = array('post_type' => 'lrtm_teams', 'posts_per_page' => -1, 'order' => 'DESC' );
		$loop = new WP_Query( $args );
		while ( $loop->have_posts() ) : $loop->the_post();
        $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
		$lrtmstart_position = get_post_meta( get_the_ID(), 'lrtm_position', true );
		$facebook = get_post_meta( get_the_ID(), 'lrtm_facebook', true );
		$twitter = get_post_meta( get_the_ID(), 'lrtm_twitter', true );
		$lrtm_linkdin = get_post_meta( get_the_ID(), 'lrtm_linkdin', true );
		$lrtm_grid_layout = get_option('lrtm_grid_layout');
		$lrtm_show_social = get_option('lrtm_show_social');
		?>
        <div class="col-md-<?php echo $lrtm_grid_layout['lrtm_grid_layout']; ?>">
            <div class="our-team">
                <div class="pic">
                    <img src="<?php echo $featured_img_url;?>">
                   <?php if($lrtm_show_social['lrtm_show_social'] == 'yes'){?>
                    <ul class="social">
                        <li><a href="<?php echo $facebook ; ?>" class="fa fa-facebook"></a></li>
                        <li><a href="<?php echo $twitter ; ?>" class="fa fa-twitter"></a></li>
                        <li><a href="<?php echo $lrtm_linkdin ; ?>" class="fa fa-linkedin"></a></li>
                  </ul>
                  <?php }?>
                </div>
                <div class="team-content">
                    <h3 class="title"><?php echo the_title(); ?></h3>
                    <?php  if(!empty($lrtmstart_position)){?>
                  <span class="post"><?php echo $lrtmstart_position ?></span>
                   <?php }?>
                </div>
            </div>
        </div>
 <?php endwhile; wp_reset_query();?>
    </div>
<?php return ob_get_clean();}