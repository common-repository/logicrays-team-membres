<?php
/**
 * Plugin Name: Logicrays Team Membres
 * Version: 1.0
 * Description: Logicrays Team Membres to the admin panel which allows you to show your team/staff/employee/people on your website the easy and best format
 * Author: Logicrays
 */
 
define("lrtm-team-membres","lrtm-team-membres" );
define('lrtm_plugin_url', plugins_url('', __FILE__));
ini_set('allow_url_fopen',1);

add_action('admin_menu' , 'lrtm_settings_page');
function lrtm_settings_page() {
	 add_submenu_page(
        'edit.php?post_type=lrtm_teams',
        __('Settings', 'lrtm-team-membres'),
        __('Settings', 'lrtm-team-membres'),
        'manage_options',
        'lrtm-setting-page',
       'lrtm_setting_page');
}
function lrtm_action_links( $links ) {
 $links = array_merge( array(
  '<a href="' . esc_url( admin_url( '/edit.php?post_type=lrtm_teams&page=lrtm-setting-page' ) ) . '">' . __( 'Settings', 'lrtm-team-membres' ) . '</a>'
 ), $links );
 return $links;
}
add_action( 'plugin_action_links_' . plugin_basename(__FILE__), 'lrtm_action_links' );
function lrtm_setting_page(){?>
<div class="wrap">
<div class="icon32" id="icon-options-general"><br>
</div>
<h2>Logicrays Team member Options</h2>
<h3>== Shortcode ==</h3>
<h4><strong style="font-weight:700">For style1:</strong> [LRTM-STYLE1]</h4>
<h4><strong style="font-weight:700">For style2:</strong> [LRTM-STYLE2]</h4>
<h4><strong style="font-weight:700">For style3:</strong> [LRTM-STYLE3]</h4>

<form action="options.php" method="post">
<?php
settings_fields("section");
?>
<?php
do_settings_sections("team-options");
submit_button();
?>
</form>
</div>
<?php
}
add_action("admin_init", "lrtm_team_fields");
function lrtm_team_fields()
{
	add_settings_section("section", "All Settings", null, "team-options");	
	add_settings_field("lrtm_image_hover_animation", "Image Hover Animation", "lrtm_image_hover_animation_element", "team-options", "section");
	add_settings_field("lrtm_grid_layout", "Team Style", "lrtm_grid_layout_element", "team-options", "section");
	add_settings_field("lrtm_show_social", "Show social ?", "lrtm_show_social_element", "team-options", "section");
	
	register_setting("section", "lrtm_image_hover_animation");
	register_setting("section", "lrtm_grid_layout");
	register_setting("section", "lrtm_show_social");	
}
function lrtm_style() {
	wp_enqueue_style('bootstrap-grid-min', lrtm_plugin_url.'/css/bootstrap-grid.min.css');
	wp_enqueue_style('font-awesome-min', lrtm_plugin_url.'/css/font-awesome.min.css');
}
add_action( 'wp_head', 'lrtm_style' );

$lrtm_image_hover_animation = get_option('lrtm_image_hover_animation');

if($lrtm_image_hover_animation['lrtm_image_hover_animation'] == '1'){
	include_once 'includes/lrtm-team-style1.php';
	add_action( 'wp_head', 'lrtm_style1' );
}
if($lrtm_image_hover_animation['lrtm_image_hover_animation'] == '2'){
	include_once 'includes/lrtm-team-style2.php';
	add_action( 'wp_head', 'lrtm_style2' );
}
if($lrtm_image_hover_animation['lrtm_image_hover_animation'] == '3'){
	include_once 'includes/lrtm-team-style3.php';
	add_action( 'wp_head', 'lrtm_style3' );
}
function lrtm_style1() {
	wp_enqueue_style('team-style1', lrtm_plugin_url.'/css/style1.css');	
}
function lrtm_style2() {
	wp_enqueue_style('team-style2', lrtm_plugin_url.'/css/style2.css');
}
function lrtm_style3() {
	wp_enqueue_style('team-style3', lrtm_plugin_url.'/css/style3.css');
}

add_action( 'init', 'lrtm_teams_post_type' );
function lrtm_teams_post_type() {
    $labels = array(
        'name' => 'LR Teams',
        'singular_name' => 'LR Team',
        'add_new' => 'Add New Member',
        'add_new_item' => 'Add New Member',
        'edit_item' => 'Edit Team',
        'new_item' => 'New Team',
        'view_item' => 'View Team',
        'search_items' => 'Search Teams Member',
        'not_found' =>  'No Teams found',
        'not_found_in_trash' => 'No Teams in the trash',
		'featured_image' => __( 'Member Image' ),
		'set_featured_image' => __( 'Set Member Image' ),
		'remove_featured_image' => __( 'Remove Member Image' ),
		'use_featured_image' => __( 'Use as Member Image' )
    );
    register_post_type( 'lrtm_teams', array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'exclude_from_search' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 10,
        'supports' => array( 'title', 'thumbnail' ),
  		'register_meta_box_cb' => 'lrtm_add_team_metaboxes',
    ) );
}

function lrtm_add_team_metaboxes(){
 add_meta_box('lrtm_teams_position','Team Details','lrtm_teams_position_callback','lrtm_teams','normal','high');
 add_meta_box('lrtm_teams_social', 'Social Details', 'lrtm_teams_social_callback', 'lrtm_teams', 'normal', 'high'); 
}
add_action('add_meta_boxes', 'lrtm_add_team_metaboxes');

