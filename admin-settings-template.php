<?php
/**
 * Plugin Name: Admin Settings Template
 * Plugin URI: https://github.com/AgriLife/admin-settings-template
 * Description: Template for creating a settings page
 * Version: 1.0.0
 * Author: Zachary K. Watkins
 * Author URI: https://github.com/ZachWatkins
 * Author Email: zachary.watkins@ag.tamu.edu
 * License: GPL2+
 */

define( 'AST_DIR_PATH', plugin_dir_path( __FILE__ ) );

// Add the settings page
include( AST_DIR_PATH . 'src/Settings.php' );
$Settings = new \Admin\Settings;
$Settings->init();

add_action( 'admin_notices', 'ast_show_message' );

function ast_show_message(){

	$options = get_site_option('plugin_settings');

  ?>
  <div class="notice notice-warning">
      <p><pre><?php print_r($options); ?></pre></p>
  </div>
  <?php
}