function lrtm_teams_position_callback( $post ) { 

    wp_nonce_field( 'lrtm_teams_metabox_nonce', 'lrtm_teams_nonce'); ?>

  <?php         
    $position = get_post_meta( $post->ID, 'lrtm_position', true );
    $member_bio   = get_post_meta( $post->ID, 'lrtm_member_bio', true );
  ?>
  <p>   
    <label for="lrtm_position"><?php _e('Position', 'lrtm-team-membres' ); ?></label><br/>    
    <input type="text" class="widefat" name="lrtm_position" value="<?php echo esc_attr( $position ); ?>" />
  </p>
<?php }
function lrtm_teams_social_callback( $post ) { 

wp_nonce_field( 'lrtm_social_metabox_nonce', 'lrtm_social_nonce'); ?>

  <?php         
    $facebook  = get_post_meta( $post->ID, 'lrtm_facebook', true );
	$twitter   = get_post_meta( $post->ID, 'lrtm_twitter', true );
	$linkdin   = get_post_meta( $post->ID, 'lrtm_linkdin', true );
  ?>
   <p>
  <label for="lrtm_facebook"><?php _e('Facebook', 'lrtm-team-membres' ); ?></label><br/> 
  <input type="text" class="widefat" name="lrtm_facebook" value="<?php echo esc_attr( $facebook ); ?>" />
  </p> 
   <p>
  <label for="lrtm_twitter"><?php _e('Twitter', 'lrtm-team-membres' ); ?></label><br/> 
  <input type="text" class="widefat" name="lrtm_twitter" value="<?php echo esc_attr( $twitter ); ?>" />
  </p> 
   <p>
  <label for="lrtm_linkdin"><?php _e('Linkdin', 'lrtm-team-membres' ); ?></label><br/> 
  <input type="text" class="widefat" name="lrtm_linkdin" value="<?php echo esc_attr( $linkdin ); ?>" />
  </p>
<?php }

function lrtm_teams_save_meta( $post_id ) {

  if( !isset( $_POST['lrtm_teams_nonce'] ) || !wp_verify_nonce( $_POST['lrtm_teams_nonce'],'lrtm_teams_metabox_nonce') ) 
    return;

  if ( !current_user_can( 'edit_post', $post_id ))
    return;

  if ( isset($_POST['lrtm_position']) ) {        
    update_post_meta($post_id, 'lrtm_position', sanitize_text_field( $_POST['lrtm_position']));      
  }

}
add_action('save_post', 'lrtm_teams_save_meta');

function lrtm_social_save_meta( $post_id ) {

  if( !isset( $_POST['lrtm_social_nonce'] ) || !wp_verify_nonce( $_POST['lrtm_social_nonce'],'lrtm_social_metabox_nonce') ) 
    return;

  if ( !current_user_can( 'edit_post', $post_id ))
    return;
  if ( isset($_POST['lrtm_facebook']) ) {        
    update_post_meta($post_id, 'lrtm_facebook', sanitize_text_field($_POST['lrtm_facebook']));      
  }
  if ( isset($_POST['lrtm_twitter']) ) {        
    update_post_meta($post_id, 'lrtm_twitter', sanitize_text_field($_POST['lrtm_twitter']));      
  }
  if ( isset($_POST['lrtm_linkdin']) ) {        
    update_post_meta($post_id, 'lrtm_linkdin', sanitize_text_field($_POST['lrtm_linkdin']));      
  }
}
add_action('save_post', 'lrtm_social_save_meta');
/* function calling */
function lrtm_image_hover_animation_element()
{
$options = get_option('lrtm_image_hover_animation');
?>
<select id="lrtm_image_hover_animation" name='lrtm_image_hover_animation[lrtm_image_hover_animation]'>
<option value='1' <?php selected( $options['lrtm_image_hover_animation'], '1' ); ?>><?php _e( 'Style1', 'lrtm-team-membres'); ?></option>
<option value='2' <?php selected( $options['lrtm_image_hover_animation'], '2' ); ?>><?php _e( 'Style2', 'lrtm-team-membres'); ?></option>
<option value='3' <?php selected( $options['lrtm_image_hover_animation'], '3' ); ?>><?php _e( 'Style3', 'lrtm-team-membres'); ?></option>
</select>
<p class="description"><?php _e( 'Choose an animation effect.' ); ?></p>
<?php
}
function lrtm_grid_layout_element(){
$options = get_option('lrtm_grid_layout');
?>
<select id="lrtm_grid_layout" name='lrtm_grid_layout[lrtm_grid_layout]'>
<option value='6'<?php selected($options['lrtm_grid_layout'],'6'); ?>><?php _e( 'Two Column', 'lrtm-team-membres'); ?></option>
<option value='4'<?php selected($options['lrtm_grid_layout'],'4'); ?>><?php _e( 'Three Column', 'lrtm-team-membres' ); ?></option>
<option value='3'<?php selected($options['lrtm_grid_layout'],'3'); ?>><?php _e( 'Four Column', 'lrtm-team-membres' ); ?></option>
</select>
<p class="description"><?php _e( 'Choose a column layout.'); ?></p>
<?php
}
function lrtm_show_social_element()
{
$options = get_option('lrtm_show_social');
?>
<select id="lrtm_show_social" name='lrtm_show_social[lrtm_show_social]'>
<option value='yes' <?php selected( $options['lrtm_show_social'], 'yes' ); ?>><?php _e( 'Yes', 'lrtm-team-membres'); ?></option>
<option value='no' <?php selected( $options['lrtm_show_social'], 'no' ); ?>><?php _e( 'No', 'lrtm-team-membres'); ?></option>
</select>
<p class="description"><?php _e( 'Choose an social.' ); ?></p>
<?php }